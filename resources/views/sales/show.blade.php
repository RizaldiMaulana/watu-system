@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-4">
            <a href="{{ route('sales.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-2">
                <i class="fa fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative">
            
            @if($transaction->is_void)
                <div class="absolute top-0 right-0 m-4">
                    <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-bold border border-red-200 transform rotate-12 shadow-sm">
                        VOID / DIBATALKAN
                    </span>
                 </div>
            @endif

            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $transaction->invoice_number }}</h1>
                        <p class="text-gray-500 text-sm mt-1">
                            {{ $transaction->created_at->format('d F Y, H:i') }} | Kasir: {{ $transaction->customer_name }}
                        </p>
                        @if($transaction->is_complimentary)
                            <span class="inline-block mt-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">Complimentary Order</span>
                        @endif
                    </div>
                     <div class="text-right">
                        <span class="block text-sm text-gray-500 mb-1">Status Pembayaran</span>
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $transaction->payment_status == 'Paid' ? 'bg-green-100 text-green-800' : ($transaction->payment_status == 'Void' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $transaction->payment_status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Detail Item</h3>
                <table class="w-full mb-6">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 uppercase">
                            <th class="pb-2">Produk</th>
                            <th class="pb-2 text-center">Qty</th>
                            <th class="pb-2 text-right">Harga</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($transaction->items as $item)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3">
                                <span class="font-medium block">{{ $item->product->name }}</span>
                                <span class="text-gray-400 text-xs">{{ $item->product->category->name ?? '-' }}</span>
                            </td>
                            <td class="py-3 text-center">{{ $item->quantity }}</td>
                            <td class="py-3 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-3 text-right font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end">
                    <div class="w-full max-w-xs space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($transaction->subtotal_amount, 0, ',', '.') }}</span>
                        </div>
                        @if($transaction->discount_amount > 0)
                        <div class="flex justify-between text-sm text-red-500">
                            <span>Discount {{ $transaction->discount_reason ? "({$transaction->discount_reason})" : '' }}</span>
                            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($transaction->tax_amount > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax ({{ $transaction->tax_rate }}%)</span>
                            <span>+ Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between text-lg font-bold text-[#5f674d] pt-3 border-t">
                            <span>Total</span>
                            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if(!$transaction->is_void && $transaction->payment_status == 'Paid')
            <div class="bg-gray-50 p-6 border-t border-gray-100">
                <h3 class="font-bold text-red-600 mb-2">Zona Berbahaya</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Jika terdapat kesalahan input, Anda dapat membatalkan transaksi ini (VOID). 
                    Stok akan dikembalikan dan jurnal akuntansi akan dibalik secara otomatis.
                    <br><strong>Tindakan ini tidak dapat dibatalkan.</strong>
                </p>
                
                <div x-data="{ open: false }">
                    <button @click="open = true" class="bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-100 transition">
                        Batalkan Transaksi (Void)
                    </button>

                    <!-- Modal Confirm -->
                    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 animate-fade-in-up">
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Void Trigger</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Apakah Anda yakin ingin membatalkan transaksi <strong>{{ $transaction->invoice_number }}</strong>?
                            </p>
                            
                            <form action="{{ route('sales.void', $transaction->uuid) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Alasan Pembatalan <span class="text-red-500">*</span></label>
                                    <input type="text" name="void_reason" required class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh: Salah input pesanan, Pelanggan batal...">
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="open = false" class="text-gray-500 hover:text-gray-700 font-bold text-sm px-3 py-2">Batal</button>
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700">Ya, Void Transaksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($transaction->is_void)
            <div class="bg-red-50 p-6 border-t border-red-100">
                <p class="text-sm text-red-800">
                    <strong>Transaksi Dibatalkan</strong> pada {{ $transaction->voided_at->format('d M Y H:i') }}.
                    <br>Alasan: {{ $transaction->void_reason }}
                </p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
