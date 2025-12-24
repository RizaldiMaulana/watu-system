<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('goods-receipt.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Validasi Penerimaan Barang') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 text-black" x-data="{ tab: 'details' }">

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                <strong class="font-bold">Perhatian!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN: INFO & PROOF --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- PO Info Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Detail Pesanan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nomor PO</span>
                            <span class="font-mono font-bold">{{ $purchase->invoice_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-bold">{{ $purchase->transaction_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Supplier</span>
                            <span class="font-bold text-right">{{ $purchase->supplier->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dibuat Oleh</span>
                            <span class="font-bold">{{ $purchase->creator->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Proof Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-700 mb-4 border-b pb-2 flex justify-between items-center">
                        Bukti Penerimaan
                        @if($purchase->delivery_proof)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] rounded-full">Ada</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] rounded-full">Belum Ada</span>
                        @endif
                    </h3>
                    
                    @if($purchase->delivery_proof)
                        <div class="relative group cursor-pointer" @click="window.open('{{ asset('storage/' . $purchase->delivery_proof) }}', '_blank')">
                            <img src="{{ asset('storage/' . $purchase->delivery_proof) }}" class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <span class="bg-black/50 text-white px-3 py-1 rounded text-xs">Klik untuk perbesar</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2 text-center">Dikirim oleh Supplier/Kurir</p>
                    @else
                        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="mt-2 block text-sm font-medium text-gray-900">Belum ada bukti foto</span>
                            <p class="mt-1 text-xs text-red-500">Wajib upload bukti sebelum validasi.</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- RIGHT COLUMN: DOCUMENT PREVIEW & ACTION --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Tabs --}}
                <div class="flex gap-4 border-b border-gray-200">
                    <button @click="tab = 'details'" :class="tab === 'details' ? 'border-[#5f674d] text-[#5f674d]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-3 px-1 border-b-2 font-bold text-sm transition">
                        Detail Item
                    </button>
                    <button @click="tab = 'document'" :class="tab === 'document' ? 'border-[#5f674d] text-[#5f674d]' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-3 px-1 border-b-2 font-bold text-sm transition">
                        Preview Dokumen (Berita Acara)
                    </button>
                </div>

                {{-- Tab 1: Item Details --}}
                <div x-show="tab === 'details'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Nama Item</th>
                                <th class="px-6 py-3 text-center">Tipe</th>
                                <th class="px-6 py-3 text-center">Jumlah Order</th>
                                <th class="px-6 py-3 text-right">Harga Satuan</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($purchase->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item->item_name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 {{ $item->ingredient_id ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} rounded text-[10px] font-bold">
                                        {{ $item->ingredient_id ? 'Bahan Baku' : 'Produk' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-700">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-600">TOTAL NILAI BARANG</td>
                                <td class="px-6 py-4 text-right font-bold text-xl text-[#5f674d]">Rp {{ number_format($purchase->items->sum(fn($i) => $i->quantity * $i->price), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Tab 2: Document Preview (Iframe/Embedded View) --}}
                <div x-show="tab === 'document'" class="bg-gray-100 p-4 rounded-xl border border-gray-200">
                    <div class="bg-white shadow-lg mx-auto max-w-2xl transform scale-95 origin-top-center">
                        {{-- Include or iframe the print view. For simplicity, let's iframe it so it matches exactly 1:1 --}}
                        <iframe src="{{ route('goods-receipt.print', $purchase->id) }}" class="w-full h-[600px] border-0" title="Preview Dokumen"></iframe>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="flex items-center justify-between bg-[#F9F7F2] p-6 rounded-xl border border-[#5f674d]/20 mt-8">
                    <div>
                        <h4 class="font-bold text-[#5f674d]">Konfirmasi Validasi</h4>
                        <p class="text-xs text-gray-500 mt-1 max-w-sm">
                            Dengan memvalidasi, stok akan ditambahkan ke sistem dan tanda tangan digital Anda akan dibubuhkan pada dokumen Berita Acara.
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <form action="{{ route('goods-receipt.verify', $purchase->id) }}" method="POST" class="flex items-center gap-4">
                            @csrf
                            
                            @if(!$purchase->delivery_proof)
                                <div class="text-xs text-red-500 font-bold bg-white px-3 py-2 rounded border border-red-200">
                                    ⚠️ Upload Bukti Dulu
                                </div>
                                <button type="button" disabled class="px-6 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                                    Tanda Tangan & Validasi
                                </button>
                            @else
                                <div class="text-center mr-4">
                                     <p class="text-[10px] uppercase text-gray-400 font-bold mb-1">Signed By</p>
                                     <div class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</div>
                                </div>
                                <button type="submit" data-confirm="Apakah Anda yakin data sudah benar?" class="px-6 py-3 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Tanda Tangan & Validasi
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
