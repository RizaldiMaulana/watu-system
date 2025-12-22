@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Accounts Receivable (Piutang Dagang)</h2>
                        <p class="text-sm text-gray-500">Aging Report & Pelunasan Invoice B2B</p>
                    </div>
                     <div class="bg-red-50 px-4 py-2 rounded-lg border border-red-100">
                        <span class="text-xs text-red-600 block">Total Piutang Belum Setor</span>
                        <span class="text-xl font-black text-red-700">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice / Tgl</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $inv)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $inv->invoice_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $inv->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $inv->customer->name ?? $inv->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $inv->customer->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $dueDate = $inv->due_date ? \Carbon\Carbon::parse($inv->due_date) : null;
                                        $isOverdue = $dueDate && $dueDate->isPast();
                                        $daysLeft = $dueDate ? now()->diffInDays($dueDate, false) : 0;
                                    @endphp
                                    <div class="text-sm text-gray-900">
                                        {{ $dueDate ? $dueDate->format('d M Y') : '-' }}
                                    </div>
                                    @if($isOverdue)
                                        <span class="text-xs text-red-600 font-bold">Overdue {{ abs((int)$daysLeft) }} days</span>
                                    @else
                                        <span class="text-xs text-green-600 font-bold">{{ (int)$daysLeft }} days left</span>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1">Term: {{ $inv->payment_term ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Unpaid
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('ar.show', $inv->uuid) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded-md">
                                        Bayar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    Tidak ada piutang yang belum lunas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
