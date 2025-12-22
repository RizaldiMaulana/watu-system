<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            {{ __('Laporan Laba Rugi (Income Statement)') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <!-- Filter Date -->
        <div class="bg-white shadow-sm sm:rounded-lg p-4 mb-6 flex justify-between items-center">
            <h3 class="font-bold text-gray-700">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h3>
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <div class="flex gap-1 mr-2">
                     <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Hari Ini</a>
                     <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Bulan Ini</a>
                     <a href="{{ request()->fullUrlWithQuery(['start_date' => date('Y-01-01'), 'end_date' => date('Y-12-31')]) }}" class="px-2 py-1 text-xs font-bold bg-gray-100 hover:bg-gray-200 rounded text-gray-600">Tahun Ini</a>
                </div>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm rounded-md border-gray-300">
                <span class="self-center">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm rounded-md border-gray-300">
                <button type="submit" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#4a503b]">Filter</button>
                <a href="{{ route('accounting.reports.income_statement') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-300">Reset</a>
                
                <div class="ml-4 flex gap-2 border-l pl-4 border-gray-300">
                    <a href="{{ route('reports.print', ['type' => 'income-statement', 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 flex items-center gap-1" title="Print PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Print
                    </a>
                    <a href="{{ route('reports.print', ['type' => 'income-statement', 'start_date' => $startDate, 'end_date' => $endDate, 'export' => 'excel']) }}" target="_blank" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 flex items-center gap-1" title="Export Excel">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        XLS
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden max-w-4xl mx-auto">
            
            <!-- REVENUE -->
            <div class="px-8 py-6">
                <h3 class="text-xl font-bold text-[#5f674d] mb-4 border-b pb-2">PENDAPATAN (REVENUE)</h3>
                <table class="w-full text-sm">
                    @foreach($revenues as $revenue)
                    <tr class="group hover:bg-gray-50">
                        <td class="py-2 text-gray-600 pl-4 border-l-2 border-transparent group-hover:border-[#5f674d]">{{ $revenue->code }} - {{ $revenue->name }}</td>
                        <td class="py-2 text-right font-mono text-gray-800">{{ number_format($revenue->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="font-bold text-lg bg-gray-50">
                        <td class="py-3 text-gray-800">Total Pendapatan</td>
                        <td class="py-3 text-right text-[#5f674d]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- EXPENSES -->
            <div class="px-8 py-6 pt-0">
                <h3 class="text-xl font-bold text-red-500 mb-4 border-b pb-2">BEBAN (EXPENSES)</h3>
                <table class="w-full text-sm">
                    @foreach($expenses as $expense)
                    <tr class="group hover:bg-gray-50">
                        <td class="py-2 text-gray-600 pl-4 border-l-2 border-transparent group-hover:border-red-500">{{ $expense->code }} - {{ $expense->name }}</td>
                        <td class="py-2 text-right font-mono text-gray-800">{{ number_format($expense->balance, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr class="font-bold text-lg bg-gray-50">
                        <td class="py-3 text-gray-800">Total Beban</td>
                        <td class="py-3 text-right text-red-500">(Rp {{ number_format($totalExpense, 0, ',', '.') }})</td>
                    </tr>
                </table>
            </div>

            <!-- NET INCOME -->
            <div class="px-8 py-6 bg-gray-900 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">LABA / RUGI BERSIH</h2>
                        <p class="text-gray-400 text-sm">Net Income</p>
                    </div>
                    <div class="text-3xl font-bold {{ $netIncome >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        Rp {{ number_format($netIncome, 0, ',', '.') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
