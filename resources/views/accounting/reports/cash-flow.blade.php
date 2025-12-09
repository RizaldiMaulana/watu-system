<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Arus Kas (Cash Flow)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Filter Date -->
                    <form method="GET" action="{{ route('accounting.reports.cash_flow') }}" class="mb-6 flex flex-wrap gap-4 items-end">
                        <div class="flex gap-2">
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Hari Ini</a>
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Bulan Ini</a>
                             <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-01-01'), 'end_date' => date('Y-12-31')]) }}" class="px-3 py-2 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Tahun Ini</a>
                        </div>
                        <div class="border-l pl-4 flex gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-[#5f674d] text-white px-4 py-2 rounded-md hover:bg-[#4a503b]">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="border rounded-lg p-6">
                        <h3 class="text-center text-lg font-bold mb-1">LAPORAN ARUS KAS</h3>
                        <p class="text-center text-sm text-gray-500 mb-6">Periode: {{ $startDate }} s/d {{ $endDate }}</p>

                        <div class="space-y-6">
                            <!-- OPERATING -->
                            <div>
                                <h4 class="font-bold text-[#5f674d] mb-2 uppercase text-sm border-b pb-1">Arus Kas dari Aktivitas Operasional</h4>
                                <div class="flex justify-between py-1 px-2 hover:bg-gray-50">
                                    <span>Penerimaan Kas (Penjualan, dll)</span>
                                    <span class="font-mono text-green-600">+ Rp {{ number_format($cashIn, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-1 px-2 hover:bg-gray-50">
                                    <span>Pengeluaran Kas (Beli Stok, Biaya, dll)</span>
                                    <span class="font-mono text-red-600">- Rp {{ number_format($cashOut, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-2 px-2 mt-2 font-bold bg-gray-100 rounded">
                                    <span>Kas Bersih dari Operasional</span>
                                    <span class="font-mono">Rp {{ number_format($netOperatingCash, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- INVESTING -->
                            <div>
                                <h4 class="font-bold text-[#5f674d] mb-2 uppercase text-sm border-b pb-1">Arus Kas dari Aktivitas Investasi</h4>
                                <div class="flex justify-between py-1 px-2 hover:bg-gray-50">
                                    <span>Pembelian Aset Tetap</span>
                                    <span class="font-mono text-red-600">- Rp 0</span>
                                </div>
                                <div class="flex justify-between py-2 px-2 mt-2 font-bold bg-gray-100 rounded">
                                    <span>Kas Bersih dari Investasi</span>
                                    <span class="font-mono">Rp 0</span>
                                </div>
                            </div>

                            <!-- FINANCING -->
                            <div>
                                <h4 class="font-bold text-[#5f674d] mb-2 uppercase text-sm border-b pb-1">Arus Kas dari Aktivitas Pendanaan</h4>
                                <div class="flex justify-between py-1 px-2 hover:bg-gray-50">
                                    <span>Setoran Modal / Pinjaman</span>
                                    <span class="font-mono text-green-600">+ Rp 0</span>
                                </div>
                                <div class="flex justify-between py-2 px-2 mt-2 font-bold bg-gray-100 rounded">
                                    <span>Kas Bersih dari Pendanaan</span>
                                    <span class="font-mono">Rp 0</span>
                                </div>
                            </div>

                            <!-- SUMMARY -->
                            <div class="pt-4 border-t-2 border-gray-400">
                                <div class="flex justify-between py-1 px-2 font-bold text-lg">
                                    <span>Kenaikan (Penurunan) Bersih Kas</span>
                                    <span class="font-mono {{ $netChangeInCash < 0 ? 'text-red-600' : 'text-green-600' }}">Rp {{ number_format($netChangeInCash, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-1 px-2 text-gray-600">
                                    <span>Saldo Kas Awal Periode</span>
                                    <span class="font-mono">Rp {{ number_format($beginningCash, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-2 px-2 mt-2 bg-[#5f674d] text-white font-bold text-lg rounded">
                                    <span>Saldo Kas Akhir Periode</span>
                                    <span class="font-mono">Rp {{ number_format($endingCash, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
