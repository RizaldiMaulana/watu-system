<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixedAssetController extends Controller
{
    public function index()
    {
        $assets = FixedAsset::with(['assetAccount', 'accumulatedAccount', 'expenseAccount'])->latest()->get();
        return view('accounting.assets.index', compact('assets'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::all(); // Should filter by type ideal (Asset for asset, Liability for accum, Expense for expense) but all is fine for now
        $assetAccounts = $accounts->where('type', 'asset');
        $expenseAccounts = $accounts->where('type', 'expense');
        // Accum accounts are technically Contra-Assets (negative asset) or Liability in some implementations. 
        // In Watu, we might use 'asset' with negative balance logic or just pick from asset list.
        
        return view('accounting.assets.create', compact('accounts', 'assetAccounts', 'expenseAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'purchase_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'salvage_value' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
            'fixed_asset_account_id' => 'required|exists:chart_of_accounts,id',
            'accumulated_depreciation_account_id' => 'required|exists:chart_of_accounts,id',
            'depreciation_expense_account_id' => 'required|exists:chart_of_accounts,id',
        ]);

        FixedAsset::create([
            'name' => $request->name,
            'description' => $request->description,
            'purchase_date' => $request->purchase_date,
            'cost' => $request->cost,
            'salvage_value' => $request->salvage_value,
            'useful_life_years' => $request->useful_life_years,
            'depreciation_accumulated' => 0,
            'book_value' => $request->cost, // Initial Book Value = Cost
            'fixed_asset_account_id' => $request->fixed_asset_account_id,
            'accumulated_depreciation_account_id' => $request->accumulated_depreciation_account_id,
            'depreciation_expense_account_id' => $request->depreciation_expense_account_id,
        ]);

        return redirect()->route('accounting.assets.index')->with('success', 'Aset Tetap berhasil ditambahkan. Jangan lupa catat Jurnal Pembelian jika belum.');
    }

    public function depreciate(Request $request)
    {
        $date = $request->input('date', date('Y-m-d')); // The date to post the journal (usually end of month)
        
        // 1. Check if we already ran depreciation for this month? (Optional, skipping for MVP simplicity)
        // 2. Loop all active assets
        $assets = FixedAsset::where('status', 'active')->get();
        $totalDepreciation = 0;
        
        DB::transaction(function () use ($assets, $date, &$totalDepreciation) {
            
            $journalEntries = [];

            foreach ($assets as $asset) {
                if ($asset->book_value <= $asset->salvage_value) continue; // Fully depreciated

                $monthlyAmount = $asset->calculateMonthlyDepreciation();
                
                // Cap at book value - salvage
                $remainingDepreciable = $asset->book_value - $asset->salvage_value;
                if ($monthlyAmount > $remainingDepreciable) {
                    $monthlyAmount = $remainingDepreciable;
                }

                if ($monthlyAmount <= 0) continue;

                // Update Asset
                $asset->depreciation_accumulated += $monthlyAmount;
                $asset->book_value -= $monthlyAmount;
                $asset->save();

                $totalDepreciation += $monthlyAmount;

                // Prepare Journal Entry Data
                // Debit Expense
                $journalEntries[] = [
                    'account_id' => $asset->depreciation_expense_account_id,
                    'debit' => $monthlyAmount,
                    'credit' => 0,
                    'note' => "Depr: {$asset->name}"
                ];

                // Credit Accumulated Depreciation
                $journalEntries[] = [
                    'account_id' => $asset->accumulated_depreciation_account_id,
                    'debit' => 0,
                    'credit' => $monthlyAmount,
                    'note' => "Accum Depr: {$asset->name}"
                ];
            }

            if ($totalDepreciation > 0) {
                // Create ONE specific journal for this batch run
                $journal = Journal::create([
                    'ref_number' => 'DEPR-' . date('Ymd', strtotime($date)),
                    'transaction_date' => $date,
                    'description' => 'Penyusutan Aset Tetap Bulan ' . date('F Y', strtotime($date)),
                    'total_debit' => $totalDepreciation,
                    'total_credit' => $totalDepreciation,
                ]);

                foreach ($journalEntries as $entry) {
                    JournalDetail::create([
                        'journal_id' => $journal->id,
                        'account_id' => $entry['account_id'],
                        'debit' => $entry['debit'],
                        'credit' => $entry['credit'],
                        // 'description' => $entry['note'] // Optional if detail has desc
                    ]);
                }
            }
        });

        if ($totalDepreciation > 0) {
            return back()->with('success', 'Jurnal Penyusutan senilai Rp ' . number_format($totalDepreciation, 0, ',', '.') . ' berhasil dibuat!');
        }

        return back()->with('info', 'Tidak ada aset yang perlu disusutkan untuk saat ini.');
    }
}
