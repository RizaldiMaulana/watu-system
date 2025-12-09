<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccountingController extends Controller
{
    // --- DASHBOARD ---
    public function index()
    {
        // Simple counts for dashboard
        $accountCount = ChartOfAccount::count();
        $journalCount = Journal::count();
        
        // Calculate total assets, liabilities, etc. for quick view (optional, can be heavy)
        // For now just return counts and recent journals
        $recentJournals = Journal::with('details')->latest()->take(5)->get();

        return view('accounting.dashboard', compact('accountCount', 'journalCount', 'recentJournals'));
    }

    // --- CHART OF ACCOUNTS ---
    public function coa()
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        return view('accounting.coa', compact('accounts'));
    }

    public function storeCoa(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:chart_of_accounts,code',
            'name' => 'required',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        ChartOfAccount::create($request->all());

        return redirect()->back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function updateCoa(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:chart_of_accounts,code,' . $id,
            'name' => 'required',
            'type' => 'required',
        ]);

        $account = ChartOfAccount::findOrFail($id);
        $account->update($request->all());

        return redirect()->back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroyCoa($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        
        // Check if has transactions
        $exists = JournalDetail::where('account_id', $id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun yang sudah memiliki transaksi.');
        }

        $account->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }

    // --- MANUAL JOURNAL ---
    public function createManualJournal()
    {
        $accounts = ChartOfAccount::all();
        return view('accounting.manual-journal', compact('accounts'));
    }

    public function storeManualJournal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_date' => 'required|date',
            'description' => 'required',
            'details' => 'required|array|min:2',
            'details.*.account_id' => 'required|exists:chart_of_accounts,id',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validate Balance
        $totalDebit = collect($request->details)->sum('debit');
        $totalCredit = collect($request->details)->sum('credit');

        if ($totalDebit != $totalCredit) {
            return redirect()->back()->with('error', 'Debit dan Kredit tidak seimbang!')->withInput();
        }

        DB::transaction(function () use ($request, $totalDebit, $totalCredit) {
            $journal = Journal::create([
                'ref_number' => 'JV-' . time(), // Journal Voucher
                'transaction_date' => $request->transaction_date,
                'description' => $request->description,
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
            ]);

            foreach ($request->details as $detail) {
                if ($detail['debit'] > 0 || $detail['credit'] > 0) {
                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'account_id' => $detail['account_id'],
                        'debit' => $detail['debit'],
                        'credit' => $detail['credit'],
                    ]);
                }
            }
        });

        return redirect()->route('accounting.manual-journal')->with('success', 'Jurnal berhasil disimpan.');
    }

    // --- FINANCIAL REPORTS ---
    public function balanceSheet(Request $request)
    {
        $endDate = $request->input('date', date('Y-m-d'));

        // Assets
        $assets = ChartOfAccount::where('type', 'asset')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'debit')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'credit')
            ->get()
            ->map(function($account) {
                $account->balance = $account->journal_details_sum_debit - $account->journal_details_sum_credit;
                return $account;
            });

        // Liabilities
        $liabilities = ChartOfAccount::where('type', 'liability')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'debit')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'credit')
            ->get()
            ->map(function($account) {
                // Liability usually Credit Balance
                $account->balance = $account->journal_details_sum_credit - $account->journal_details_sum_debit;
                return $account;
            });

        // Equity
        $equity = ChartOfAccount::where('type', 'equity')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'debit')
            ->withSum(['journalDetails' => function($query) use ($endDate) {
                $query->whereHas('journal', function($q) use ($endDate) {
                    $q->whereDate('transaction_date', '<=', $endDate);
                });
            }], 'credit')
            ->get()
            ->map(function($account) {
                 // Equity usually Credit Balance
                $account->balance = $account->journal_details_sum_credit - $account->journal_details_sum_debit;
                return $account;
            });
            
        // CALCULATE RETAINED EARNINGS (Current Period Profit/Loss)
        // Revenue - Expenses
        $revenue = $this->calculateTypeBalance('revenue', $endDate);
        $expense = $this->calculateTypeBalance('expense', $endDate); // Expense is Debit normal
        $netIncome = $revenue - $expense;

        return view('accounting.reports.balance-sheet', compact('assets', 'liabilities', 'equity', 'netIncome', 'endDate'));
    }

    public function incomeStatement(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $revenues = ChartOfAccount::where('type', 'revenue')
             ->withSum(['journalDetails' => function($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }], 'debit')
            ->withSum(['journalDetails' => function($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }], 'credit')
            ->get()
            ->map(function($account) {
                // Revenue Credit Normal
                $account->balance = $account->journal_details_sum_credit - $account->journal_details_sum_debit;
                return $account;
            });

        $expenses = ChartOfAccount::where('type', 'expense')
             ->withSum(['journalDetails' => function($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }], 'debit')
            ->withSum(['journalDetails' => function($query) use ($startDate, $endDate) {
                $query->whereHas('journal', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('transaction_date', [$startDate, $endDate]);
                });
            }], 'credit')
            ->get()
            ->map(function($account) {
                // Expense Debit Normal
                $account->balance = $account->journal_details_sum_debit - $account->journal_details_sum_credit;
                return $account;
            });

        $totalRevenue = $revenues->sum('balance');
        $totalExpense = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpense;

        return view('accounting.reports.income-statement', compact('revenues', 'expenses', 'totalRevenue', 'totalExpense', 'netIncome', 'startDate', 'endDate'));
    }

    public function cashFlow(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // --- ARUS KAS DARI AKTIVITAS OPERASIONAL ---
        
        // 1. Penerimaan Kas dari Pelanggan (Lawannya Kas Besar / Bank)
        // Cari Jurnal Detail yang Akunnya Kas (ID 2), Posisi Debit.
        // Asumsi: Semua Debit di Kas adalah Penerimaan.
        $cashIn = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->where('account_id', 2) // Kas Besar
            ->where('debit', '>', 0)
            ->sum('debit');

        // 2. Pembayaran Kas untuk Operasional (Lawannya Kas Besar)
        // Cari Jurnal Detail yang Akunnya Kas (ID 2), Posisi Kredit.
        // Asumsi: Semua Kredit di Kas adalah Pengeluaran.
        $cashOut = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->where('account_id', 2) // Kas Besar
            ->where('credit', '>', 0)
            ->sum('credit');

        $netOperatingCash = $cashIn - $cashOut;

        // --- INVESTASI & PENDANAAN (Belum ada fitur spesifik, set 0) ---
        $netInvestingCash = 0;
        $netFinancingCash = 0;

        $netChangeInCash = $netOperatingCash + $netInvestingCash + $netFinancingCash;

        // Saldo Awal (Total Debit - Kredit akun Kas sebelum Start Date)
        $beginningCash = JournalDetail::whereHas('journal', function($q) use ($startDate) {
                $q->where('transaction_date', '<', $startDate);
            })
            ->where('account_id', 2)
            ->sum(DB::raw('debit - credit'));

        $endingCash = $beginningCash + $netChangeInCash;

        return view('accounting.reports.cash-flow', compact(
            'startDate', 'endDate', 
            'cashIn', 'cashOut', 'netOperatingCash', 
            'netInvestingCash', 'netFinancingCash',
            'netChangeInCash', 'beginningCash', 'endingCash'
        ));
    }

    public function accountsPayable(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // Query Purchases: Unpaid
        $query = \App\Models\Purchase::where('payment_status', 'unpaid')
            ->with('supplier');

        // Optional: Filter by due date range if needed (though usually we want to see ALL debt)
        // For this report, we might want to see all outstanding debt, but maybe filter by *creation* date or *due* date?
        // Let's stick to showing ALL unpaid for now, but sort by closest Due Date.
        // If filters are applied, maybe filter by Due Date?
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('due_date', [$startDate, $endDate]);
        }

        $purchases = $query->orderBy('due_date', 'asc')->get();

        // Highlight: Top 3 Closest Due Date (including overdue)
        // We can just take the first 3 from the sorted list
        $urgentDebts = $purchases->take(3);

        $totalDebt = $purchases->sum(function($p) {
            return $p->total_amount; // You might want to subtract already paid amount if partial payment exists (future feature)
        });

        return view('accounting.reports.accounts-payable', compact('purchases', 'urgentDebts', 'totalDebt', 'startDate', 'endDate'));
    }

    private function calculateTypeBalance($type, $endDate) 
    {
        // Helper to calculate total balance of a type up to a date
        $balance = DB::table('chart_of_accounts')
            ->join('journal_details', 'chart_of_accounts.id', '=', 'journal_details.account_id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->where('chart_of_accounts.type', $type)
            ->where('journals.transaction_date', '<=', $endDate)
            ->select(DB::raw('SUM(debit) as total_debit'), DB::raw('SUM(credit) as total_credit'))
            ->first();

        if ($type == 'revenue' || $type == 'liability' || $type == 'equity') {
            return ($balance->total_credit ?? 0) - ($balance->total_debit ?? 0);
        } else {
            return ($balance->total_debit ?? 0) - ($balance->total_credit ?? 0);
        }
    }
}
