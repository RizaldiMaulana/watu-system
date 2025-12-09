<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyCogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:cogs';
    protected $description = 'Verify COGS Calculation and Journaling';

    public function handle()
    {
        $this->info('--- STARTING COGS VERIFICATION ---');

        // 1. SETUP DATA
        $ing = \App\Models\Ingredient::create(['name' => 'COGS Test Bean', 'unit' => 'kg', 'stock' => 10, 'cost_price' => 10000]);
        $this->info("[SETUP] Ingredient Created: Stock=10, Cost=10000");

        $prod = \App\Models\Product::create(['name' => 'COGS Test Coffee', 'category' => 'coffee', 'price' => 50000]);
        \App\Models\Recipe::create(['product_id' => $prod->id, 'ingredient_id' => $ing->id, 'amount_needed' => 1]);
        $this->info("[SETUP] Product Created with Recipe (Needs 1 unit of Bean)");

        // 2. TEST PURCHASE (Weighted Average)
        // Buying 10 @ 20,000.
        // Expected New Cost = ((10*10000) + (10*20000)) / 20 = 15,000.
        $this->info('--- TESTING PURCHASE ---');
        $req = new \Illuminate\Http\Request([
            'supplier_id' => 1,
            'transaction_date' => now(),
            'payment_method' => 'cash',
            'items' => [
                ['ingredient_id' => $ing->id, 'quantity' => 10, 'price' => 20000]
            ]
        ]);

        $purchaseController = new \App\Http\Controllers\PurchaseController();
        $purchaseController->store($req);

        $ing->refresh();
        $this->info("[PURCHASE] New Stock: " . $ing->stock . " (Expected: 20)");
        $this->info("[PURCHASE] New Cost: " . number_format($ing->cost_price, 2) . " (Expected: 15,000.00)");

        if ($ing->cost_price == 15000) {
             $this->info("✅ PURCHASE CHECK PASSED");
        } else {
             $this->error("❌ PURCHASE CHECK FAILED");
        }

        // 3. TEST SALE (POS)
        // Selling 1 Product.
        // Expected COGS = 1 * 15,000 = 15,000.
        $this->info('--- TESTING POS SALE ---');
        $cart = json_encode([
            ['id' => $prod->id, 'name' => $prod->name, 'price' => $prod->price, 'qty' => 1]
        ]);
        $reqPos = new \Illuminate\Http\Request([
            'total_amount' => 50000,
            'payment_method' => 'Cash',
            'cart' => $cart
        ]);

        $posController = new \App\Http\Controllers\PosController();
        $res = $posController->store($reqPos);
        $data = $res->getData(); // json response

        if ($data->status == 'success') {
            $txId = $data->transaction_id;
            $txItem = \App\Models\TransactionItem::where('transaction_id', $txId)->first();
            $this->info("[POS] Transaction Item Cost: " . number_format($txItem->cost_price, 2) . " (Expected: 15,000.00)");
            
            // Check Journal
            $journal = \App\Models\Journal::where('ref_number', \App\Models\Transaction::find($txId)->invoice_number)->first();
            $cogsAccount = \App\Models\ChartOfAccount::where('code', '5-101')->first();
            
            if ($cogsAccount) {
                $cogsEntry = \App\Models\JournalDetail::where('journal_id', $journal->id)
                            ->where('account_id', $cogsAccount->id)
                            ->first();
                
                if ($cogsEntry) {
                     $this->info("[POS] Journal COGS Debit: " . number_format($cogsEntry->debit, 2) . " (Expected: 15,000.00)");
                     if ($cogsEntry->debit == 15000) {
                         $this->info("✅ POS CHECK PASSED");
                     } else {
                         $this->error("❌ POS CHECK FAILED (Wrong Amount)");
                     }
                } else {
                     $this->error("❌ POS CHECK FAILED (No Journal Entry)");
                }
            } else {
                $this->error("❌ POS CHECK FAILED (Account 5-101 not found)");
            }
            
        } else {
            $this->error("[POS] FAILED: " . $data->message);
        }

        // CLEANUP
        if (isset($txId)) {
            \App\Models\TransactionItem::where('transaction_id', $txId)->delete();
            \App\Models\Transaction::find($txId)->delete();
        }
        $prod->recipes()->delete(); // Clean recipes
        $prod->delete();
        $ing->delete(); 
        $this->info('--- CLEANUP DONE ---');
    }
}
