<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalDetail;
use App\Models\Transaction;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Get Balance Sheet Data
     */
    public function getBalanceSheet($endDate)
    {
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
                $account->balance = $account->journal_details_sum_credit - $account->journal_details_sum_debit;
                return $account;
            });

        // Retained Earnings (Net Income)
        $revenue = $this->calculateTypeBalance('revenue', $endDate);
        $expense = $this->calculateTypeBalance('expense', $endDate);
        $netIncome = $revenue - $expense;

        return compact('assets', 'liabilities', 'equity', 'netIncome', 'endDate');
    }

    /**
     * Get Income Statement Data
     */
    public function getIncomeStatement($startDate, $endDate)
    {
        $revenues = $this->getPeriodData('revenue', $startDate, $endDate);
        $expenses = $this->getPeriodData('expense', $startDate, $endDate);

        $totalRevenue = $revenues->sum('balance');
        $totalExpense = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpense;

        return compact('revenues', 'expenses', 'totalRevenue', 'totalExpense', 'netIncome', 'startDate', 'endDate');
    }

    /**
     * Get Cash Flow Data
     */
    public function getCashFlow($startDate, $endDate)
    {
        // 1. Operating Cash In
        $cashIn = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->where('account_id', 2) // Assuming ID 2 is Cash/Bank
            ->where('debit', '>', 0)
            ->sum('debit');

        // 2. Operating Cash Out
        $cashOut = JournalDetail::whereHas('journal', function($q) use ($startDate, $endDate) {
                $q->whereBetween('transaction_date', [$startDate, $endDate]);
            })
            ->where('account_id', 2)
            ->where('credit', '>', 0)
            ->sum('credit');

        $netOperatingCash = $cashIn - $cashOut;
        $netInvestingCash = 0;
        $netFinancingCash = 0;
        $netChangeInCash = $netOperatingCash + $netInvestingCash + $netFinancingCash;

        $beginningCash = JournalDetail::whereHas('journal', function($q) use ($startDate) {
                $q->where('transaction_date', '<', $startDate);
            })
            ->where('account_id', 2)
            ->sum(DB::raw('debit - credit'));

        $endingCash = $beginningCash + $netChangeInCash;

        return compact(
            'startDate', 'endDate',
            'cashIn', 'cashOut', 'netOperatingCash',
            'netInvestingCash', 'netFinancingCash',
            'netChangeInCash', 'beginningCash', 'endingCash'
        );
    }

    /**
     * Get Accounts Receivable Data
     */
    public function getAccountsReceivable($startDate = null, $endDate = null)
    {
        $query = Transaction::where('payment_status', 'Unpaid')
            ->whereNotNull('customer_id')
            ->with(['customer', 'items']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    /**
     * Get Accounts Payable Data
     */
    public function getAccountsPayable($startDate = null, $endDate = null)
    {
        $query = Purchase::where('payment_status', 'unpaid')
            ->with('supplier');

        if ($startDate && $endDate) {
            $query->whereBetween('due_date', [$startDate, $endDate]);
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    /**
     * Helper: Calculate total balance for a specific account type up to end date
     */
    private function calculateTypeBalance($type, $endDate)
    {
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

    /**
     * Helper: Get accounts with balances for a period
     */
    private function getPeriodData($type, $startDate, $endDate)
    {
        return ChartOfAccount::where('type', $type)
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
            ->map(function($account) use ($type) {
                if ($type == 'revenue') $account->balance = $account->journal_details_sum_credit - $account->journal_details_sum_debit;
                else $account->balance = $account->journal_details_sum_debit - $account->journal_details_sum_credit;
                return $account;
            });
    }
    // --- Transaction Journaling ---
    public function recordTransaction(Transaction $transaction)
    {
        // Ensure relationships are loaded
        $transaction->load(['items.product.category', 'payments']);

        $totalCogs = 0;
        $totalCafe = 0;
        $totalRoastery = 0;

        // 1. Calculate Allocations
        foreach ($transaction->items as $item) {
            $lineCogs = $item->cost_price * $item->quantity;
            $totalCogs += $lineCogs;

            $isRoastery = false;
            if ($item->product) {
                // Check if category is a loaded relationship object
                if ($item->product->relationLoaded('category')) {
                     $cat = $item->product->getRelation('category');
                     if ($cat && $cat->type === 'roastery') {
                         $isRoastery = true;
                     }
                } elseif (is_string($item->product->category) && $item->product->category == 'roast_bean') { // Legacy string check safely
                     $isRoastery = true;
                }
            }

            if ($isRoastery) {
                $totalRoastery += $item->subtotal;
            } else {
                $totalCafe += $item->subtotal;
            }
        }

        // 2. Create Journal Header
        $description = 'Penjualan POS: ' . $transaction->invoice_number;
        if ($transaction->is_complimentary) $description .= ' (Complimentary)';

        $journal = \App\Models\Journal::create([
            'ref_number' => $transaction->invoice_number,
            'transaction_date' => $transaction->created_at, // Use transaction time
            'description' => $description,
            'total_debit' => $transaction->total_amount, 
            'total_credit' => $transaction->total_amount,
        ]);
        
        $details = [];

        if ($transaction->is_complimentary) {
            // COMPLIMENTARY (Expense Promotion)
            if ($totalCogs > 0) {
                $promoAcc = ChartOfAccount::where('code', '5-102')->value('id'); // Beban Promosi
                $invAcc = 1; // Persediaan (Hardcoded in PosController as 1)

                if ($promoAcc) {
                    $details[] = ['journal_id' => $journal->id, 'account_id' => $promoAcc, 'debit' => $totalCogs, 'credit' => 0];
                    $details[] = ['journal_id' => $journal->id, 'account_id' => $invAcc, 'debit' => 0, 'credit' => $totalCogs];
                }
            }
        } else {
             // NORMAL SALE
             
             // A. DEBITS (Payments & Discounts)
             // 1. Payments
             foreach($transaction->payments as $pay) {
                $accId = 2; // Default Kas
                if ($pay->payment_method === 'Credit') {
                    $accId = ChartOfAccount::where('code', '1-103')->value('id') 
                          ?? ChartOfAccount::where('name', 'like', '%Piutang%')->value('id') 
                          ?? 2; 
                }
                
                $details[] = ['journal_id' => $journal->id, 'account_id' => $accId, 'debit' => $pay->amount, 'credit' => 0];
             }

             // 2. Discounts
             if ($transaction->discount_amount > 0) {
                 $discAcc = ChartOfAccount::where('code', '4-102')->value('id');
                 if ($discAcc) {
                     $details[] = ['journal_id' => $journal->id, 'account_id' => $discAcc, 'debit' => $transaction->discount_amount, 'credit' => 0];
                 }
             }

             // B. CREDITS (Revenue, Service, Tax)
             // 3. Revenue
             if ($totalCafe > 0) {
                 $details[] = ['journal_id' => $journal->id, 'account_id' => 4, 'debit' => 0, 'credit' => $totalCafe];
             }
             if ($totalRoastery > 0) {
                 $details[] = ['journal_id' => $journal->id, 'account_id' => 5, 'debit' => 0, 'credit' => $totalRoastery];
             }

             // 4. Service Charge
             if ($transaction->service_charge_amount > 0) {
                 $svcAcc = ChartOfAccount::where('name', 'like', '%Service%')->value('id') ?? 5;
                 if ($svcAcc) {
                     $details[] = ['journal_id' => $journal->id, 'account_id' => $svcAcc, 'debit' => 0, 'credit' => $transaction->service_charge_amount];
                 }
             }

             // 5. Tax
             if ($transaction->tax_amount > 0) {
                 $taxAcc = ChartOfAccount::where('code', '2-102')->value('id');
                 if ($taxAcc) {
                     $details[] = ['journal_id' => $journal->id, 'account_id' => $taxAcc, 'debit' => 0, 'credit' => $transaction->tax_amount];
                 }
             }

             // 6. COGS
             if ($totalCogs > 0) {
                 $cogsAcc = ChartOfAccount::where('code', '5-101')->value('id');
                 $invAcc = 1; 
                 if ($cogsAcc) {
                     $details[] = ['journal_id' => $journal->id, 'account_id' => $cogsAcc, 'debit' => $totalCogs, 'credit' => 0];
                     $details[] = ['journal_id' => $journal->id, 'account_id' => $invAcc, 'debit' => 0, 'credit' => $totalCogs];
                 }
             }
        }
        
        // Insert Details
        foreach ($details as $det) {
            \App\Models\JournalDetail::create($det);
        }
        
        $realDebit = collect($details)->sum('debit');
        $realCredit = collect($details)->sum('credit');
        $journal->update(['total_debit' => $realDebit, 'total_credit' => $realCredit]);

        return $journal;
    }
}
