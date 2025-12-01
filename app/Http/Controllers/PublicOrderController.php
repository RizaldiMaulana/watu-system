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

            return redirect()->route('public.invoice', $trx->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function showInvoice($id)
    {
        // Ambil data transaksi beserta item produknya
        $transaction = Transaction::with('items.product')->findOrFail($id);

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