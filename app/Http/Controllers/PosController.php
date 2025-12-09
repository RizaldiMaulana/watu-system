<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator; // Tambahkan Validator

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->get();
        
        $categories = $products->pluck('category')->unique();

        return view('pos.pos', compact('products', 'categories'));
    }

    public function print($uuid)
    {
        $transaction = Transaction::where('uuid', $uuid)->with('items.product')->firstOrFail();
        return view('pos.print', compact('transaction'));
    }

    public function store(Request $request)
    {
        // Gunakan Validator manual agar bisa return JSON jika gagal
        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric',
            'payment_method' => 'required',
            'cart' => 'required', 
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        // Decode JSON cart dari frontend
        $cart = json_decode($request->cart, true);
        
        // Validasi jika cart kosong atau gagal decode
        if (!$cart || !is_array($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang belanja kosong atau format salah.'], 400);
        }

        $invoice = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        try {
            // 1. Validasi & Hitung Total di Server (SECURITY AUDIT)
            $cartItems = [];
            $calculatedTotal = 0;
            $itemsToProcess = [];

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                $price = $product->price; // Ambil harga dari DB! Jangan dari Request
                $qty = $item['qty'];
                $subtotal = $price * $qty;
                
                $calculatedTotal += $subtotal;
                
                $itemsToProcess[] = [
                    'product_model' => $product, // Simpan model untuk dipakai nanti
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal
                ];
            }

            // Bisa tambahkan validasi selisih (misal toleransi pembulatan) jika frontend ada diskon
            // Tapi untuk standar keamanan ketat, kita pakai harga server.
            
            DB::beginTransaction();

            // 2. Simpan Transaksi Header
            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'customer_name' => $request->customer_name ?? 'Guest',
                'type' => 'Dine-in',
                'total_amount' => $calculatedTotal, // Gunakan Total yang dihitung server
                'payment_status' => 'Paid',
                'payment_method' => $request->payment_method,
            ]);

            // 3. Simpan Item & Kurangi Stok
            $totalCogs = 0; // Total HPP untuk Jurnal

            foreach ($itemsToProcess as $item) {
                $product = $item['product_model'];
                $cogsPerUnit = 0;

                // LOGIKA PENGURANGAN STOK & HITUNG HPP
                if ($product && $product->recipes->count() > 0) {
                    // Punya Resep: Kurangi Bahan Baku & Hitung HPP dari Bahan
                    foreach ($product->recipes as $recipe) {
                        $recipe->ingredient->decrement('stock', $recipe->amount_needed * $item['quantity']);
                        
                        // HPP = Cost Bahan * Jumlah yg dipakai
                        $cogsPerUnit += $recipe->ingredient->cost_price * $recipe->amount_needed;
                    }
                } else {
                    // Tidak Punya Resep: Kurangi Stok Produk Langsung & Pakai HPP Produk
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                        $cogsPerUnit = $product->cost_price;
                    }
                }

                $totalCogsItem = $cogsPerUnit * $item['quantity'];
                $totalCogs += $totalCogsItem;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'cost_price' => $cogsPerUnit, // Store HPP saat transaksi
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // 4. JURNAL OTOMATIS
            $journalId = DB::table('journals')->insertGetId([
                'ref_number' => $invoice, 
                'transaction_date' => now(),
                'description' => 'Penjualan POS: ' . $invoice,
                'total_debit' => $calculatedTotal,
                'total_credit' => $calculatedTotal,
                'created_at' => now(), 
                'updated_at' => now(),
            ]);

            // Debit Kas (ID 2)
            DB::table('journal_details')->insert([
                'journal_id' => $journalId,
                'account_id' => 2, 
                'debit' => $calculatedTotal, 'credit' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // HITUNG SPLIT PENDAPATAN
            $totalCafe = 0;
            $totalRoastery = 0;

            foreach ($itemsToProcess as $item) {
                // Ambil Produk untuk cek kategori
                $prod = $item['product_model'];
                if ($prod->category == 'roast_bean') {
                    $totalRoastery += $item['subtotal'];
                } else {
                    $totalCafe += $item['subtotal'];
                }
            }

            // Kredit Pendapatan Cafe (ID 4)
            if ($totalCafe > 0) {
                DB::table('journal_details')->insert([
                    'journal_id' => $journalId,
                    'account_id' => 4, // Penjualan Cafe
                    'debit' => 0, 'credit' => $totalCafe,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            // Kredit Pendapatan Roastery (ID 5)
            if ($totalRoastery > 0) {
                DB::table('journal_details')->insert([
                    'journal_id' => $journalId,
                    'account_id' => 5, // Penjualan Roastery (Baru)
                    'debit' => 0, 'credit' => $totalRoastery,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            // 5. JURNAL HPP (COGS)
            if ($totalCogs > 0) {
                // Debit Beban Pokok Penjualan (5-101)
                $cogsAccountId = DB::table('chart_of_accounts')->where('code', '5-101')->value('id');
                if ($cogsAccountId) {
                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId,
                        'account_id' => $cogsAccountId,
                        'debit' => $totalCogs, 'credit' => 0,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);

                    // Kredit Persediaan (1-101) - Assumed Single Inventory Account for Simplicity
                    // In complex system, this might be split by category asset accounts
                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId,
                        'account_id' => 1, // Persediaan Bahan Baku (Default)
                        'debit' => 0, 'credit' => $totalCogs,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'transaction_id' => $transaction->id,
                'transaction_uuid' => $transaction->uuid,
                'message' => 'Transaksi Berhasil!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}