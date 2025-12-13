<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with('transaction.items.product')->latest('booking_date');

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->has('status') && $request->status != '') {
             $query->where('status', $request->status);
        }

        $reservations = $query->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'pax' => 'required|numeric',
            'status' => 'required'
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi diperbarui.');
    }
    
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Optional: Cancel linked transaction if exists?
        // For now, let's keep it simple. If hard delete is needed, cascade.
        
        $reservation->delete();
        
        return redirect()->back()->with('success', 'Reservasi dihapus.');
    }

    public function processToPos($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Check if there is a linked transaction (Pre-order)
        if ($reservation->transaction) {
            return redirect()->route('pos.index', ['order_id' => $reservation->transaction->uuid]);
        }
        
        // If no transaction, we could pass customer name to POS?
        // For now, simpler to just redirect to POS and user enters manually, 
        // or we could add logic to PosController to accept 'reservation_id' later.
        // Let's redirect with a message for now.
        return redirect()->route('pos.index')->with('info', 'Reservasi ini tidak memiliki Pre-order. Silakan input manual.');
    }
}
