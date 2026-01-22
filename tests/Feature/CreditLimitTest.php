<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;

class CreditLimitTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create(['role' => 'admin']);
        
        // Seed Standard Accounts needed by PosController
        \App\Models\ChartOfAccount::create(['id' => 1, 'name' => 'Persediaan', 'type' => 'asset', 'code' => '1-101']);
        \App\Models\ChartOfAccount::create(['id' => 2, 'name' => 'Kas', 'type' => 'asset', 'code' => '1-102']);
        \App\Models\ChartOfAccount::create(['id' => 4, 'name' => 'Pendapatan Cafe', 'type' => 'revenue', 'code' => '4-101']);
        \App\Models\ChartOfAccount::create(['id' => 5, 'name' => 'Pendapatan Roastery', 'type' => 'revenue', 'code' => '4-102']);
        // Need to check what code/ID accounts are used in PosController exactly.
        // Line 326: code 1-103 (Piutang)
        \App\Models\ChartOfAccount::create(['id' => 3, 'name' => 'Piutang Usaha', 'type' => 'asset', 'code' => '1-103']);
    }

    public function test_customer_can_buy_on_credit_if_within_limit()
    {
        $customer = Customer::factory()->create(['credit_limit' => 1000000]);
        $product = Product::factory()->create(['price' => 500000, 'stock' => 10, 'is_available' => true]);

        $payload = [
            'total_amount' => 500000,
            'payment_method' => 'Credit',
            'customer_id' => $customer->id,
            'payment_term' => 'net30',
            'cart' => json_encode([
                ['id' => $product->id, 'qty' => 1]
            ])
        ];

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

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertEquals(500000, $customer->outstanding_debt);
    }

    public function test_customer_cannot_buy_on_credit_if_limit_exceeded()
    {
        // 1. Customer Limit 1.000.000
        $customer = Customer::factory()->create(['credit_limit' => 1000000]);
        
        // 2. Existing Debt 900.000
        Transaction::factory()->create([
            'customer_id' => $customer->id,
            'total_amount' => 900000,
            'payment_status' => 'Unpaid'
        ]);

        $this->assertEquals(900000, $customer->outstanding_debt);

        // 3. Try to buy 200.000 (Total 1.100.000 > 1.000.000)
        $product = Product::factory()->create(['price' => 200000, 'stock' => 10, 'is_available' => true]);

        $payload = [
            'total_amount' => 200000,
            'payment_method' => 'Credit',
            'customer_id' => $customer->id,
            'payment_term' => 'net30',
            'cart' => json_encode([
                ['id' => $product->id, 'qty' => 1]
            ])
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('pos.store'), $payload);

        $response->assertStatus(500); // Exception throws 500 usually unless caught
        // Or check validation message if we handled it gracefully. 
        // In current implementation it throws \Exception.
    }
}
