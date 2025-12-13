<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class WebOrderController extends Controller
{
    /**
     * Display a listing of active (unpaid) web orders.
     */
    public function index(Request $request)
    {
        $query = Transaction::where('type', 'Web-Order')
                            ->where('payment_status', 'Unpaid')
                            ->latest();

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(10);

        return view('web-orders.index', compact('orders'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($uuid)
    {
        $order = Transaction::where('uuid', $uuid)->firstOrFail();
        return view('web-orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'customer_name' => 'required',
            'notes' => 'nullable|string'
        ]);

        $order = Transaction::where('uuid', $uuid)->firstOrFail();
        
        $order->update([
            'customer_name' => $request->customer_name,
            'notes' => $request->notes
        ]);

        return redirect()->route('web-orders.index')->with('success', 'Data pesanan berhasil diperbarui.');
    }

    /**
     * Remove the specified order from storage (Cancel/Void).
     */
    public function destroy($uuid)
    {
        $order = Transaction::where('uuid', $uuid)->firstOrFail();
        
        // Use Void logic if we want to reverse Journal/Stock, 
        // OR simply Delete if it's just an unpaid order draft.
        // Since Web Orders already create Journal entries (Receivable/Draft), 
        // we should probably do a proper Void OR Soft Delete.
        // Let's use Delete for now, but we should clear the Journal.
        
        // 1. Delete Journal (Draft Journal)
        \App\Models\Journal::where('ref_number', $order->invoice_number)->delete();
        
        // 2. Delete Items
        $order->items()->delete();
        
        // 3. Delete Header
        $order->delete();

        return redirect()->route('web-orders.index')->with('success', 'Pesanan online dibatalkan & dihapus.');
    }
}
