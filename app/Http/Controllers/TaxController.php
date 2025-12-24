<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;


class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxes = Tax::orderBy('sort_order')->get();
        return view('admin.accounting.taxes.index', compact('taxes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.accounting.taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'type' => 'required|in:tax,service_charge',
            'sort_order' => 'integer',
        ]);

        Tax::create([
            'name' => $request->name,
            'rate' => $request->rate,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('taxes.index')->with('success', 'Tax created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tax = Tax::findOrFail($id);
        return view('admin.accounting.taxes.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'type' => 'required|in:tax,service_charge',
            'sort_order' => 'integer',
        ]);

        $tax = Tax::findOrFail($id);
        $tax->update([
            'name' => $request->name,
            'rate' => $request->rate,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('taxes.index')->with('success', 'Tax updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tax = Tax::findOrFail($id);
        $tax->delete();

        return redirect()->route('taxes.index')->with('success', 'Tax deleted successfully.');
    }
}
