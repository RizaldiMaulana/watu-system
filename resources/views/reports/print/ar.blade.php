@extends('reports.print.layout')

@section('content')
<table class="w-full text-sm border-collapse">
    <thead>
        <tr class="border-b-2 border-gray-800">
            <th class="py-3 text-left font-bold text-gray-700">No. Invoice</th>
            <th class="py-3 text-left font-bold text-gray-700">Tanggal</th>
            <th class="py-3 text-left font-bold text-gray-700">Customer</th>
            <th class="py-3 text-left font-bold text-gray-700">Jatuh Tempo</th>
            <th class="py-3 text-right font-bold text-gray-700">Total Tagihan</th>
            <th class="py-3 text-center font-bold text-gray-700">Aging</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        @php
            $due = $item->due_date ? \Carbon\Carbon::parse($item->due_date) : null;
            $days = $due ? now()->diffInDays($due, false) : 0;
            $overdue = $due && $due->isPast();
        @endphp
        <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="py-2">{{ $item->invoice_number }}</td>
            <td class="py-2">{{ $item->created_at->format('d/m/Y') }}</td>
            <td class="py-2 font-medium">{{ $item->customer->name ?? '-' }}</td>
            <td class="py-2">{{ $due ? $due->format('d/m/Y') : '-' }}</td>
            <td class="py-2 text-right font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
            <td class="py-2 text-center text-xs font-bold {{ $overdue ? 'text-red-600' : 'text-green-600' }}">
                {{ $overdue ? 'Overdue ' . abs((int)$days) . ' d' : (int)$days . ' days left' }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-gray-100 border-t-2 border-gray-800 font-bold">
            <td colspan="4" class="py-3 text-right pr-4">TOTAL PIUTANG</td>
            <td class="py-3 text-right">Rp {{ number_format($items->sum('total_amount'), 0, ',', '.') }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endsection
