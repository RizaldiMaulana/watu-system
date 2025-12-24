@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <!-- Header & Tabs -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Keuangan: Hutang & Piutang</h2>
                        <p class="text-sm text-gray-500">Monitoring Kewajiban dan Hak Tagih</p>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="bg-gray-100 p-1 rounded-lg flex items-center">
                        <a href="{{ route('finance.index', ['tab' => 'receivables']) }}" 
                           class="px-4 py-2 rounded-md text-sm font-bold transition-all {{ $activeTab === 'receivables' ? 'bg-white text-[#5f674d] shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Piutang Usaha (AR)
                        </a>
                        <a href="{{ route('finance.index', ['tab' => 'payables']) }}" 
                           class="px-4 py-2 rounded-md text-sm font-bold transition-all {{ $activeTab === 'payables' ? 'bg-white text-[#5f674d] shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Hutang Usaha (AP)
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                 <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">
                            Total {{ $activeTab === 'receivables' ? 'Piutang Belum Lunas' : 'Hutang Belum Dibayar' }}
                        </span>
                    </div>
                    <div class="text-right">
                         <span class="text-2xl font-black {{ $activeTab === 'receivables' ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                         </span>
                    </div>
                </div>

                <!-- Actions (Print/Export) -->
                <div class="flex justify-end gap-2 mb-4">
                     <a href="{{ route('reports.print', ['type' => ($activeTab === 'receivables' ? 'ar' : 'ap')]) }}" target="_blank" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Print Report
                    </a>
                </div>

                <!-- TABLE CONTENT -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref No / Tgl</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $activeTab === 'receivables' ? 'Customer' : 'Supplier' }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($data as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->invoice_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activeTab === 'receivables')
                                        <div class="text-sm font-medium text-gray-900">{{ $item->customer->name ?? $item->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->customer->phone ?? '' }}</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">{{ $item->supplier->name ?? '-' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d M Y') : '-' }}
                                    </div>
                                    @php
                                        $due = $item->due_date ? \Carbon\Carbon::parse($item->due_date) : null;
                                        $isOverdue = $due && $due->isPast();
                                        $days = $due ? now()->diffInDays($due, false) : 0;
                                    @endphp
                                    @if($isOverdue)
                                        <span class="text-xs text-red-600 font-bold">Overdue {{ abs((int)$days) }} days</span>
                                    @else
                                        <span class="text-xs text-green-600 font-bold">{{ (int)$days }} days left</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                    Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Unpaid
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    @if($activeTab === 'receivables')
                                        <a href="{{ route('ar.show', $item->uuid) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded font-bold mr-2">Bayar</a>
                                        <a href="{{ route('pos.print', $item->uuid) }}" target="_blank" class="text-gray-600 hover:text-gray-900 bg-gray-100 px-3 py-1 rounded font-bold" title="Cetak Invoice">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </a>
                                    @else
                                        <!-- For AP, maybe link to Purchase Detail -->
                                        <a href="{{ route('purchases.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded font-bold">Detail</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    Tidak ada data {{ $activeTab === 'receivables' ? 'Piutang' : 'Hutang' }} yang belum lunas.
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
