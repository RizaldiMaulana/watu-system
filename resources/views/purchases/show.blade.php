<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pembelian (PO)') }} - {{ $purchase->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-bold">{{ $purchase->supplier->name ?? 'Unknown Supplier' }}</h3>
                            <p class="text-sm text-gray-500">{{ $purchase->supplier->contact_person ?? '' }} - {{ $purchase->supplier->phone ?? '' }}</p>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $purchase->status == 'received' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    STATUS: {{ strtoupper($purchase->status) }}
                                </span>
                                <span class="px-2 py-1 text-xs font-bold rounded {{ $purchase->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    PAYMENT: {{ strtoupper($purchase->payment_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tanggal: {{ \Carbon\Carbon::parse($purchase->transaction_date)->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">Jatuh Tempo: {{ $purchase->due_date ? \Carbon\Carbon::parse($purchase->due_date)->format('d M Y') : '-' }}</p>
                            <a href="{{ route('purchases.print', $purchase->id) }}" target="_blank" class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700">
                                Print PDF
                            </a>
                        </div>
                    </div>

                    <table class="w-full text-sm mb-6 border-collapse border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border p-2 text-left">Item</th>
                                <th class="border p-2 text-center">Qty</th>
                                <th class="border p-2 text-right">Harga Satuan</th>
                                <th class="border p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td class="border p-2">
                                    <span class="font-bold">{{ $item->item_name }}</span>
                                    <span class="text-xs text-gray-500 block">{{ $item->product ? 'Product' : ($item->ingredient ? 'Bahan Baku' : 'Unknown') }}</span>
                                </td>
                                <td class="border p-2 text-center">{{ $item->quantity }}</td>
                                <td class="border p-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="border p-2 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="border p-2 text-right font-bold">Total</td>
                                <td class="border p-2 text-right font-bold text-lg">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="border-t pt-4">
                        <h4 class="font-bold mb-2">Catatan:</h4>
                        <p class="text-gray-600 italic">{{ $purchase->notes ?? '-' }}</p>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('finance.index', ['tab' => 'payables']) }}" class="bg-gray-500 text-white px-4 py-2 rounded font-bold hover:bg-gray-600">Kembali</a>
                        
                        @if($purchase->status != 'received')
                        <a href="{{ route('goods-receipt.create', $purchase->id) }}" class="bg-[#5f674d] text-white px-4 py-2 rounded font-bold hover:bg-[#4a503b]">
                            Terima Barang (Goods Receipt)
                        </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
