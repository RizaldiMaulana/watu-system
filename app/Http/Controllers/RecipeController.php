<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // 1. Halaman Daftar Produk (Pilih produk mana yang mau diatur resepnya)
    public function index(Request $request)
    {
        $search = $request->search;
        
        $products = Product::when($search, function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->withCount('recipes') // Hitung jumlah bahan baku per produk
            ->latest()
            ->paginate(10);

        return view('recipes.index', compact('products'));
    }

    // 2. Halaman Edit Resep per Produk
    public function edit(Product $product)
    {
        // Ambil semua bahan baku untuk dropdown
        $ingredients = Ingredient::orderBy('name')->get();
        
        // Load resep yang sudah ada
        $product->load('recipes.ingredient');

        return view('recipes.edit', compact('product', 'ingredients'));
    }

    // 3. Simpan Bahan Baku ke Resep
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'amount_needed' => 'required|numeric|min:0.1',
        ]);

        // Cek apakah bahan sudah ada di resep (hindari duplikat)
        $exists = Recipe::where('product_id', $product->id)
                        ->where('ingredient_id', $request->ingredient_id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Bahan baku ini sudah ada di resep.');
        }

        Recipe::create([
            'product_id' => $product->id,
            'ingredient_id' => $request->ingredient_id,
            'amount_needed' => $request->amount_needed
        ]);

        return back()->with('success', 'Bahan baku berhasil ditambahkan ke resep.');
    }

    // 4. Hapus Bahan dari Resep
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', 'Bahan baku dihapus dari resep.');
    }
}