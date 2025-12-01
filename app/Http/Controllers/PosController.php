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

    public function print($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);
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
            DB::beginTransaction();

            // 1. Simpan Transaksi Header
            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'customer_name' => $request->customer_name ?? 'Guest',
                'type' => 'Dine-in',
                'total_amount' => $request->total_amount,
                'payment_status' => 'Paid',
                'payment_method' => $request->payment_method,
            ]);

            // 2. Simpan Item & Kurangi Stok
            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
                
                // LOGIKA PENGURANGAN STOK
                $product = Product::with('recipes.ingredient')->find($item['id']);

                if ($product && $product->recipes->count() > 0) {
                    // Punya Resep: Kurangi Bahan Baku
                    foreach ($product->recipes as $recipe) {
                        $recipe->ingredient->decrement('stock', $recipe->amount_needed * $item['qty']);
                    }
                } else {
                    // Tidak Punya Resep: Kurangi Stok Produk Langsung
                    if ($product) {
                        $product->decrement('stock', $item['qty']);
                    }
                }
            }

            // 3. JURNAL OTOMATIS
            $journalId = DB::table('journals')->insertGetId([
                'ref_number' => $invoice, 
                'transaction_date' => now(),
                'description' => 'Penjualan POS: ' . $invoice,
                'total_debit' => $request->total_amount,
                'total_credit' => $request->total_amount,
                'created_at' => now(), 
                'updated_at' => now(),
            ]);

            // Debit Kas (ID 2)
            DB::table('journal_details')->insert([
                'journal_id' => $journalId,
                'account_id' => 2, 
                'debit' => $request->total_amount, 'credit' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            // Kredit Pendapatan (ID 4)
            DB::table('journal_details')->insert([
                'journal_id' => $journalId,
                'account_id' => 4, 
                'debit' => 0, 'credit' => $request->total_amount,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'transaction_id' => $transaction->id,
                'message' => 'Transaksi Berhasil!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}