<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ __('Manajemen Aset Tetap') }}
            </h2>
            <div class="flex gap-2">
                <form method="POST" action="{{ route('accounting.assets.depreciate') }}" data-confirm="Jalankan penyusutan untuk periode ini? Jurnal akan dibuat otomatis.">
                    @csrf
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="text-sm rounded-md border-gray-300 mr-2" required>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-700">
                        Hitung Penyusutan
                    </button>
                </form>
                <a href="{{ route('accounting.assets.create') }}" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#4a503b]">
                    + Tambah Aset
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase">Total Nilai Aset (Cost)</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($assets->sum('cost'), 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase">Akumulasi Penyusutan</p>
                <h3 class="text-2xl font-bold text-red-600">Rp {{ number_format($assets->sum('depreciation_accumulated'), 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase">Nilai Buku Bersih (Net Book Value)</p>
                <h3 class="text-2xl font-bold text-[#5f674d]">Rp {{ number_format($assets->sum('book_value'), 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold">
                        <tr>
                            <th class="px-4 py-3">Nama Aset</th>
                            <th class="px-4 py-3">Tgl Beli</th>
                            <th class="px-4 py-3">Umur (Thn)</th>
                            <th class="px-4 py-3 text-right">Harga Perolehan</th>
                            <th class="px-4 py-3 text-right">Nilai Sisa</th>
                            <th class="px-4 py-3 text-right">Akum. Penyusutan</th>
                            <th class="px-4 py-3 text-right">Nilai Buku</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold">{{ $asset->name }}<br><span class="text-xs font-normal text-gray-500">{{ $asset->description }}</span></td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center">{{ $asset->useful_life_years }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($asset->cost, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-gray-500">Rp {{ number_format($asset->salvage_value, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-red-500">Rp {{ number_format($asset->depreciation_accumulated, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-[#5f674d]">Rp {{ number_format($asset->book_value, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs rounded-full {{ $asset->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ strtoupper($asset->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada data aset tetap.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
