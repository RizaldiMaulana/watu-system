<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Utang (Accounts Payable)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Filter Date -->
                    <form method="GET" action="{{ route('accounting.reports.accounts_payable') }}" class="mb-6 flex flex-wrap gap-4 items-end">
                        <div class="flex gap-2">
                             <!-- Quick Filters (Implemented as per plan later, but adding placeholder/structure) -->
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Hari Ini</a>
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Bulan Ini</a>
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-01-01'), 'end_date' => date('Y-12-31')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Tahun Ini</a>
                        </div>
                        <div class="border-l pl-4 flex gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo Awal</label>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo Akhir</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-[#5f674d] text-white px-4 py-2 rounded-md hover:bg-[#4a503b]">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Urgent Debts Highlight -->
                    @if($urgentDebts->count() > 0)
                    <div class="mb-8 p-4 bg-red-50 rounded-lg border border-red-200">
                        <h3 class="font-bold text-red-700 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Jatuh Tempo Terdekat (Urgent)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($urgentDebts as $debt)
                                <div class="bg-white p-3 rounded shadow-sm border-l-4 border-red-500">
                                    <div class="text-sm text-gray-500">{{ $debt->supplier->name }}</div>
                                    <div class="font-bold text-lg text-gray-800">Rp {{ number_format($debt->total_amount, 0, ',', '.') }}</div>
                                    <div class="text-xs font-bold {{ $debt->due_date < now() ? 'text-red-600' : 'text-orange-600' }}">
                                        Due: {{ \Carbon\Carbon::parse($debt->due_date)->format('d M Y') }}
                                        @if($debt->due_date < now()) (Lewat {{ now()->diffInDays($debt->due_date) }} hari) @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Main Table -->
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice / Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (IDR)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($purchases as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $item->invoice_number }}</div>
                                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->supplier->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                            {{ number_format($item->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Unpaid
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                            Tidak ada hutang yang belum lunas.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="3" class="px-6 py-4 text-right">TOTAL HUTANG</td>
                                    <td class="px-6 py-4 text-right font-mono text-lg">{{ number_format($totalDebt, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
