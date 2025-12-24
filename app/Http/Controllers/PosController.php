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
    public function index(Request $request)
    {
        $products = Product::where('is_available', true)->get();
        $categories = \App\Models\Category::orderBy('sort_order')->get();
        $customers = \App\Models\Customer::orderBy('name')->get(); // NEW for AR POS
        
        // Data Master Integration
        $promotions = \App\Models\Promotion::where('is_active', true)->get();
        // $taxRate = \App\Models\Setting::where('key', 'tax_rate')->value('value') ?? 10;
        $taxes = \App\Models\Tax::where('is_active', true)->orderBy('sort_order')->get();

        $loadedOrder = null;
        if ($request->order_id) {
            $loadedOrder = Transaction::where('uuid', $request->order_id)
                ->where('payment_status', 'Unpaid') // Security: Only allow Unpaid orders
                ->with('items.product')
                ->first();
        }

        return view('pos.pos', compact('products', 'categories', 'promotions', 'taxes', 'loadedOrder', 'customers'));
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
            $subtotalCalc = 0;
            $itemsToProcess = [];

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                $price = $product->price; // Ambil harga dari DB!
                $qty = $item['qty'];
                $lineTotal = $price * $qty;
                
                $subtotalCalc += $lineTotal;
                
                $itemsToProcess[] = [
                    'product_model' => $product, 
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $lineTotal
                ];
            }

            // Calculation Logic
            // Calculation Logic
            $discountAmount = $request->discount_amount ?? 0; // Nominal
            $taxEnabled = $request->boolean('tax_enabled', true);
            $isComplimentary = $request->boolean('is_complimentary', false);
            
            // Tax Logic (Multi-Tax)
            // Fetch latest tax rates to ensure accuracy
            $activeTaxes = \App\Models\Tax::where('is_active', true)->orderBy('sort_order')->get();

            // 1. Taxable Base
            $taxableAmount = max(0, $subtotalCalc - $discountAmount);

            // 2. Service Charge Calculation (Type: service_charge) -> Applied on Taxable Base
            $serviceChargeAmount = 0;
            $serviceChargeRate = 0;

            if ($taxEnabled) {
                foreach ($activeTaxes as $tax) {
                    if ($tax->type === 'service_charge') {
                        $serviceChargeAmount += $taxableAmount * ($tax->rate / 100);
                        $serviceChargeRate += $tax->rate;
                    }
                }
            }

            // 3. PB1 / Tax Calculation (Type: tax) -> Applied on (Taxable Base + Service Charge)
            $taxBase = $taxableAmount + $serviceChargeAmount;
            $taxAmount = 0;
            $taxRate = 0;

            if ($taxEnabled) {
                 foreach ($activeTaxes as $tax) {
                    if ($tax->type === 'tax') {
                        $taxAmount += $taxBase * ($tax->rate / 100);
                        $taxRate += $tax->rate;
                    }
                }
            }
            
            // Final Total
            $grandTotal = $taxBase + $taxAmount;

            if ($isComplimentary) {
                $grandTotal = 0;
                $taxAmount = 0; // No tax on free items usually, or company pays it. Assuming 0 for now.
                $discountAmount = 0; // Reset discount if free
            }
            
            DB::beginTransaction();

            $transaction = null;

            // CHECK IF UPDATING EXISTING TRANSACTION (e.g., Online Order)
            if ($request->transaction_uuid) {
                $transaction = Transaction::where('uuid', $request->transaction_uuid)->firstOrFail();
                $oldInvoice = $transaction->invoice_number;
                
                // 1. Clean up Old Data (Items & Journal)
                $transaction->items()->delete(); // Remove old items (we will re-create from Cart)
                
                // Delete Old Journal (if any) to prevent double counting
                \App\Models\Journal::where('ref_number', $oldInvoice)->delete();

                // 2. Update Header
                $transaction->update([
                    'invoice_number' => $invoice, // Update to INV format
                    'customer_name' => $request->customer_name ?? $transaction->customer_name,
                    // Keep type as Web-Order or change to Dine-in? Keeping it provides lineage.
                    // 'type' => 'Dine-in', 
                    'subtotal_amount' => $subtotalCalc,
                    'discount_amount' => $discountAmount,
                    'discount_reason' => $request->discount_reason,
                    'tax_rate' => $taxEnabled ? $taxRate : 0,
                    'tax_amount' => $taxAmount,
                    'service_charge_amount' => $serviceChargeAmount,
                    'total_amount' => $grandTotal, 
                    'is_complimentary' => $isComplimentary,
                    'payment_status' => 'Paid',
                    'payment_method' => $request->payment_method ?? 'Split',
                    'notes' => $request->notes
                ]);

            } else {
                // Determine Payment Status & AR Fields
                $paymentStatus = 'Paid';
                $dueDate = null;
                $paymentMethod = $request->payment_method ?? 'Split';

                if ($paymentMethod === 'Credit') {
                    $paymentStatus = 'Unpaid';
                    $term = $request->payment_term; // e.g. net30
                    $days = (int) filter_var($term ?: '30', FILTER_SANITIZE_NUMBER_INT) ?: 30; // Default 30
                    $dueDate = \Carbon\Carbon::now()->addDays($days);
                }

                // CREATE NEW TRANSACTION
                $transaction = Transaction::create([
                    'invoice_number' => $invoice,
                    'customer_id' => $request->customer_id, // NEW: Link to Customer
                    'customer_name' => $request->customer_name ?? 'Guest',
                    'type' => 'Dine-in',
                    'subtotal_amount' => $subtotalCalc,
                    'discount_amount' => $discountAmount,
                    'discount_reason' => $request->discount_reason,
                    'tax_rate' => $taxEnabled ? $taxRate : 0,
                    'tax_amount' => $taxAmount,
                    'service_charge_amount' => $serviceChargeAmount,
                    'total_amount' => $grandTotal, 
                    'is_complimentary' => $isComplimentary,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $paymentMethod,
                    'payment_term' => $request->payment_term,
                    'due_date' => $dueDate,
                    'notes' => $request->notes
                ]);
            }

            // 2.5 Simpan Detail Pembayaran (Split Payment)
            $payments = $request->payments ?? []; // Expecting JSON or array: [{method: 'Cash', amount: 50000}, ...]
            // Fallback for legacy single payment request
            if (empty($payments) && $request->payment_method) {
                $payments = [['method' => $request->payment_method, 'amount' => $grandTotal]];
            } elseif(is_string($payments)) {
                $payments = json_decode($payments, true);
            }

            foreach($payments as $pay) {
                \App\Models\TransactionPayment::create([
                    'transaction_id' => $transaction->id,
                    'payment_method' => $pay['method'],
                    'amount' => $pay['amount'],
                    'reference_no' => $pay['reference'] ?? null
                ]);
            }

            // 3. Simpan Item & Kurangi Stok
            $totalCogs = 0; 
            $totalCafe = 0;
            $totalRoastery = 0;

            foreach ($itemsToProcess as $item) {
                $product = $item['product_model'];
                $cogsPerUnit = 0;

                // LOGIKA PENGURANGAN STOK & HITUNG HPP
                if ($product && $product->recipes->count() > 0) {
                    foreach ($product->recipes as $recipe) {
                        $recipe->ingredient->decrement('stock', $recipe->amount_needed * $item['quantity']);
                        $cogsPerUnit += $recipe->ingredient->cost_price * $recipe->amount_needed;
                    }
                } else {
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
                    'cost_price' => $cogsPerUnit, 
                    'subtotal' => $item['subtotal'],
                ]);

                // NOTIFICATION: Low Stock Check
                // Threshold Hardcoded to 5 for now.
                if ($product && $product->stock <= 5) {
                    // Check if already notified recently? For now just fire. 
                    // To prevent spam, we might want a cache check but simpler is better for "Zero Config".
                    // Let's rely on Manager clearing it.
                    $managers = \App\Models\User::whereIn('role', ['manager', 'owner'])->get();
                    foreach ($managers as $u) {
                        try {
                            $u->notify(new \App\Notifications\LowStockNotification($product));
                        } catch (\Exception $e) {} // Don't break POS if notif fails
                    }
                }

                // Split Revenue Calculation (Gross)
                $isRoastery = false;
                if ($product->category_id) {
                     $cat = \App\Models\Category::find($product->category_id);
                     if ($cat && $cat->type === 'roastery') $isRoastery = true;
                } elseif ($product->category == 'roast_bean') {
                     $isRoastery = true;
                }

                if ($isRoastery) $totalRoastery += $item['subtotal'];
                else $totalCafe += $item['subtotal'];
            }

            // 4. JURNAL OTOMATIS
            $journalId = DB::table('journals')->insertGetId([
                'ref_number' => $invoice, 
                'transaction_date' => now(),
                'description' => 'Penjualan POS: ' . $invoice . ($isComplimentary ? ' (Complimentary)' : ''),
                'total_debit' => $grandTotal,
                'total_credit' => $grandTotal,
                'created_at' => now(), 'updated_at' => now(),
            ]);

            if ($isComplimentary) {
                // CASE: COMPLIMENTARY (Expense Promotion)
                if ($totalCogs > 0) {
                    $promoAcc = DB::table('chart_of_accounts')->where('code', '5-102')->value('id'); // Beban Promosi
                    $invAcc = 1; // Persediaan

                    if ($promoAcc) {
                        DB::table('journal_details')->insert([
                            ['journal_id' => $journalId, 'account_id' => $promoAcc, 'debit' => $totalCogs, 'credit' => 0, 'created_at' => now(), 'updated_at' => now()],
                            ['journal_id' => $journalId, 'account_id' => $invAcc, 'debit' => 0, 'credit' => $totalCogs, 'created_at' => now(), 'updated_at' => now()]
                        ]);
                    }
                }

            } else {
                // CASE: NORMAL SALE (Split Revenue & Payment)
                
                // 1. Debit Cash/Bank (Iterate Payments)
                // Map Payment Methods to Accounts:
                // Cash -> 2 (Kas)
                // QRIS, Transfer, Debit -> 3? (Bank) - Assuming 3 or fallback to 2
                // We will default to 2 for now but try to differentiate if needed.
                // Assuming 'Kas' ID=2 is generic Cash/Bank
                
                foreach($payments as $pay) {
                    $accId = 2; // Default Kas
                    
                    if ($pay['method'] === 'Credit') {
                        $accId = DB::table('chart_of_accounts')->where('code', '1-103')->value('id');
                        if (!$accId) $accId = DB::table('chart_of_accounts')->where('name', 'like', '%Piutang%')->value('id');
                        if (!$accId) $accId = 2; // Fallback to Cash (Safe) if AR still missing, preventing Crash
                    }

                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId,
                        'account_id' => $accId,
                        'debit' => $pay['amount'], 'credit' => 0,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }

                // 2. Debit Potongan Penjualan (Jika ada diskon)
                if ($discountAmount > 0) {
                    $discAcc = DB::table('chart_of_accounts')->where('code', '4-102')->value('id');
                    if ($discAcc) {
                        DB::table('journal_details')->insert([
                            'journal_id' => $journalId,
                            'account_id' => $discAcc,
                            'debit' => $discountAmount, 'credit' => 0,
                            'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }

                // 3. Kredit Pendapatan (Gross)
                if ($totalCafe > 0) {
                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId, 'account_id' => 4, // Pendapatan Cafe
                        'debit' => 0, 'credit' => $totalCafe, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
                if ($totalRoastery > 0) {
                    DB::table('journal_details')->insert([
                        'journal_id' => $journalId, 'account_id' => 5, // Pendapatan Roastery
                        'debit' => 0, 'credit' => $totalRoastery, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }

                // 3.5. Kredit Service Charge Payable
                if ($serviceChargeAmount > 0) {
                     // Need a Service Charge Account ID.
                     // Mapping: 4-xxx (Revenue) or 2-xxx (Liability/Payable).
                     // Ideally we have a dedicated account.
                     // Let's check for 'Service Charge' account or fallback to 'Pendapatan Lain-lain' (4-200) or similar.
                    
                    // For now, we will try to find 'Service Charge' account, else 'Pendapatan Service'
                    $svcAcc = DB::table('chart_of_accounts')->where('name', 'like', '%Service%')->value('id');
                    if (!$svcAcc) $svcAcc = 5; // Fallback to Roastery Income temporarily or maybe ID 6 (Other Income)
                    
                    if ($svcAcc) {
                         DB::table('journal_details')->insert([
                            'journal_id' => $journalId, 'account_id' => $svcAcc,
                            'debit' => 0, 'credit' => $serviceChargeAmount, 'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }

                // 4. Kredit Hutang Pajak (PB1)
                if ($taxAmount > 0) {
                    $taxAcc = DB::table('chart_of_accounts')->where('code', '2-102')->value('id');
                    if ($taxAcc) {
                        DB::table('journal_details')->insert([
                            'journal_id' => $journalId, 'account_id' => $taxAcc,
                            'debit' => 0, 'credit' => $taxAmount, 'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }

                // 5. Jurnal COGS (Debit HPP, Kredit Persediaan)
                if ($totalCogs > 0) {
                    $cogsAccountId = DB::table('chart_of_accounts')->where('code', '5-101')->value('id');
                    if ($cogsAccountId) {
                        DB::table('journal_details')->insert([
                            ['journal_id' => $journalId, 'account_id' => $cogsAccountId, 'debit' => $totalCogs, 'credit' => 0, 'created_at' => now(), 'updated_at' => now()],
                            ['journal_id' => $journalId, 'account_id' => 1, 'debit' => 0, 'credit' => $totalCogs, 'created_at' => now(), 'updated_at' => now()]
                        ]);
                    }
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