<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\ChartOfAccount;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'transaction_date' => 'required|date',
            'items' => 'required|array',
            'payment_method' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 1. Hitung Total
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }

                // 2. Simpan Header
                $purchase = Purchase::create([
                    'invoice_number' => 'PUR-' . time(),
                    'supplier_id' => $request->supplier_id,
                    'transaction_date' => $request->transaction_date,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes ?? null,
                ]);

                // 3. Simpan Detail & Update Stok
                foreach ($request->items as $item) {
                    $ingredient = Ingredient::find($item['ingredient_id']);

                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'ingredient_id' => $item['ingredient_id'],
                        'item_name' => $ingredient ? $ingredient->name : 'Unknown',
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);
                    
                    // AUTO INCREMENT STOCK
                    if ($ingredient) {
                        $ingredient->increment('stock', $item['quantity']);
                    }
                }

                // 4. Buat Jurnal (Panggil Helper Private Sekali Saja)
                $this->createJournal($purchase, $totalAmount, $request->payment_method);
            });

            return redirect()->route('purchases.create')->with('success', 'Stok bertambah & Jurnal tercatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function createJournal($purchase, $total, $method) {
        $journal = Journal::create([
            'ref_number' => $purchase->invoice_number,
            'transaction_date' => $purchase->transaction_date,
            'description' => 'Pembelian Stok: ' . $purchase->invoice_number,
            'total_debit' => $total, 'total_credit' => $total,
        ]);
        
        // Debit Persediaan
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => 1, 'debit' => $total, 'credit' => 0]);
        
        // Kredit Kas/Utang
        $creditAccount = ($method == 'cash') ? 2 : 3;
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => $creditAccount, 'debit' => 0, 'credit' => $total]);
    }
}