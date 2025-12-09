<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {
    public function index()
    {
        $user = Auth::user();

        // 1. Data Operasional (SEMUA BISA LIHAT: Reservasi & Order Web)
        $reservations = DB::table('reservations')
            ->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();

        $webOrders = Transaction::where('type', 'like', 'Web%')
            ->where('payment_status', 'Unpaid')
            ->orderBy('created_at', 'asc')
            ->with('items.product')
            ->get();
            
        $pendingCount = $webOrders->count();

        // 2. Data Keuangan (HANYA MANAGER/OWNER/ADMIN)
        $todaySales = 0;
        $todayCount = 0;
        $todayRevenue = 0;
        $todayOrdersCount = 0;
        $bestSellers = collect([]);
        $criticalStock = collect([]);

        // Cek Role: Jika BUKAN Barista, baru ambil data keuangan
        if ($user->role !== 'barista') {
            $todaySales = Transaction::whereDate('created_at', today())->sum('total_amount');
            $todayCount = Transaction::whereDate('created_at', today())->count();
            
            $todayRevenue = Transaction::whereDate('created_at', now()->toDateString())
                ->where('payment_status', 'Paid')
                ->sum('total_amount');

            $todayOrdersCount = Transaction::whereDate('created_at', now()->toDateString())
                ->where('payment_status', 'Paid')
                ->count();

            $bestSellers = DB::table('transaction_items')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
                ->groupBy('transaction_items.product_id', 'products.name')
                ->orderByRaw('SUM(transaction_items.quantity) DESC')
                ->limit(5)
                ->get();

            $criticalStock = Ingredient::whereColumn('stock', '<=', 'minimum_stock')->get();
        }

        return view('dashboard', compact(
            'reservations', 'webOrders', 'pendingCount',
            'todayRevenue', 'todayOrdersCount', 'todaySales', 'todayCount', 
            'bestSellers', 'criticalStock'
        ));
    }
    
    public function completeWebOrder($id)
    {
        try {
            DB::beginTransaction();

            // 1. Update Status Transaksi jadi Paid
            $trx = Transaction::findOrFail($id);
            $trx->update([
                'payment_status' => 'Paid',
                'payment_method' => 'Transfer/QRIS'
            ]);
            
            // 2. CATAT JURNAL AKUNTANSI OTOMATIS
            // 2. CATAT JURNAL AKUNTANSI OTOMATIS
            
            // Header Jurnal
            $journal = \App\Models\Journal::create([
                'ref_number' => $trx->invoice_number,
                'transaction_date' => now(),
                'description' => 'Penerimaan Web: ' . $trx->customer_name,
                'total_debit' => $trx->total_amount,
                'total_credit' => $trx->total_amount,
            ]);

            // DEBIT: Bank BCA (Anggap ID 2 = Kas Besar / ID 3 bisa disesuaikan, atau buat ID baru misal 10 = Bank)
            // Untuk sementara masuk ke Kas Besar (ID 2) atau Utang (jika invoice).
            // Default ke Kas Besar (ID 2) sesuai PosController, atau jika mau Bank BCA harus buat akun baru.
            // Asumsi pakai ID 2 dulu agar aman dengan data benih.
            \App\Models\JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => 2, // Kas Besar
                'debit' => $trx->total_amount,
                'credit' => 0,
            ]);

            // HITUNG SPLIT REVENUE
            $totalCafe = 0;
            $totalRoastery = 0;

            foreach ($trx->items as $item) {
                if ($item->product->category == 'roast_bean') {
                    $totalRoastery += $item->subtotal;
                } else {
                    $totalCafe += $item->subtotal;
                }
            }

            // KREDIT: Pendapatan Cafe (ID 4)
            if ($totalCafe > 0) {
                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => 4, // Penjualan Cafe
                    'debit' => 0, 
                    'credit' => $totalCafe,
                ]);
            }

            // KREDIT: Pendapatan Roastery (ID 5)
            if ($totalRoastery > 0) {
                 \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => 5, // Penjualan Roastery
                    'debit' => 0, 
                    'credit' => $totalRoastery,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil diverifikasi & Jurnal tercatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}