<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\ChartOfAccount;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    public function create(Request $request)
    {
        $suppliers = \App\Models\Supplier::all();
        $ingredients = \App\Models\Ingredient::all();
        $products = Product::where('is_available', true)->get(); // Fetch products

        // Pre-fill logic
        $prefilledItem = null;
        if ($request->has('ingredient_id')) {
            $ing = Ingredient::find($request->ingredient_id);
            if ($ing) {
                $prefilledItem = [
                    'id' => $ing->id,
                    'type' => 'ingredient',
                    'name' => $ing->name,
                    'price' => $ing->cost_price, // Estimated
                    'unit' => $ing->unit,
                ];
            }
        } elseif ($request->has('product_id')) {
            $prod = Product::find($request->product_id);
            if ($prod) {
                $prefilledItem = [
                    'id' => $prod->id,
                    'type' => 'product',
                    'name' => $prod->name,
                    'price' => $prod->cost_price,
                    'unit' => 'Unit',
                ];
            }
        }

        return view('purchases.create', compact('suppliers', 'ingredients', 'products', 'prefilledItem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'transaction_date' => 'required|date',
            'items' => 'required|array',
            'payment_method' => 'required',
            'payment_term' => 'nullable|string', // cash, net7, net30
            'due_date' => 'nullable|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                // 1. Hitung Total
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += $item['quantity'] * $item['price'];
                }

                // Logic Terms & Due Date
                $paymentStatus = ($request->payment_method == 'credit') ? 'unpaid' : 'paid';
                $transactionDate = \Carbon\Carbon::parse($request->transaction_date);
                $dueDate = $transactionDate;

                if ($request->payment_method == 'credit') {
                    if ($request->payment_term == 'net7') {
                        $dueDate = $transactionDate->copy()->addDays(7);
                    } elseif ($request->payment_term == 'net14') {
                        $dueDate = $transactionDate->copy()->addDays(14);
                    } elseif ($request->payment_term == 'net30') {
                        $dueDate = $transactionDate->copy()->addDays(30);
                    } elseif ($request->payment_term == 'net60') {
                        $dueDate = $transactionDate->copy()->addDays(60);
                    } elseif ($request->due_date) {
                         $dueDate = \Carbon\Carbon::parse($request->due_date);
                    }
                }

                // Check if this is a Pre-order (Pending Receipt) or Direct Purchase
                $status = $request->status ?? 'pending'; // Default to pending for proper PO workflow

                // 2. Simpan Header
                $purchase = Purchase::create([
                    'invoice_number' => 'PUR-' . time(),
                    'supplier_id' => $request->supplier_id,
                    'transaction_date' => $request->transaction_date,
                    'total_amount' => $totalAmount,
                    'paid_amount' => ($paymentStatus == 'paid') ? $totalAmount : 0, // Auto-fill if cash
                    'payment_method' => $request->payment_method,
                    'payment_term' => $request->payment_term,
                    'payment_status' => $paymentStatus,
                    'status' => $status, // 'pending' or 'received'
                    'due_date' => $dueDate,
                    'notes' => $request->notes ?? null,
                    'created_by' => auth()->id(),
                ]);

                // 3. Simpan Detail
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
                    
                    // ONLY UPDATE STOCK IF STATUS IS RECEIVED
                    if ($status === 'received') {
                        $targetModel = $ingredient ?? $product;

                        if ($targetModel) {
                            $newQty = $item['quantity'];
                            $newPrice = $item['price'];

                            // Calculate Weighted Average Cost
                            $totalOldValue = $oldStock * $oldCost;
                            $totalNewValue = $newQty * $newPrice;
                            $totalQty = $oldStock + $newQty;

                            $newAvgCost = ($totalQty > 0) ? ($totalOldValue + $totalNewValue) / $totalQty : $newPrice;

                            $targetModel->cost_price = $newAvgCost;
                            $targetModel->stock = $totalQty; 
                            $targetModel->save();
                        }
                    }
                }

                // 4. Buat Jurnal (ONLY IF RECEIVED)
                if ($status === 'received') {
                    $this->createJournal($purchase, $totalAmount, $request->payment_method);
                }
            });

            if ($request->status === 'pending') {
                 return redirect()->route('purchases.index')->with('success', 'Purchase Order berhasil dibuat! Silahkan proses di Menu Penerimaan Barang saat barang tiba.');
            }

            return redirect()->route('purchases.create')->with('success', 'Stok bertambah dan Jurnal tercatat!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function print(Purchase $purchase)
    {
        $purchase->load(['items.ingredient', 'items.product', 'supplier']);
        return view('purchases.print', compact('purchase'));
    }

    private function createJournal($purchase, $total, $method) {
        $journal = Journal::create([
            'ref_number' => $purchase->invoice_number,
            'transaction_date' => $purchase->transaction_date,
            'description' => 'Pembelian Stok: ' . $purchase->invoice_number,
            'total_debit' => $total, 'total_credit' => $total,
        ]);
        
        // Debit Persediaan
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => 1, 'debit' => $total, 'credit' => 0]);
        
        // Kredit Kas/Utang
        $creditAccount = ($method == 'cash') ? 2 : 3;
        JournalDetail::create(['journal_id' => $journal->id, 'account_id' => $creditAccount, 'debit' => 0, 'credit' => $total]);
    }
}