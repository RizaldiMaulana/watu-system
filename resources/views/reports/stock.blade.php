<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            {{ __('Laporan Stok Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        
        @php
            $criticalItems = $stocks->filter(fn($i) => $i->stock <= $i->minimum_stock);
        @endphp

        @if($criticalItems->count() > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        <span class="font-bold">Perhatian!</span> Terdapat {{ $criticalItems->count() }} item bahan baku yang stoknya menipis atau habis. Segera lakukan pembelian ulang.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-700">Status Persediaan Gudang</h3>
                <span class="text-xs text-gray-500 italic">* Data ini diperbarui otomatis dari Pembelian & Penjualan</span>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Batas Minimum</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stocks as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                            {{ $item->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $item->stock <= $item->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $item->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $item->minimum_stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->stock == 0)
                                <span class="text-xs font-bold text-red-600 uppercase border border-red-200 bg-red-50 px-2 py-1 rounded">Habis</span>
                            @elseif($item->stock <= $item->minimum_stock)
                                <span class="text-xs font-bold text-orange-600 uppercase border border-orange-200 bg-orange-50 px-2 py-1 rounded">Menipis</span>
                            @else
                                <span class="text-xs font-bold text-green-600 uppercase border border-green-200 bg-green-50 px-2 py-1 rounded">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>