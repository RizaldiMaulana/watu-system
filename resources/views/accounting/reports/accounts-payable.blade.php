<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Hutang Usaha (Accounts Payable)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ 
                    showPaymentModal: false,
                    selectedPurchase: null,
                    amountToPay: 0,
                    openPaymentModal(purchase) {
                        this.selectedPurchase = purchase;
                        this.amountToPay = purchase.total_amount - purchase.paid_amount;
                        this.showPaymentModal = true;
                    }
                }">
                    
                    <!-- Filter Date -->
                    <form method="GET" action="{{ route('accounting.reports.accounts_payable') }}" class="mb-6 flex flex-wrap gap-4 items-end">
                         <!-- ... (Filters kept same) ... -->
                        <div class="flex gap-2">
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
                                    <div class="font-bold text-lg text-gray-800">Rp {{ number_format($debt->total_amount - $debt->paid_amount, 0, ',', '.') }}</div>
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
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (IDR)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Terbayar</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Hutang</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right font-mono">
                                            {{ number_format($item->paid_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold text-right font-mono">
                                            {{ number_format($item->total_amount - $item->paid_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button @click="openPaymentModal({{ $item }})" class="bg-[#5f674d] text-white px-3 py-1 rounded text-xs font-bold hover:bg-[#4b523d] transition">
                                                Bayar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 italic">
                                            Tidak ada hutang yang belum lunas.
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="5" class="px-6 py-4 text-right">TOTAL HUTANG</td>
                                    <td class="px-6 py-4 text-right font-mono text-lg">{{ number_format($totalDebt, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <!-- Payment Modal -->
                    <div x-show="showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Overlay -->
                            <div x-show="showPaymentModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showPaymentModal = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal Panel -->
                            <div x-show="showPaymentModal" x-transition.scale 
                                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                
                                <form :action="`{{ url('purchases') }}/${selectedPurchase?.id}/payments`" method="POST">
                                    @csrf
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                    Pembayaran Hutang: <span x-text="selectedPurchase?.invoice_number"></span>
                                                </h3>
                                                <div class="mt-2 text-sm text-gray-500">
                                                    Sisa tagihan: <span class="font-bold text-gray-800" x-text="'Rp ' + (selectedPurchase?.total_amount - selectedPurchase?.paid_amount).toLocaleString('id-ID')"></span>
                                                </div>

                                                <div class="mt-4 space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700">Jumlah Pembayaran</label>
                                                        <input type="number" name="amount" x-model="amountToPay" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700">Tanggal Bayar</label>
                                                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700">Metode Pembayaran</label>
                                                        <select name="payment_method" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                                            <option value="cash">Tunai (Kas Besar)</option>
                                                            <option value="bank_transfer">Transfer Bank</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700">Catatan (Optional)</label>
                                                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#5f674d] focus:border-[#5f674d]"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5f674d] text-base font-medium text-white hover:bg-[#4a503b] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                            Proses Pembayaran
                                        </button>
                                        <button type="button" @click="showPaymentModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
