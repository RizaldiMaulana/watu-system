<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DssController extends Controller
{
    /**
     * Display the DSS Restock Recommendation page.
     */
    public function index()
    {
        // 1. Fetch available products with necessary data
        // specific to Restock: we need stock, cost, price, and sales history.
        $products = Product::where('is_available', true)->get();

        // 2. Prepare Data Structure for Calculation
        $alternatives = [];
        $minStock = $products->min('stock') ?: 1; // Avoid divide by zero if 0, though stock can be 0.
        // Actually for Cost attribute normalization (Min / Value), if Value is 0 handle carefully.
        // Let's handle 0 stock by treating it as 1 or handling logic separately. 
        // Better logic: If stock is 0, it's CRITICAL.
        
        $maxSales = 0;
        $maxMargin = 0;

        // First Pass: Calculate raw values and find Max/Min for normalization
        foreach ($products as $product) {
            // A. Stock (Raw)
            $stock = $product->stock;
            
            // B. Sales Velocity (Last 30 Days)
            // JOIN transaction_items -> transactions
            $salesQty = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transaction_items.product_id', $product->id)
                ->where('transactions.created_at', '>=', Carbon::now()->subDays(30))
                ->whereNull('transactions.voided_at')
                ->sum('transaction_items.quantity');
            
            // C. Margin
            $margin = $product->price - $product->cost_price;

            $alternatives[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'stock' => $stock,
                'sales_30d' => $salesQty,
                'margin' => $margin,
                'score' => 0
            ];

            if ($salesQty > $maxSales) $maxSales = $salesQty;
            if ($margin > $maxMargin) $maxMargin = $margin;
        }

        // Handle edge case if max is 0 to avoid division by zero
        if ($maxSales == 0) $maxSales = 1; 
        if ($maxMargin == 0) $maxMargin = 1;
        
        // Find Absolute Min Stock for Normalization (Cost Attribute)
        // If min stock is 0, we can use 1 (if we add 1 to all stocks) or just use 0.
        // Standard SAW for COST: Min / Value. Division by zero is risk.
        // Adjustment: Use (Min + 1) / (Value + 1) to be safe.
        $minStock = collect($alternatives)->min('stock');

        // 3. Second Pass: Normalize and Calculate Score
        // Weights: Stock (40%), Sales (40%), Margin (20%)
        foreach ($alternatives as &$alt) {
            // Normalize Stock (Cost Attribute: Lower is better/higher priority)
            // If Stock is 0, Priority is Highest (1.0).
            // Formula: Min / Val. 
            // Safe: ($minStock + 1) / ($alt['stock'] + 1)
            $normStock = ($minStock + 1) / ($alt['stock'] + 1);

            // Normalize Sales (Benefit Attribute: Higher is better)
            $normSales = $alt['sales_30d'] / $maxSales;

            // Normalize Margin (Benefit Attribute: Higher is better)
            $normMargin = $alt['margin'] / $maxMargin;

            // SAW Score
            // Weights: Stock=0.4, Sales=0.4, Margin=0.2
            $score = ($normStock * 0.40) + ($normSales * 0.40) + ($normMargin * 0.20);
            
            $alt['norm_stock'] = $normStock;
            $alt['norm_sales'] = $normSales;
            $alt['norm_margin'] = $normMargin;
            $alt['score'] = $score;
        }

        // 4. Rank
        usort($alternatives, function ($a, $b) {
            return $b['score'] <=> $a['score']; // Descending
        });

        // Limit to top 50
        $rankings = array_slice($alternatives, 0, 50);

        return view('dss.restock', compact('rankings'));
    }
}
