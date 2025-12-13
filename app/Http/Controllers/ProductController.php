<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. TAMPILKAN LIST DATA (TABEL)
    public function index(Request $request)
    {
        // Fitur Pencarian Sederhana
        $query = Product::query();
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }
        
        // Ambil data terbaru, paginasi 10 per halaman
        $products = $query->latest()->paginate(10);
        
        return view('products.index', compact('products'));
    }

    // 2. TAMPILKAN FORM TAMBAH
    // 2. TAMPILKAN FORM TAMBAH
    public function create()
    {
        $categories = \App\Models\Category::orderBy('sort_order')->get();
        return view('products.create', compact('categories'));
    }

    // 3. SIMPAN DATA BARU
   public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|unique:products,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ganti category string ke ID
            'process' => 'nullable|string|max:100',  // Tambahan
            'price' => 'required|numeric',
            'cost_price' => 'nullable|numeric', // Tambahan HPP
            'stock' => 'required|integer',
            'unit' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'options' => 'nullable|array', // Validasi Array
        ]);

        // Upload Gambar
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Ambil slug dari kategori untuk backward compatibility (kolom category lama)
        $category = \App\Models\Category::find($validated['category_id']);
        $validated['category'] = $category->slug;

        // Ensure options is set (sometimes empty array comes as null if using some FE libs, but checking validates)
        if ($request->has('options')) {
            $validated['options'] = array_values($request->options); // Reindex array just in case
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // UPDATE DATA + GAMBAR
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'nullable|unique:products,code,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'cost_price' => 'nullable|numeric', // Tambahan HPP
            'stock' => 'required|integer',
            'unit' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'options' => 'nullable|array',
        ]);

        // Cek jika ada gambar baru diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        
        // Sync legacy category column
        $category = \App\Models\Category::find($validated['category_id']);
        $validated['category'] = $category->slug;

        // Reset options if not provided (means removed all) or update
        $validated['options'] = $request->has('options') ? array_values($request->options) : null;

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // 4. TAMPILKAN FORM EDIT
    public function edit(Product $product)
    {
        $categories = \App\Models\Category::orderBy('sort_order')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    // 6. HAPUS DATA
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}