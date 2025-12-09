<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicOrderController extends Controller
{
    // 1. PROSES PESANAN CAFE (MAKANAN/MINUMAN)
    public function storeCafe(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'whatsapp' => 'required',
            'payment_method' => 'required', // Tambahkan Validasi ini
            'quantities' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            
            $itemsToOrder = array_filter($request->quantities, function($qty) {
                return $qty > 0;
            });

            if (empty($itemsToOrder)) {
                return redirect()->back()->with('error', 'Silakan pilih minimal satu item.');
            }

            $totalAmount = 0;
            $transactionItems = [];

            foreach ($itemsToOrder as $productId => $qty) {
                $product = Product::find($productId);
                if ($product) {
                    $subtotal = $product->price * $qty;
                    $totalAmount += $subtotal;

                    $transactionItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                        'subtotal' => $subtotal
                    ];
                }
            }

            // Simpan Transaksi
            $trx = Transaction::create([
                'invoice_number' => 'WEB-' . time(),
                'customer_name' => $request->customer_name . ' (' . $request->whatsapp . ')',
                'type' => 'Web-Order',
                'total_amount' => $totalAmount,
                'payment_status' => 'Unpaid', // Status awal Unpaid
                'payment_method' => $request->payment_method // Ambil dari Form
            ]);

            // --- AUTOMATED ACCOUNTING ---
            // Create Journal Entry for this Sales Transaction
            // Debit: Kas Besar (ID 2)
            // Credit: Penjualan Cafe (ID 4) or Penjualan Roastery (ID 5)

            // 1. Calculate Revenue Split
            $revenueCafe = 0;
            $revenueRoastery = 0;

            foreach ($itemsToOrder as $productId => $qty) {
                $p = Product::find($productId);
                if ($p) {
                    $lineTotal = $p->price * $qty;
                    if ($p->category == 'roast_bean') {
                        $revenueRoastery += $lineTotal;
                    } else {
                        $revenueCafe += $lineTotal;
                    }
                }
            }

            // 2. Create Journal Header
            $journal = \App\Models\Journal::create([
                'ref_number' => $trx->invoice_number,
                'transaction_date' => now(),
                'description' => 'Sales Order ' . $trx->invoice_number . ' (' . $trx->customer_name . ')',
                'total_debit' => $totalAmount,
                'total_credit' => $totalAmount,
            ]);

            // 3. Create Journal Details
            // DEBIT: Kas Besar (ID 2) - Asumsi uang masuk/tagihan
            \App\Models\JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => 2, // Kas Besar
                'debit' => $totalAmount,
                'credit' => 0
            ]);

            // CREDIT: Revenue Accounts
            if ($revenueCafe > 0) {
                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => 4, // Penjualan Cafe
                    'debit' => 0,
                    'credit' => $revenueCafe
                ]);
            }

            if ($revenueRoastery > 0) {
                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => 5, // Penjualan Roastery
                    'debit' => 0,
                    'credit' => $revenueRoastery
                ]);
            }
            // --- END AUTOMATED ACCOUNTING ---

            foreach ($transactionItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();

            return redirect()->route('public.invoice', $trx->uuid);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function showInvoice($uuid)
    {
        // Ambil data transaksi beserta item produknya berdasarkan UUID (untuk keamanan IDOR)
        $transaction = Transaction::where('uuid', $uuid)->with('items.product')->firstOrFail();

        return view('public.invoice', compact('transaction'));
    }

    // 2. PROSES RESERVASI MEJA
    public function storeReservation(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'time' => 'required',
            'pax' => 'required|numeric'
        ]);

        // Simpan ke database menggunakan Query Builder 
        DB::table('reservations')->insert([
            'name' => $request->name,
            'phone' => '0000', // Nanti bisa ditambah field input HP di form reservasi
            'booking_date' => $request->date,
            'booking_time' => $request->time,
            'pax' => $request->pax,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Reservasi Meja Berhasil Dibuat!');
    }
}