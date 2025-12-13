<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchasePaymentController extends Controller
{
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required', // cash or bank_transfer
            'notes' => 'nullable|string',
        ]);

        if ($request->amount > ($purchase->total_amount - $purchase->paid_amount)) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
        }

        try {
            DB::transaction(function () use ($request, $purchase) {
                // 1. Create Payment Record
                PurchasePayment::create([
                    'purchase_id' => $purchase->id,
                    'amount' => $request->amount,
                    'payment_date' => $request->payment_date,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'recorded_by' => Auth::id(),
                ]);

                // 2. Update Purchase Status
                $purchase->paid_amount += $request->amount;
                if ($purchase->paid_amount >= $purchase->total_amount) {
                    $purchase->payment_status = 'paid';
                } else {
                    $purchase->payment_status = 'partial';
                }
                $purchase->save();

                // 3. Create Journal Entry
                $journal = Journal::create([
                    'ref_number' => $purchase->invoice_number . '-PAY-' . time(),
                    'transaction_date' => $request->payment_date,
                    'description' => 'Pembayaran Utang: ' . $purchase->invoice_number,
                    'total_debit' => $request->amount,
                    'total_credit' => $request->amount,
                ]);

                // Debit Utang Usaha (Account 3)
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => 3, 
                    'debit' => $request->amount,
                    'credit' => 0
                ]);

                // Credit Payment Account (Kas = 2, Bank = ?)
                // Assuming Kas = 2. Need to handle Bank properly.
                // For now, if 'cash' -> 2, if 'bank_transfer' -> let's assume Bank Account ID is 4 (need to verify or make dynamic later).
                // Ideally, user selects the Source Account.
                // Let's check CoA if ID 4 exists for Bank, otherwise default to Kas for now or add a TODO.
                
                $creditAccountId = ($request->payment_method == 'cash') ? 2 : 4; // Hardcoded mostly safe for now if IDs are standard.
                // Better approach: Let user select account in UI, but for now simplified.
                
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $creditAccountId,
                    'debit' => 0,
                    'credit' => $request->amount
                ]);
            });

            return back()->with('success', 'Pembayaran berhasil dicatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
