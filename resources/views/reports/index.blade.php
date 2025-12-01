<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            {{ __('Laporan Keuangan & Jurnal') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center gap-4 relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-10">
                    <svg class="w-24 h-24 text-[#5f674d]" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                </div>
                <div class="p-4 bg-[#5f674d]/10 rounded-full text-[#5f674d]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Transaksi (7 Hari)</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($values->sum(), 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-bold text-gray-700">Grafik Arus Kas</h3>
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">7 Hari Terakhir</span>
                </div>
                <div class="h-64 w-full">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-700">Jurnal Umum (General Ledger)</h3>
                    <p class="text-xs text-gray-500">Data transaksi tercatat secara otomatis</p>
                </div>
                <button class="px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Ref</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan / Akun</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Debit (Rp)</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Kredit (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($journals as $journal)
                            <tr class="bg-gray-50/30">
                                <td class="px-6 py-3 text-xs text-gray-500 font-mono border-t border-gray-100">{{ $journal->transaction_date }}</td>
                                <td class="px-6 py-3 text-xs font-bold text-[#5f674d] border-t border-gray-100">{{ $journal->ref_number }}</td>
                                <td class="px-6 py-3 text-xs text-gray-700 font-bold uppercase tracking-wide border-t border-gray-100" colspan="3">{{ $journal->description }}</td>
                            </tr>
                            
                            @foreach($journal->details as $detail)
                            <tr class="hover:bg-gray-50 transition">
                                <td colspan="2"></td>
                                <td class="px-6 py-2 text-sm text-gray-600 pl-10 border-l-2 {{ $detail->debit > 0 ? 'border-[#5f674d]' : 'border-gray-300' }}">
                                    <div class="flex justify-between">
                                        <span>{{ $detail->account->name ?? 'Akun Tidak Dikenal' }}</span>
                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 rounded">{{ $detail->account->code ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-2 text-sm text-right font-mono text-gray-700">
                                    {{ $detail->debit > 0 ? number_format($detail->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-2 text-sm text-right font-mono text-gray-700">
                                    {{ $detail->credit > 0 ? number_format($detail->credit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $journals->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('financeChart').getContext('2d');
            
            // PERBAIKAN: Gunakan Js::from() untuk konversi data PHP ke JS
            const labels = {{ \Illuminate\Support\Js::from($labels) }};
            const dataValues = {{ \Illuminate\Support\Js::from($values) }};

            new Chart(ctx, {
                type: 'line', 
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Transaksi',
                        data: dataValues,
                        borderColor: '#5f674d', // Warna Watu Olive
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                            gradient.addColorStop(0, 'rgba(95, 103, 77, 0.2)');
                            gradient.addColorStop(1, 'rgba(95, 103, 77, 0)');
                            return gradient;
                        },
                        borderWidth: 2,
                        tension: 0.4, 
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#5f674d',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { borderDash: [4, 4] },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>