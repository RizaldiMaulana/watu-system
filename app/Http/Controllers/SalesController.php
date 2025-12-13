<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query()->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(15);
        $totalSalesToday = Transaction::whereDate('created_at', today())
                                      ->whereNull('voided_at')
                                      ->sum('total_amount');

        return view('sales.index', compact('transactions', 'totalSalesToday'));
    }

    public function show($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with('items.product')->firstOrFail();
        return view('sales.show', compact('transaction'));
    }

    public function void($uuid, Request $request)
    {
        $request->validate([
            'void_reason' => 'required|string|max:255'
        ]);

        $transaction = Transaction::where('uuid', $uuid)->firstOrFail();

        if ($transaction->voided_at) {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // 1. Mark Transaction as Void
            $transaction->update([
                'voided_at' => now(),
                'void_reason' => $request->void_reason,
                'payment_status' => 'Void'
            ]);

            // 2. Return Stock
            foreach ($transaction->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                     if ($product->recipes->count() > 0) {
                        foreach ($product->recipes as $recipe) {
                            $recipe->ingredient->increment('stock', $recipe->amount_needed * $item->quantity);
                        }
                     } else {
                        $product->increment('stock', $item->quantity);
                     }
                }
            }

            // 3. Reverse Journal
            // Find original journal
            $originalJournal = DB::table('journals')->where('ref_number', $transaction->invoice_number)->first();
            
            if ($originalJournal) {
                // Create Reversal Journal (Swap Debit and Credit)
                $journalId = DB::table('journals')->insertGetId([
                    'ref_number' => $transaction->invoice_number . '-VOID',
                    'transaction_date' => now(),
                    'description' => 'VOID Reversal: ' . $transaction->invoice_number . ' Reason: ' . $request->void_reason,
                    'total_debit' => $originalJournal->total_debit, 
                    'total_credit' => $originalJournal->total_credit,
                    'created_at' => now(), 'updated_at' => now(),
                ]);

                $details = DB::table('journal_details')->where('journal_id', $originalJournal->id)->get();
                foreach ($details as $detail) {
                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId,
                        'account_id' => $detail->account_id,
                        'debit' => $detail->credit,   // Swap
                        'credit' => $detail->debit,   // Swap
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan (Void). Stok dikembalikan & Jurnal dibalik.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
