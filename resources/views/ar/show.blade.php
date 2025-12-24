@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-start mb-6 border-b pb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Tagihan: {{ $transaction->invoice_number }}</h2>
                        <p class="text-gray-500">Customer: <span class="font-bold text-gray-800">{{ $transaction->customer->name ?? $transaction->customer_name }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">Tgl Transaksi: {{ $transaction->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                         <span class="px-3 py-1 text-sm font-bold rounded-full bg-red-100 text-red-800">Status: Unpaid</span>
                         <div class="mt-2 text-xs text-gray-500">Jatuh Tempo: {{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}</div>
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-700 uppercase mb-3">Rincian Item</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="pb-2">Produk</th>
                                    <th class="pb-2 text-center">Qty</th>
                                    <th class="pb-2 text-right">Harga</th>
                                    <th class="pb-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->items as $item)
                                <tr>
                                    <td class="py-2">{{ $item->product->name ?? 'Unknown' }}</td>
                                    <td class="py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-2 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="pt-3 text-right font-bold">Total Tagihan</td>
                                    <td class="pt-3 text-right font-black text-xl text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-[#F9F7F2] p-6 rounded-xl border border-[#5f674d]/20">
                    <h3 class="text-lg font-bold text-[#5f674d] mb-4">Proses Pembayaran (Pelunasan)</h3>
                    <form action="{{ route('ar.storePayment', $transaction->uuid) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                <select name="payment_method" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#5f674d]">
                                    <option value="Cash">Tunai</option>
                                    <option value="Transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Bayar</label>
                                <input type="number" name="amount" value="{{ $transaction->total_amount }}" class="w-full rounded-lg border-gray-300 shadow-sm font-bold text-gray-900 focus:ring-[#5f674d]" readonly>
                                <p class="text-xs text-gray-500 mt-1">* Saat ini hanya mendukung pelunasan penuh.</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('finance.index', ['tab' => 'receivables']) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-bold transition">Batal</a>
                            <button type="submit" data-confirm="Konfirmasi pelunasan piutang ini?" class="px-6 py-2 bg-[#5f674d] hover:bg-[#4b523d] text-white rounded-lg font-bold shadow-lg transition">
                                Konfirmasi Pelunasan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
