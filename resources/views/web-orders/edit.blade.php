<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pesanan Online') }}
            </h2>
            <a href="{{ route('web-orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('web-orders.update', $order->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                            <input type="text" value="{{ $order->invoice_number }}" class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-500" disabled>
                        </div>

                        <div class="mb-6">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $order->customer_name) }}" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Pesanan</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">{{ old('notes', $order->notes) }}</textarea>
                        </div>

                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-bold text-gray-700 mb-2">Detail Item (Read Only)</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach($order->items as $item)
                                    <li class="flex justify-between">
                                        <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between font-bold">
                                <span>Total</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 italic">* Item pesanan hanya bisa diubah saat diproses di POS.</p>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-[#5f674d] text-white font-bold rounded-lg hover:bg-[#4b523d] transition shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
