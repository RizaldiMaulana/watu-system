<?php

namespace App\Http\Controllers;

use App\Models\Ingredient; // Import Model Ingredient
use App\Models\AuditLog;   // Import Model AuditLog
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Facade Auth

class StockAdjustmentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'actual_stock' => 'required|numeric',
            'reason' => 'required|string', // Wajib diisi untuk Audit Trail
        ]);

        $ingredient = Ingredient::findOrFail($request->ingredient_id);
        $systemStock = $ingredient->stock;
        
        // Update Stok Fisik ke Database
        $ingredient->update(['stock' => $request->actual_stock]);

        // Catat di Audit Log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Stock Adjustment', // Penyesuaian Stok
            'description' => "Penyesuaian stok {$ingredient->name}. Sistem: {$systemStock}, Fisik: {$request->actual_stock}. Selisih: " . ($request->actual_stock - $systemStock) . ". Alasan: {$request->reason}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Stok Opname Berhasil Dicatat & Audit Log Tersimpan.');
    }
}