<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Purchase;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->input('tab', 'receivables'); // Default to AR

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = [];
        $totalAmount = 0;

        if ($activeTab === 'receivables') {
            // ACCOUNTS RECEIVABLE (AR) Logic
            $query = Transaction::where('payment_status', 'Unpaid')
                ->whereNotNull('customer_id')
                ->with(['customer', 'items']);

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $data = $query->orderBy('due_date', 'asc')->get();
            $totalAmount = $data->sum('total_amount');

        } else {
            // ACCOUNTS PAYABLE (AP) Logic
            $query = Purchase::where('payment_status', 'unpaid')
                ->with('supplier');

            if ($startDate && $endDate) {
                $query->whereBetween('due_date', [$startDate, $endDate]);
            }

            $data = $query->orderBy('due_date', 'asc')->get();
            $totalAmount = $data->sum('total_amount'); // Adjust if partial payments logic exists
        }

        return view('finance.index', compact('activeTab', 'data', 'totalAmount', 'startDate', 'endDate'));
    }
}
