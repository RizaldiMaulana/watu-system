<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        return view('public.home');
    }

    public function order()
    {
        // 1. Ambil Menu Cafe (Minuman & Makanan)
        $cafe_products = Product::whereIn('category', ['coffee', 'non_coffee', 'food', 'snack'])
                                ->where('is_available', true)
                                ->get();

        // 2. Ambil Menu Roast Beans
        $roast_beans = Product::where('category', 'roast_bean')
                              ->where('is_available', true)
                              ->get();

        // Kirim kedua variabel ke view
        return view('public.order', compact('cafe_products', 'roast_beans'));
    }
}