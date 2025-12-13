<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Purchase;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Journal;
use App\Models\JournalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodsReceiptController extends Controller
{
    // List Pending POs (for Barista/Roaster/Manager)
    public function index()
    {
        // 1. Pending (Waiting for Staff to input receipt)
        $pendingPurchases = Purchase::where('status', 'pending')->with('supplier')->latest()->get();
        // 2. Received (Waiting for Manager Validation)
        $receivedPurchases = Purchase::where('status', 'received')->with(['supplier', 'items.product', 'items.ingredient'])->latest()->get();
        // 3. Verified (Completed/History)
        $historyPurchases = Purchase::where('status', 'verified')->with(['supplier', 'items.product', 'items.ingredient'])->latest()->limit(50)->get();
        
        return view('goods_receipt.index', compact('pendingPurchases', 'receivedPurchases', 'historyPurchases'));
    }

    // Show Form to Receive Goods (Upload Invoice)
    public function create(Purchase $purchase)
    {
        if ($purchase->status !== 'pending') {
            return redirect()->route('goods-receipt.index')->with('error', 'Purchase Order ini sudah diproses.');
        }
        $purchase->load(['items.ingredient', 'items.product', 'supplier']);
        return view('goods_receipt.create', compact('purchase'));
    }

    // Process Receipt (Upload File -> Update Stock -> Create Journal)
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB
        ]);

        try {
            DB::transaction(function () use ($request, $purchase) {
                
                // 1. Upload File
                $path = $request->file('proof_file')->store('invoices', 'public');

                // 2. Update Status & File
                $purchase->update([
                    'status' => 'received',
                    'proof_file' => $path,
                ]);

                // NOTIFICATION TRIGGER
                $managers = \App\Models\User::whereIn('role', ['manager', 'owner'])->get();
                foreach ($managers as $u) {
                    $u->notify(new \App\Notifications\GoodsReceiptNotification($purchase));
                }

                // 3. Update Stock & Calculate Average Cost (Mirroring PurchaseController Logic)
                foreach ($purchase->items as $item) {
                     $ingredient = $item->ingredient;
                     $product = $item->product; 
                     $targetModel = $ingredient ?? $product;

                     if ($targetModel) {
                        $oldStock = $targetModel->stock;
                        $oldCost = $targetModel->cost_price;
                        $newQty = $item->quantity;
                        $newPrice = $item->price;

                        // Weighted Average Cost Logic
                        $totalOldValue = $oldStock * $oldCost;
                        $totalNewValue = $newQty * $newPrice;
                        $totalQty = $oldStock + $newQty;

                        $newAvgCost = ($totalQty > 0) ? ($totalOldValue + $totalNewValue) / $totalQty : $newPrice;

                        $targetModel->cost_price = $newAvgCost;
                        $targetModel->stock = $totalQty;
                        $targetModel->save();
                     }
                }

                // 4. Create Journal
                $this->createJournal($purchase);

            });

            return redirect()->route('goods-receipt.index')->with('success', 'Barang diterima, Stok diupdate, dan Bukti terupload!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Manager Verification / Signing
    public function verify(Purchase $purchase)
    {
        // Only Manager/Owner can access this route (controlled by middleware in routes)
        // Check if already signed
        if ($purchase->signed_by) {
             return back()->with('info', 'Dokumen ini sudah ditandatangani.');
        }

        $purchase->update([
            'status' => 'verified',
            'signed_by' => auth()->id(),
            'signed_at' => now(),
        ]);
        
        return back()->with('success', 'Penerimaan Barang berhasil divalidasi & ditandatangani oleh ' . auth()->user()->name);
    }

    // --- MANUAL RECEIPT (Input from Physical Invoice) ---
    
    public function createManual()
    {
        $suppliers = \App\Models\Supplier::all();
        $ingredients = \App\Models\Ingredient::all();
        $products = Product::where('is_available', true)->get();

        return view('goods_receipt.create_manual', compact('suppliers', 'ingredients', 'products'));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'transaction_date' => 'required|date',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'items' => 'required|array',
            'payment_method' => 'required',
            'due_date' => 'nullable|required_if:payment_method,credit|date|after_or_equal:transaction_date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 1. Upload File
                $proofPath = $request->file('proof_file')->store('invoices', 'public');

                // 2. Calculate Total
                $totalAmount = 0;
                foreach ($request->items as $item) {
                     $totalAmount += $item['quantity'] * $item['price'];
                }
                
                $paymentStatus = ($request->payment_method == 'credit') ? 'unpaid' : 'paid';
                $dueDate = ($request->payment_method == 'credit') ? $request->due_date : $request->transaction_date;

                // 3. Create Purchase Header
                $purchase = Purchase::create([
                    'invoice_number' => 'INV-MAN-' . time(), // Distinct prefix for manual input
                    'supplier_id' => $request->supplier_id,
                    'transaction_date' => $request->transaction_date,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'status' => 'received', // Auto received
                    'proof_file' => $proofPath,
                    'due_date' => $dueDate,
                    'notes' => $request->notes . ' (Input Manual via Penerimaan Barang)',
                ]);

                // 4. Save Items & Update Stock
                foreach ($request->items as $item) {
                     $ingredient = null;
                     $product = null;
                     $itemName = 'Unknown';
                     $oldStock = 0;
                     $oldCost = 0;

                     if (!empty($item['ingredient_id'])) {
                        $ingredient = Ingredient::find($item['ingredient_id']);
                        $itemName = $ingredient ? $ingredient->name : 'Unknown';
                        $oldStock = $ingredient->stock;
                        $oldCost = $ingredient->cost_price;
                     } elseif (!empty($item['product_id'])) {
                         $product = Product::find($item['product_id']);
                         $itemName = $product ? $product->name : 'Unknown';
                         $oldStock = $product->stock;
                         $oldCost = $product->cost_price;
                     } else {
                        continue;
                     }

                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'ingredient_id' => $item['ingredient_id'] ?? null,
                        'product_id' => $item['product_id'] ?? null,
                        'item_name' => $itemName,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                    ]);

                     // Update Stock & WAC
                     $targetModel = $ingredient ?? $product;
                     if ($targetModel) {
                        $newQty = $item['quantity'];
                        $newPrice = $item['price'];

                        $totalOldValue = $oldStock * $oldCost;
                        $totalNewValue = $newQty * $newPrice;
                        $totalQty = $oldStock + $newQty;

                        $newAvgCost = ($totalQty > 0) ? ($totalOldValue + $totalNewValue) / $totalQty : $newPrice;

                        $targetModel->cost_price = $newAvgCost;
                        $targetModel->stock = $totalQty;
                        $targetModel->save();
                     }
                }

                // 5. Create Journal
                $this->createJournal($purchase);

            });

            return redirect()->route('goods-receipt.index')->with('success', 'Faktur Manual berhasil disimpan! Stok bertambah & Menunggu Validasi Manager.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function createJournal($purchase) {
        $journal = Journal::create([
            'ref_number' => $purchase->invoice_number,
            'transaction_date' => now(), // Date of receipt aka Now
            'description' => 'Penerimaan Barang PO: ' . $purchase->invoice_number,
            'total_debit' => $purchase->total_amount, 
            'total_credit' => $purchase->total_amount,
        ]);
        
        // Debit Persediaan (Asset)
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => 1, 'debit' => $purchase->total_amount, 'credit' => 0]);
        
        // Kredit Kas/Utang depending on payment method
        // Logic: If Credit -> Accounts Payable (3). If Cash -> Cash (2).
        $creditAccount = ($purchase->payment_method == 'cash') ? 2 : 3;
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => $creditAccount, 'debit' => 0, 'credit' => $purchase->total_amount]);
    }
}
