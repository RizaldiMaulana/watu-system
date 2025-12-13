<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Penerimaan Barang #' . $purchase->invoice_number) }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="px-8 py-6 border-b border-gray-100 bg-[#F9F7F2]">
                <h3 class="font-serif font-bold text-lg text-[#2b2623] mb-2">Detail Pesanan</h3>
                <div class="flex justify-between text-sm text-gray-600">
                    <p>Supplier: <span class="font-bold">{{ $purchase->supplier->name }}</span></p>
                    <p>Tanggal Order: <span class="font-bold">{{ date('d M Y', strtotime($purchase->transaction_date)) }}</span></p>
                </div>
            </div>

            <div class="p-8">
                <!-- LIST ITEMS -->
                <div class="mb-8">
                    <h4 class="font-bold text-gray-400 text-xs uppercase mb-3">Item yang harus diterima:</h4>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <ul class="space-y-3">
                            @foreach($purchase->items as $item)
                            <li class="flex justify-between items-center text-sm">
                                <span>{{ $item->item_name }}</span>
                                <span class="font-mono font-bold">{{ $item->quantity }} Unit</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <form action="{{ route('goods-receipt.store', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Penerimaan (Surat Jalan / Invoice Supplier)</label>
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition">
                            <input type="file" name="proof_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-sm text-gray-600 font-medium">Klik untuk upload atau drag file ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (Max 5MB)</p>
                        </div>
                        @error('proof_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="section-alert bg-blue-50 text-blue-800 p-4 rounded-lg text-xs mb-6 flex gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>Dengan menekan tombol simpan, stok akan otomatis bertambah ke sistem dan jurnal keuangan akan dicatat.</p>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('goods-receipt.index') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold text-sm transition transition-all">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Konfirmasi Terima Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
