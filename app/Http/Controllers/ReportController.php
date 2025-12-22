<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\JournalDetail;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function print(Request $request, $type)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));
        $export = $request->query('export') === 'excel';

        $viewName = "reports.print.{$type}";
        $data = [];

        switch ($type) {
            case 'balance-sheet':
                $data = $this->getBalanceSheetData($endDate);
                $data['title'] = 'Laporan Neraca (Balance Sheet)';
                $data['subtitle'] = "Per Tanggal: " . \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
                break;

            case 'income-statement':
                $data = $this->getIncomeStatementData($startDate, $endDate);
                $data['title'] = 'Laporan Laba Rugi (Income Statement)';
                $data['subtitle'] = "Periode: " . \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . " - " . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y');
                break;

            case 'cash-flow':
                $data = $this->getCashFlowData($startDate, $endDate);
                $data['title'] = 'Laporan Arus Kas (Cash Flow)';
                $data['subtitle'] = "Periode: " . \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') . " - " . \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y');
                break;

            case 'ar': // Accounts Receivable
                $data = $this->getARData($startDate, $endDate);
                $data['title'] = 'Laporan Piutang Usaha (AR)';
                $data['subtitle'] = "Status: Belum Lunas (Unpaid)";
                break;

            case 'ap': // Accounts Payable
                $data = $this->getAPData($startDate, $endDate);
                $data['title'] = 'Laporan Hutang Usaha (AP)';
                $data['subtitle'] = "Status: Belum Lunas (Unpaid)";
                break;

            default:
                abort(404);
        }

        if ($export) {
            return response()->view($viewName, $data)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', "attachment; filename={$type}_" . date('YmdHis') . ".xls")
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }

        return view($viewName, $data);
    }

    // --- DATA FETCHING HELPERS (Reused Logic) ---

    private function getARData($startDate, $endDate) {
        $query = Transaction::where('payment_status', 'Unpaid')
            ->whereNotNull('customer_id')
            ->with(['customer']);
        
        if ($startDate && $endDate) {
             // For AR, usually we want ALL unpaid details, maybe filtered by Invoice Date
             // Let's filter by Created At
             $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $items = $query->orderBy('due_date', 'asc')->get();
        return compact('items');
    }

    private function getAPData($startDate, $endDate) {
        $query = Purchase::where('payment_status', 'unpaid')
            ->with('supplier');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $items = $query->orderBy('due_date', 'asc')->get();
        return compact('items');
    }

    private function getBalanceSheetData($endDate) {
        // ... Reuse AccountingController logic ...
        // For brevity/DRY, ideally this is in a Service.
        // Copying logic for now to ensure independence.
        
        // Assets
        $assets = ChartOfAccount::where('type', 'asset')
            ->withSum(['journalDetails' => function($q) use ($endDate) {
                $q->whereHas('journal', fn($j) => $j->whereDate('transaction_date', '<=', $endDate));
            }], 'debit')
            ->withSum(['journalDetails' => function($q) use ($endDate) {
                $q->whereHas('journal', fn($j) => $j->whereDate('transaction_date', '<=', $endDate));
            }], 'credit')
            ->get()
            ->map(function($acc) {
                $acc->balance = $acc->journal_details_sum_debit - $acc->journal_details_sum_credit;
                return $acc;
            });

        // Liabilities & Equity
        $liabilities = $this->getGroupData('liability', $endDate);
        $equity = $this->getGroupData('equity', $endDate);

        // Retained Earnings
        $revenue = $this->calculateTypeBalance('revenue', $endDate);
        $expense = $this->calculateTypeBalance('expense', $endDate);
        $netIncome = $revenue - $expense;

        return compact('assets', 'liabilities', 'equity', 'netIncome', 'endDate');
    }

    private function getIncomeStatementData($startDate, $endDate) {
        $revenues = $this->getPeriodData('revenue', $startDate, $endDate);
        $expenses = $this->getPeriodData('expense', $startDate, $endDate);
        
        $totalRevenue = $revenues->sum('balance');
        $totalExpense = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpense;

        return compact('revenues', 'expenses', 'totalRevenue', 'totalExpense', 'netIncome', 'startDate', 'endDate');
    }

    private function getCashFlowData($startDate, $endDate) {
        // Reuse Cash Flow Logic
         $cashIn = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })->where('account_id', 2)->where('debit', '>', 0)->sum('debit');

        $cashOut = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })->where('account_id', 2)->where('credit', '>', 0)->sum('credit');

        $netOperatingCash = $cashIn - $cashOut;
        $netChangeInCash = $netOperatingCash; // + investing + financing (0)

        $beginningCash = JournalDetail::whereHas('journal', function($q) use ($startDate) {
                $q->where('transaction_date', '<', $startDate);
            })->where('account_id', 2)->sum(DB::raw('debit - credit'));

        $endingCash = $beginningCash + $netChangeInCash;

        return compact('cashIn', 'cashOut', 'netOperatingCash', 'netChangeInCash', 'beginningCash', 'endingCash', 'startDate', 'endDate');
    }

    // --- Helper Methods ---
    private function getGroupData($type, $endDate) {
        return ChartOfAccount::where('type', $type)
            ->withSum(['journalDetails' => function($q) use ($endDate) {
                $q->whereHas('journal', fn($j) => $j->whereDate('transaction_date', '<=', $endDate));
            }], 'debit')
            ->withSum(['journalDetails' => function($q) use ($endDate) {
                $q->whereHas('journal', fn($j) => $j->whereDate('transaction_date', '<=', $endDate));
            }], 'credit')
            ->get()
            ->map(function($acc) {
                $acc->balance = $acc->journal_details_sum_credit - $acc->journal_details_sum_debit; // Credit normal
                return $acc;
            });
    }

    private function getPeriodData($type, $startDate, $endDate) {
        return ChartOfAccount::where('type', $type)
             ->withSum(['journalDetails' => function($q) use ($startDate, $endDate) {
                $q->whereHas('journal', fn($j) => $j->whereBetween('transaction_date', [$startDate, $endDate]));
            }], 'debit')
            ->withSum(['journalDetails' => function($q) use ($startDate, $endDate) {
                $q->whereHas('journal', fn($j) => $j->whereBetween('transaction_date', [$startDate, $endDate]));
            }], 'credit')
            ->get()
            ->map(function($acc) use ($type) {
                if ($type == 'revenue') $acc->balance = $acc->journal_details_sum_credit - $acc->journal_details_sum_debit;
                else $acc->balance = $acc->journal_details_sum_debit - $acc->journal_details_sum_credit;
                return $acc;
            });
    }

    private function calculateTypeBalance($type, $endDate) {
        // Helper
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