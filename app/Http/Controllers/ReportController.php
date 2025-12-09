<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function jurnal(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Ambil Data Jurnal Lengkap (Tabel)
        $query = Journal::with('details.account')->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $journals = $query->paginate(20)->withQueryString();

        // 2. Siapkan Data Grafik (Total Debit per Hari - 7 Hari Terakhir / Filtered Range)
        // Jika ada filter, gunakan range filter. Jika tidak, default 7 hari terakhir.
        $chartQuery = Journal::select(
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('SUM(total_debit) as total')
        );

        if ($startDate && $endDate) {
            $chartQuery->whereBetween('transaction_date', [$startDate, $endDate]);
        } else {
            $chartQuery->where('transaction_date', '>=', now()->subDays(7));
        }
            
        $chartData = $chartQuery->groupBy('date')
        ->orderBy('date')
        ->get();

        // Format data untuk Chart.js
        $labels = $chartData->pluck('date')->map(function($date) {
            return date('d M', strtotime($date));
        });
        $values = $chartData->pluck('total');

        return view('reports.index', compact('journals', 'labels', 'values', 'startDate', 'endDate'));
    }

    public function stock()
    {
        // Ambil semua bahan baku, urutkan stok paling sedikit dulu
        $stocks = Ingredient::orderBy('stock', 'asc')->get();
        
        return view('reports.stock', compact('stocks'));
    }
}