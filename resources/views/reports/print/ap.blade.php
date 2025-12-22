@extends('reports.print.layout')

@section('content')
<table class="w-full text-sm border-collapse">
    <thead>
        <tr class="border-b-2 border-gray-800">
            <th class="py-3 text-left font-bold text-gray-700">No. Invoice (Ref)</th>
            <th class="py-3 text-left font-bold text-gray-700">Supplier</th>
            <th class="py-3 text-left font-bold text-gray-700">Jatuh Tempo</th>
             <th class="py-3 text-center font-bold text-gray-700">Status</th>
            <th class="py-3 text-right font-bold text-gray-700">Total Hutang</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        @php
            $due = $item->due_date ? \Carbon\Carbon::parse($item->due_date) : null;
            $overdue = $due && $due->isPast();
        @endphp
        <tr class="border-b border-gray-100 hover:bg-gray-50">
            <td class="py-2">{{ $item->invoice_number }}</td>
            <td class="py-2 font-medium">{{ $item->supplier->name ?? '-' }}</td>
            <td class="py-2">{{ $due ? $due->format('d/m/Y') : '-' }}</td>
            <td class="py-2 text-center text-xs {{ $overdue ? 'text-red-500 font-bold' : '' }}">
                {{ ucfirst($item->payment_status) }}
            </td>
            <td class="py-2 text-right font-bold">Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-gray-100 border-t-2 border-gray-800 font-bold">
            <td colspan="4" class="py-3 text-right pr-4 Uppercase">Total Hutang</td>
            <td class="py-3 text-right">Rp {{ number_format($items->sum('total_amount'), 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endsection
