<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function jurnal()
    {
        // 1. Ambil Data Jurnal Lengkap (Tabel)
        $journals = Journal::with('details.account')->latest()->paginate(20);

        // 2. Siapkan Data Grafik (Total Debit per Hari - 7 Hari Terakhir)
        // Asumsi: Debit di jurnal umum seringkali menggambarkan aktivitas transaksi masuk/aset
        $chartData = Journal::select(
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('SUM(total_debit) as total')
        )
        ->where('transaction_date', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Format data untuk Chart.js
        $labels = $chartData->pluck('date')->map(function($date) {
            return date('d M', strtotime($date));
        });
        $values = $chartData->pluck('total');

        return view('reports.index', compact('journals', 'labels', 'values'));
    }

    public function stock()
    {
        // Ambil semua bahan baku, urutkan stok paling sedikit dulu
        $stocks = Ingredient::orderBy('stock', 'asc')->get();
        
        return view('reports.stock', compact('stocks'));
    }
}