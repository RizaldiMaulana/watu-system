<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ChartOfAccount;
use App\Models\Customer;

class PosJournalTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $roasteryCategory;
    protected $cafeCategory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);

        // 1. Seed Accounts
        ChartOfAccount::create(['id' => 1, 'name' => 'Persediaan', 'type' => 'asset', 'code' => '1-101']);
        ChartOfAccount::create(['id' => 2, 'name' => 'Kas', 'type' => 'asset', 'code' => '1-102']);
        ChartOfAccount::create(['id' => 3, 'name' => 'Piutang Usaha', 'type' => 'asset', 'code' => '1-103']); // For Credit
        ChartOfAccount::create(['id' => 4, 'name' => 'Pendapatan Cafe', 'type' => 'revenue', 'code' => '4-101']);
        ChartOfAccount::create(['id' => 5, 'name' => 'Pendapatan Roastery', 'type' => 'revenue', 'code' => '4-102']);
        ChartOfAccount::create(['id' => 6, 'name' => 'HPP', 'type' => 'expense', 'code' => '5-101']);

        // 2. Seed Categories
        $this->roasteryCategory = Category::create(['name' => 'Roast Bean', 'slug' => 'roast-bean', 'type' => 'roastery', 'sort_order' => 1]);
        $this->cafeCategory = Category::create(['name' => 'Beverage', 'slug' => 'beverage', 'type' => 'cafe', 'sort_order' => 2]);
    }

    public function test_pos_transaction_creates_journal_entries()
    {
        // 1. Setup Product (Roastery)
        $product = Product::factory()->create([
            'price' => 100000, 
            'cost_price' => 50000, // For COGS
            'stock' => 10, 
            'is_available' => true,
            'category_id' => $this->roasteryCategory->id
        ]);
        
        $customer = Customer::factory()->create();

        $payload = [
            'total_amount' => 100000,
            'payment_method' => 'Cash',
            'customer_id' => $customer->id,
            'cart' => json_encode([
                ['id' => $product->id, 'qty' => 1]
            ])
        ];

        // 2. Transact
        try {
            $response = $this->withoutExceptionHandling()
                ->actingAs($this->user)
                ->postJson(route('pos.store'), $payload);
        } catch (\Throwable $e) {
            dump($e->getMessage());
            dump($e->getTraceAsString());
            throw $e;
        }
        
        if ($response->status() !== 200) {
            dump($response->json());
        }
        
        $response->assertStatus(200);

        // 3. Verify Journal Header
        $this->assertDatabaseHas('journals', [
            'total_debit' => 150000, 
        ]);


        // 4. Verify Journal Details
        // Cash (Debit 100k)
        $this->assertDatabaseHas('journal_details', [
            'account_id' => 2, // Kas
            'debit' => 100000,
            'credit' => 0
        ]);

        // Revenue Roastery (Credit 100k)
        $this->assertDatabaseHas('journal_details', [
            'account_id' => 5, // Pendapatan Roastery
            'debit' => 0,
            'credit' => 100000
        ]);

        // COGS (Debit 50k)
        $this->assertDatabaseHas('journal_details', [
            'account_id' => 6, // HPP (ID 6 in setup, Check Service maps code 5-101 to ID ??)
            // Wait, in Service: $cogsAcc = ChartOfAccount::where('code', '5-101')->value('id');
            // In setup above: Code '5-101' is ID 6. So correct.
            'account_id' => 6,
            'debit' => 50000,
            'credit' => 0
        ]);

        // Inventory (Credit 50k)
        $this->assertDatabaseHas('journal_details', [
            'account_id' => 1, // Persediaan
            'debit' => 0,
            'credit' => 50000
        ]);
    }
}
