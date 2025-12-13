@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Riwayat Penjualan</h2>
            <div class="bg-white px-4 py-2 rounded-lg shadow-sm">
                <span class="text-sm text-gray-500">Total Penjualan Hari Ini</span>
                <p class="text-lg font-bold text-[#5f674d]">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('sales.index') }}" method="GET" class="flex gap-4">
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No Invoice / Nama..." class="rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d] w-64">
                <button type="submit" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg font-bold hover:bg-[#4a503a] transition">
                    Filter
                </button>
                @if(request()->has('date') || request()->has('search'))
                    <a href="{{ route('sales.index') }}" class="text-gray-500 hover:text-gray-700 font-bold self-center">Reset</a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 {{ $trx->is_void ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $trx->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $trx->invoice_number }}
                            @if($trx->is_complimentary)
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded textxs ml-1">Promo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $trx->customer_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($trx->is_void)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    VOID
                                </span>
                            @elseif($trx->payment_status == 'Paid')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    LUNAS
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    PENDING
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('sales.show', $trx->uuid) }}" class="text-[#5f674d] hover:text-[#4a503a]">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data penjualan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
