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
        // Case 1: Update Master Data (Edit Button)
        if ($request->action_type == 'update_data') {
            $request->validate([
                'name' => 'required|string|max:255',
                'unit' => 'required|string',
                'minimum_stock' => 'required|numeric',
            ]);
            
            $ingredient->update([
                'name' => $request->name,
                'unit' => $request->unit,
                'minimum_stock' => $request->minimum_stock,
            ]);
            
            return back()->with('success', 'Data bahan baku berhasil diperbarui.');
        }

        // Case 2: Stock Opname (Direct Input)
        // Adjusts stock to match physical count.
        // Ideally, we should create a Journal for inventory variance here, but for now we just update stock.
        $request->validate([
            'stock' => 'required|numeric', 
        ]);

        $ingredient->update(['stock' => $request->stock]);

        return back()->with('success', 'Stok Opname berhasil disimpan. Stok diperbarui.');
    }

    // Hapus bahan baku
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return back()->with('success', 'Bahan baku dihapus');
    }
}