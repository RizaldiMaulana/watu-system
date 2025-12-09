<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('Accounting Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-[#5f674d]">
                <div class="text-gray-500 text-sm font-bold uppercase">Total Akun (COA)</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">{{ $accountCount }}</div>
                <a href="{{ route('accounting.coa') }}" class="text-[#5f674d] text-sm mt-4 hover:underline block">Kelola Akun &rarr;</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Total Jurnal</div>
                <div class="text-3xl font-bold text-gray-800 mt-2">{{ $journalCount }}</div>
                <a href="{{ route('reports.index') }}" class="text-blue-500 text-sm mt-4 hover:underline block">Lihat Jurnal &rarr;</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                <div class="text-gray-500 text-sm font-bold uppercase">Laporan Keuangan</div>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="{{ route('accounting.reports.balance_sheet') }}" class="bg-purple-50 text-purple-700 px-3 py-2 rounded-lg text-sm font-bold hover:bg-purple-100 transition">
                        📊 Neraca (Balance Sheet)
                    </a>
                    <a href="{{ route('accounting.reports.income_statement') }}" class="bg-purple-50 text-purple-700 px-3 py-2 rounded-lg text-sm font-bold hover:bg-purple-100 transition">
                        📉 Laba Rugi (Income Statement)
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Journals -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 text-lg">Jurnal Terbaru</h3>
                <a href="{{ route('accounting.journal.create') }}" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#4a503b] transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Jurnal Manual
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentJournals as $journal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $journal->transaction_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $journal->ref_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $journal->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 font-mono">{{ number_format($journal->total_debit, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
