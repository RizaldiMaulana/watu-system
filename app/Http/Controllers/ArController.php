<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArController extends Controller
{
    // List Unpaid B2B/Credit Invoices (AR Aging)
    public function index(Request $request)
    {
        $query = Transaction::where('payment_status', 'Unpaid')
            ->whereNotNull('customer_id') // Only registered customers
            ->with(['customer', 'items']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        
        // Sort by Due Date (Closest first)
        $invoices = $query->orderBy('due_date', 'asc')->get();

        $totalReceivable = $invoices->sum('total_amount');

        return view('ar.index', compact('invoices', 'totalReceivable'));
    }

    public function show($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with(['customer', 'items.product'])->firstOrFail();
        return view('ar.show', compact('transaction'));
    }

    public function storePayment(Request $request, $uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->firstOrFail();

        if ($transaction->payment_status === 'Paid') {
            return back()->with('error', 'Invoice sudah lunas.');
        }

        $request->validate([
            'payment_method' => 'required', // Cash, Transfer
            'amount' => 'required|numeric|min:0',
        ]);

        $amount = (float) $request->amount;
        
        // For simplicity in this iteration, we assume full payment or manual handling of partial.
        // Let's assume Full Payment for now to close the status.
        // Or if amount >= total, mark Paid.
        
        DB::transaction(function () use ($request, $transaction, $amount) {
            // 1. Update Transaction
            // If partial payments are supported in future, we track 'paid_amount'.
            // For now, let's treat it as closing the invoice.
            $transaction->update([
                'payment_status' => 'Paid',
                'payment_method' => $request->payment_method . ' (Repayment)',
            ]);

            // 2. Create Journal (Debit Kas, Credit Piutang)
            // Kas ID = 2
            // Piutang ID = 1-103 (or find)
            
            $piutangAcc = DB::table('chart_of_accounts')->where('name', 'like', '%Piutang%')->value('id') ?? 103;
            $cashAcc = 2; // Default Kas
            if ($request->payment_method === 'Transfer') {
                 // Try find Bank Account
                 $bankAcc = DB::table('chart_of_accounts')->where('name', 'like', '%Bank%')->value('id');
                 if ($bankAcc) $cashAcc = $bankAcc;
            }

            $journalId = DB::table('journals')->insertGetId([
                'ref_number' => 'PAY-' . $transaction->invoice_number,
                'transaction_date' => now(),
                'description' => 'Pelunasan Piutang: ' . $transaction->invoice_number . ' (' . $transaction->customer_name . ')',
                'total_debit' => $amount,
                'total_credit' => $amount,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // Debit Kas
            DB::table('journal_details')->insert([
                'journal_id' => $journalId, 'account_id' => $cashAcc,
                'debit' => $amount, 'credit' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // Credit Piutang
            DB::table('journal_details')->insert([
                'journal_id' => $journalId, 'account_id' => $piutangAcc,
                'debit' => 0, 'credit' => $amount,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // 3. Record Payment
            \App\Models\TransactionPayment::create([
                'transaction_id' => $transaction->id,
                'payment_method' => $request->payment_method,
                'amount' => $amount,
                'reference_no' => 'AR-PAYMENT'
            ]);
        });

        return redirect()->route('ar.index')->with('success', 'Pembayaran berhasil dikonfirmasi & Jurnal tercatat.');
    }
}
