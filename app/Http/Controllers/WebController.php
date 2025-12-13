<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        // Fetch Hero Sliders
        $heroSliders = \App\Models\Slider::where('type', 'hero')->where('is_active', true)->orderBy('sort_order')->get();

        // Fetch About Sliders
        $aboutSliders = \App\Models\Slider::where('type', 'about')->where('is_active', true)->orderBy('sort_order')->get();

        // Fetch Top 5 Selling Products
        $topProducts = \App\Models\TransactionItem::select('product_id', \DB::raw('SUM(quantity) as total_sold'))
            ->has('product')
            ->with(['product'])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->pluck('product');

        if ($topProducts->isEmpty()) {
            $topProducts = \App\Models\Product::whereNull('deleted_at')->where('is_available', true)->inRandomOrder()->take(5)->get();
        }

        return view('welcome', compact('heroSliders', 'aboutSliders', 'topProducts'));
    }

    public function order()
    {
        return $this->renderOrderView('cafe');
    }

    public function menu()
    {
        return $this->renderOrderView('cafe');
    }

    public function roastBeans()
    {
        return $this->renderOrderView('beans');
    }

    private function renderOrderView($activeTab)
    {
        // 1. Ambil Menu Cafe (Minuman & Makanan)
        // Ambil semua category tipe cafe, urutkan by sort_order
        // Eager load products yg available
        $cafe_categories = \App\Models\Category::where('type', 'cafe')
                            ->orderBy('sort_order')
                            ->with(['products' => function($query) {
                                $query->where('is_available', true)->orderBy('name');
                            }])
                            ->get();

        // Format data agar sesuai dengan view: [ 'Nama Kategori' => Collection(Products) ]
        $cafe_menu = [];
        foreach($cafe_categories as $cat) {
            if ($cat->products->count() > 0) {
                $cafe_menu[$cat->name] = $cat->products;
            }
        }

        // 2. Ambil Menu Roast Beans
        // 2. Ambil Menu Roast Beans
        // Ambil kategori roastery dan produknya
        $roastery_categories = \App\Models\Category::where('type', 'roastery')
                                ->orderBy('sort_order')
                                ->with(['products' => function($query) {
                                    $query->where('is_available', true)->orderBy('name');
                                }])
                                ->get();
        
        $roast_beans = [];
        foreach($roastery_categories as $cat) {
            if ($cat->products->count() > 0) {
                // Gunakan label custom jika perlu, atau nama kategori langsung
                $roast_beans[$cat->name] = $cat->products;
            }
        }

        // Fallback backward compatibility (jika ada produk roast bean tanpa kategori ID baru)
        if (empty($roast_beans)) {
             $legacy_beans = Product::where('category', 'roast_bean')
                              ->where('is_available', true)
                              ->get();
             if ($legacy_beans->isNotEmpty()) {
                 $roast_beans['Roast Beans'] = $legacy_beans;
             }
        }

        return view('public.order', compact('cafe_menu', 'roast_beans', 'activeTab'));
    }
}