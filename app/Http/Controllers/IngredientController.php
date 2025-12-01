<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    // Tampilkan halaman daftar bahan baku
    public function index()
    {
        // Ambil data terbaru, paginate 10 baris
        $ingredients = Ingredient::latest()->paginate(10);
        return view('ingredients.index', compact('ingredients'));
    }

    // Simpan bahan baku baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:10', // ml, gr, pcs
            'stock' => 'required|numeric',
            'minimum_stock' => 'required|numeric',
        ]);

        Ingredient::create($request->all());

        return back()->with('success', 'Bahan baku berhasil ditambahkan');
    }

    // Update stok (Stok Opname) atau Edit Data
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'stock' => 'nullable|numeric', // Jika hanya update stok
            'name' => 'nullable|string',
            'unit' => 'nullable|string',
            'minimum_stock' => 'nullable|numeric',
        ]);

        $ingredient->update($request->all());

        return back()->with('success', 'Data bahan baku diperbarui');
    }

    // Hapus bahan baku
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return back()->with('success', 'Bahan baku dihapus');
    }
}