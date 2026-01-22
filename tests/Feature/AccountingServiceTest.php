<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Services\AccountingService;
use Carbon\Carbon;

class AccountingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $accountingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountingService = new AccountingService();
        $this->seedAccounts();
    }

    private function seedAccounts()
    {
        // IDs must match what we use in tests.
        // Assuming ID 1-5 for simplicity.
        ChartOfAccount::create(['id' => 1, 'name' => 'Cash', 'type' => 'asset', 'code' => '101']);
        ChartOfAccount::create(['id' => 2, 'name' => 'Bank', 'type' => 'asset', 'code' => '102']);
        ChartOfAccount::create(['id' => 3, 'name' => 'Capital', 'type' => 'equity', 'code' => '301']);
        ChartOfAccount::create(['id' => 4, 'name' => 'Sales', 'type' => 'revenue', 'code' => '401']);
        ChartOfAccount::create(['id' => 5, 'name' => 'Expense', 'type' => 'expense', 'code' => '501']);
        ChartOfAccount::create(['id' => 6, 'name' => 'Payable', 'type' => 'liability', 'code' => '201']);
    }

    public function test_balance_sheet_calculation()
    {
        $date = Carbon::now()->format('Y-m-d');
        
        // 1. Initial Investment: Debit Bank 1000, Credit Capital 1000
        $j1 = Journal::create(['transaction_date' => $date, 'ref_number' => 'JV001', 'description' => 'Initial Investment', 'total_debit' => 1000, 'total_credit' => 1000]);
        JournalDetail::create(['journal_id' => $j1->id, 'account_id' => 2, 'debit' => 1000, 'credit' => 0]);
        JournalDetail::create(['journal_id' => $j1->id, 'account_id' => 3, 'debit' => 0, 'credit' => 1000]);

        // 2. Buy Asset: Debit Expense 200, Credit Bank 200
        $j2 = Journal::create(['transaction_date' => $date, 'ref_number' => 'JV002', 'description' => 'Buy Asset', 'total_debit' => 200, 'total_credit' => 200]);
        JournalDetail::create(['journal_id' => $j2->id, 'account_id' => 5, 'debit' => 200, 'credit' => 0]);
        JournalDetail::create(['journal_id' => $j2->id, 'account_id' => 2, 'debit' => 0, 'credit' => 200]);

        $data = $this->accountingService->getBalanceSheet($date);

        // Assert Assets: Bank Balance should be 1000 - 200 = 800
        $bank = $data['assets']->firstWhere('id', 2);
        $this->assertEquals(800, $bank->balance);

        // Assert Equity: Capital 1000
        $capital = $data['equity']->firstWhere('id', 3);
        $this->assertEquals(1000, $capital->balance);

        // Assert Net Income (Retained Earnings): Revenue 0 - Expense 200 = -200
        $this->assertEquals(-200, $data['netIncome']);
    }

    public function test_income_statement_calculation()
    {
        $date = Carbon::now()->format('Y-m-d');

        // 1. Sale: Debit Cash 500, Credit Sales 500
        $j1 = Journal::create(['transaction_date' => $date, 'ref_number' => 'JV003', 'description' => 'Sales Transaction', 'total_debit' => 500, 'total_credit' => 500]);
        JournalDetail::create(['journal_id' => $j1->id, 'account_id' => 1, 'debit' => 500, 'credit' => 0]);
        JournalDetail::create(['journal_id' => $j1->id, 'account_id' => 4, 'debit' => 0, 'credit' => 500]);

        // 2. Expense: Debit Expense 100, Credit Cash 100
        $j2 = Journal::create(['transaction_date' => $date, 'ref_number' => 'JV004', 'description' => 'Expense Transaction', 'total_debit' => 100, 'total_credit' => 100]);
        JournalDetail::create(['journal_id' => $j2->id, 'account_id' => 5, 'debit' => 100, 'credit' => 0]);
        JournalDetail::create(['journal_id' => $j2->id, 'account_id' => 1, 'debit' => 0, 'credit' => 100]);

        $data = $this->accountingService->getIncomeStatement($date, $date);

        $this->assertEquals(500, $data['totalRevenue']);
        $this->assertEquals(100, $data['totalExpense']);
        $this->assertEquals(400, $data['netIncome']);
    }
}
