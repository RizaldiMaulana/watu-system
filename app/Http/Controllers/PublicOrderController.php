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

                    // Validasi & Ambil Options jika ada
                    $options = null;
                    if ($request->has('product_options') && isset($request->product_options[$product->id])) {
                        $options = $request->product_options[$product->id]; // Saving as array, Model will cast to JSON
                    }

                    $transactionItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                        'options' => $options
                    ];
                }
            }

            // Tax Calculation Logic
            $subtotalCalc = $totalAmount;
            
            // Tax Logic (Multi-Tax)
            $activeTaxes = \App\Models\Tax::where('is_active', true)->orderBy('sort_order')->get();

            // 1. Taxable Base (No discount for now in public order or handle if needed later)
            $taxableAmount = $subtotalCalc; // max(0, $subtotalCalc - $discountAmount);

             // 2. Service Charge Calculation
            $serviceChargeAmount = 0;
            $serviceChargeRate = 0;
             foreach ($activeTaxes as $tax) {
                if ($tax->type === 'service_charge') {
                    $serviceChargeAmount += $taxableAmount * ($tax->rate / 100);
                    $serviceChargeRate += $tax->rate;
                }
            }

            // 3. PB1 / Tax Calculation
            $taxBase = $taxableAmount + $serviceChargeAmount;
            $taxAmount = 0;
            $taxRate = 0;
            foreach ($activeTaxes as $tax) {
                if ($tax->type === 'tax') {
                    $taxAmount += $taxBase * ($tax->rate / 100);
                    $taxRate += $tax->rate;
                }
            }
            
            $grandTotal = $taxBase + $taxAmount;

            // Simpan Transaksi
            $trx = Transaction::create([
                'invoice_number' => 'WEB-' . time(),
                'customer_name' => $request->customer_name . ' (' . $request->whatsapp . ')',
                'type' => 'Web-Order',
                'subtotal_amount' => $subtotalCalc,
                'service_charge_amount' => $serviceChargeAmount,
                'tax_rate' => $taxRate, // Simpan rate total utk info
                'tax_amount' => $taxAmount,
                'total_amount' => $grandTotal,
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

            // NOTIFICATION TRIGGER
            // Check Item Categories
            $hasCafeItems = false;
            $hasRoasteryItems = false;

            foreach ($itemsToOrder as $productId => $qty) {
                $p = Product::find($productId);
                if ($p) {
                   if ($p->category == 'roast_bean') {
                        $hasRoasteryItems = true;
                   } else {
                        $hasCafeItems = true;
                   }
                }
            }
            
            // To Device Only
            // Barista => Cafe Orders
            if ($hasCafeItems) {
                $baristas = \App\Models\User::where('role', 'barista')->get();
                foreach ($baristas as $u) $u->notify(new \App\Notifications\NewWebOrderNotification($trx));
            }

            // Roaster => Roastery Orders
            if ($hasRoasteryItems) {
                $roasters = \App\Models\User::where('role', 'roaster')->get();
                foreach ($roasters as $u) $u->notify(new \App\Notifications\NewWebOrderNotification($trx));
            }


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
                'total_debit' => $grandTotal,
                'total_credit' => $grandTotal,
            ]);

            // 3. Create Journal Details
            // DEBIT: Kas Besar (ID 2) - Asumsi uang masuk/tagihan
            \App\Models\JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => 2, // Kas Besar
                'debit' => $grandTotal,
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

            // CREDIT: Service Charge Payable
            if ($serviceChargeAmount > 0) {
                $svcAcc = DB::table('chart_of_accounts')->where('name', 'like', '%Service%')->value('id');
                if (!$svcAcc) $svcAcc = 5; 
                \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => $svcAcc, 
                    'debit' => 0,
                    'credit' => $serviceChargeAmount
                ]);
            }

             // CREDIT: Tax Payable
            if ($taxAmount > 0) {
                 \App\Models\JournalDetail::create([
                    'journal_id' => $journal->id,
                    'account_id' => DB::table('chart_of_accounts')->where('code', '2-102')->value('id'), 
                    'debit' => 0,
                    'credit' => $taxAmount
                ]);
            }
            // --- END AUTOMATED ACCOUNTING ---

            foreach ($transactionItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $trx->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'options' => $item['options'] ?? null
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
            'phone' => 'required',
            'date' => 'required',
            'time' => 'required',
            'pax' => 'required|numeric'
        ]);

        // Simpan Reservation pakai Eloquent biar dapet ID
        $reservation = \App\Models\Reservation::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'booking_date' => $request->date,
            'booking_time' => $request->time,
            'pax' => $request->pax,
            'status' => 'Pending'
        ]);

        // NOTIFICATION TRIGGER
        // To Device (Barista Only)
        $staff = \App\Models\User::where('role', 'barista')->get();
        foreach ($staff as $u) {
            $u->notify(new \App\Notifications\NewReservationNotification($reservation));
        }

        // Redirect ke Halaman Pre-Order Menu
        return redirect()->route('public.reservation.pre-order', $reservation->id)
                         ->with('success', 'Reservasi tercatat! Silakan pilih menu jika ingin Pre-Order.');
    }

    public function preOrder($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        $transaction = $reservation->transaction; // Cek apa udah ada PO

        // Ambil hanya produk CAFE (bukan beans) untuk Pre-order
        $products = Product::where('is_available', true)
                           ->whereHas('category', function($q) {
                               $q->where('type', 'cafe');
                           })
                           ->get()
                           ->groupBy('category'); // Fallback to category string column for grouping keys

        return view('public.reservation.pre-order', compact('reservation', 'products', 'transaction'));
    }

    public function storePreOrder(Request $request, $id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);

        if ($request->has('notes')) {
            $reservation->update(['special_note' => $request->notes]);
        }
        
        // Items Processing
        if ($request->has('quantities')) {
             $itemsToOrder = array_filter($request->quantities, function($qty) {
                return $qty > 0;
            });

            if (!empty($itemsToOrder)) {
                 try {
                     DB::beginTransaction();
                     
                     // 1. Hitung Total
                     $totalAmount = 0;
                     $transactionItems = [];
                     
                     foreach ($itemsToOrder as $productId => $qty) {
                        $product = Product::find($productId);
                        if ($product) {
                            $subtotal = $product->price * $qty;
                            $totalAmount += $subtotal;

                            // Validasi & Ambil Options jika ada
                            $options = null;
                            if ($request->has('product_options') && isset($request->product_options[$product->id])) {
                                $options = $request->product_options[$product->id]; // Saving as array, Model will cast to JSON
                            }

                            $transactionItems[] = [
                                'product_id' => $product->id,
                                'quantity' => $qty,
                                'price' => $product->price,
                                'subtotal' => $subtotal,
                                'options' => $options
                            ];
                        }
                    }

                    // 2. Create Transaction linked to Reservation
                    $trx = Transaction::create([
                        'invoice_number' => 'RES-' . time(),
                        'customer_name' => $reservation->name . ' (Reserved)',
                        'type' => 'Pre-Order',
                        'total_amount' => $totalAmount,
                        'payment_status' => 'Unpaid',
                        'payment_method' => 'Cash/Later',
                        'reservation_id' => $reservation->id 
                    ]);

                    // 3. Create Transaction Items
                    foreach ($transactionItems as $item) {
                        TransactionItem::create([
                            'transaction_id' => $trx->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'subtotal' => $item['subtotal'],
                            'options' => $item['options'] ?? null
                        ]);
                    }
                    
                    DB::commit();
                    return redirect()->route('public.invoice', $trx->uuid)->with('success', 'Reservasi & Pre-Order Berhasil!');

                 } catch (\Exception $e) {
                     DB::rollBack();
                     return redirect()->back()->with('error', 'Gagal Pre-order: ' . $e->getMessage());
                 }
            }
        }
        
        // Kalau cuma update notes tanpa order menu, redirect ke success message simple atau homepage
        return redirect()->route('home')->with('success', 'Reservasi & Notes Berhasil Disimpan!');
    }
}