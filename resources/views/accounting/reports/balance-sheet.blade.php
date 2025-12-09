<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            {{ __('Laporan Posisi Keuangan (Neraca)') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Filter Date -->
        <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-6 flex justify-between items-center">
            <h3 class="font-bold text-gray-700">Per Tanggal: {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</h3>
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <div class="flex gap-1 mr-2">
                     <a href="{{ request()->fullUrlWithQuery(['date' => date('Y-m-d')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Hari Ini</a>
                     <a href="{{ request()->fullUrlWithQuery(['date' => date('Y-m-t')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Akhir Bulan</a>
                     <a href="{{ request()->fullUrlWithQuery(['date' => date('Y-12-31')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Akhir Tahun</a>
                </div>
                <input type="date" name="date" value="{{ $endDate }}" class="text-sm rounded-md border-gray-300">
                <button type="submit" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#4a503b]">Filter</button>
                <a href="{{ route('accounting.reports.balance_sheet') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-300">Reset</a>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- ASSETS -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="bg-[#5f674d] px-6 py-4 border-b border-[#4a503b]">
                    <h3 class="font-bold text-white text-lg">ASET (HARTA)</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-sm">
                        @foreach($assets as $asset)
                        <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                            <td class="py-2 text-gray-600">{{ $asset->code }} - {{ $asset->name }}</td>
                            <td class="py-2 text-right font-mono font-bold text-gray-800">{{ number_format($asset->balance, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                    <h4 class="font-bold text-gray-700">TOTAL ASET</h4>
                    <h4 class="font-bold text-xl text-[#5f674d]">Rp {{ number_format($assets->sum('balance'), 0, ',', '.') }}</h4>
                </div>
            </div>

            <!-- LIABILITIES & EQUITY -->
            <div class="space-y-6">
                <!-- LIABILITIES -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="bg-red-500 px-6 py-4 border-b border-red-600">
                        <h3 class="font-bold text-white text-lg">KEWAJIBAN (LIABILITY)</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm">
                            @foreach($liabilities as $liability)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="py-2 text-gray-600">{{ $liability->code }} - {{ $liability->name }}</td>
                                <td class="py-2 text-right font-mono font-bold text-gray-800">{{ number_format($liability->balance, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            @if($liabilities->isEmpty())
                                <tr><td colspan="2" class="py-2 text-gray-400 italic text-center">Tidak ada data kewajiban</td></tr>
                            @endif
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <h4 class="font-bold text-gray-700">TOTAL KEWAJIBAN</h4>
                        <h4 class="font-bold text-xl text-red-500">Rp {{ number_format($liabilities->sum('balance'), 0, ',', '.') }}</h4>
                    </div>
                </div>

                <!-- EQUITY -->
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="bg-blue-500 px-6 py-4 border-b border-blue-600">
                        <h3 class="font-bold text-white text-lg">MODAL (EQUITY)</h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm">
                            @foreach($equity as $eq)
                            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                                <td class="py-2 text-gray-600">{{ $eq->code }} - {{ $eq->name }}</td>
                                <td class="py-2 text-right font-mono font-bold text-gray-800">{{ number_format($eq->balance, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            
                            <!-- RETAINED EARNINGS (NET INCOME) -->
                            <tr class="bg-blue-50 border-t border-blue-100">
                                <td class="py-2 pl-2 text-blue-800 font-bold">Laba/Rugi Periode Berjalan</td>
                                <td class="py-2 text-right font-mono font-bold text-blue-800">{{ number_format($netIncome, 0, ',', '.') }}</td>
                            </tr>

                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <h4 class="font-bold text-gray-700">TOTAL MODAL + LABA BERJALAN</h4>
                        <h4 class="font-bold text-xl text-blue-500">Rp {{ number_format($equity->sum('balance') + $netIncome, 0, ',', '.') }}</h4>
                    </div>
                </div>

                <!-- CHECK BALANCE -->
                <div class="bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden p-4 text-white flex justify-between items-center">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-gray-400">Total Pasiva (Kewajiban + Modal)</div>
                        <div class="text-xl font-bold">Rp {{ number_format($liabilities->sum('balance') + $equity->sum('balance') + $netIncome, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-right">
                        @php
                            $assetsTotal = $assets->sum('balance');
                            $pasivaTotal = $liabilities->sum('balance') + $equity->sum('balance') + $netIncome;
                            // Tolerance for floating point
                            $balanced = abs($assetsTotal - $pasivaTotal) < 1;
                        @endphp

                        @if($balanced)
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">Balance</span>
                        @else
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase">NOT Balance (Diff: {{ $assetsTotal - $pasivaTotal }})</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
