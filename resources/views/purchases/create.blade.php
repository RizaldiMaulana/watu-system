<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Input Pembelian Stok') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="px-8 py-6 border-b border-gray-100 bg-[#F9F7F2] flex justify-between items-center">
                <div>
                    <h3 class="font-serif font-bold text-lg text-[#2b2623]">Formulir Pengadaan</h3>
                    <p class="text-xs text-gray-500 mt-1">Pastikan bahan baku sudah terdaftar di Master Data.</p>
                </div>
                <div class="px-3 py-1 bg-[#5f674d]/10 text-[#5f674d] rounded-full text-xs font-bold">
                    #PUR-{{ date('dmY') }}
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                            <div class="relative">
                                <input type="date" name="transaction_date" 
                                       class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#5f674d]/20 focus:border-[#5f674d] transition font-medium"
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Supplier</label>
                            <div class="relative">
                                <select name="supplier_id" class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#5f674d]/20 focus:border-[#5f674d] appearance-none cursor-pointer font-medium">
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8 relative">
                        <div class="absolute top-0 left-0 bg-[#5f674d] text-white text-[10px] font-bold px-3 py-1 rounded-br-lg rounded-tl-lg">
                            ITEM PEMBELIAN
                        </div>

                        <div class="grid grid-cols-12 gap-4 mt-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Bahan Baku</label>
                                <select name="items[0][ingredient_id]" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5">
                                    <option value="" disabled selected>-- Pilih dari Inventory --</option>
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}">
                                            {{ $ing->name }} (Stok: {{ $ing->stock }} {{ $ing->unit }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    *Item tidak ada? <a href="{{ route('ingredients.index') }}" class="text-[#5f674d] underline hover:text-black">Tambah di Master Data</a>
                                </p>
                            </div>

                            <div class="col-span-6 md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Jumlah</label>
                                <input type="number" name="items[0][quantity]" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5" placeholder="0">
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Harga Total Item (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                    <input type="number" name="items[0][price]" required class="w-full border-gray-300 rounded-lg text-sm pl-9 focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8" x-data="{ paymentMethod: 'cash' }">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-4">
                            
                            <div>
                                <input type="radio" name="payment_method" id="pm_cash" value="cash" x-model="paymentMethod" class="peer sr-only">
                                <label for="pm_cash" 
                                       class="block p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-200
                                              hover:bg-gray-50 hover:border-gray-300
                                              peer-checked:border-[#5f674d] peer-checked:bg-[#5f674d]/10 peer-checked:shadow-md relative">
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-700 text-sm peer-checked:text-[#5f674d]">Tunai (Cash)</div>
                                            <div class="text-xs text-gray-400">Dari Kas Besar</div>
                                        </div>
                                    </div>

                                    <div class="absolute top-3 right-3 text-[#5f674d] opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100">
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                            </div>

                            <div>
                                <input type="radio" name="payment_method" id="pm_credit" value="credit" x-model="paymentMethod" class="peer sr-only">
                                <label for="pm_credit" 
                                       class="block p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-200
                                              hover:bg-gray-50 hover:border-gray-300
                                              peer-checked:border-[#d4a056] peer-checked:bg-[#d4a056]/10 peer-checked:shadow-md relative">
                                    
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-700 text-sm peer-checked:text-[#d4a056]">Utang (Credit)</div>
                                            <div class="text-xs text-gray-400">Tempo Supplier</div>
                                        </div>
                                    </div>

                                    <div class="absolute top-3 right-3 text-[#d4a056] opacity-0 peer-checked:opacity-100 transition-opacity scale-0 peer-checked:scale-100">
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                            </div>

                        </div>

                        <!-- Due Date Input (Only show if Credit) -->
                        <div x-show="paymentMethod === 'credit'" class="mt-4" x-transition>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Jatuh Tempo (Due Date)</label>
                            <input type="date" name="due_date" 
                                   class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4a056]/20 focus:border-[#d4a056] transition font-medium"
                                   min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mb-5">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-[#5f674d] hover:bg-[#4b523d] text-green font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>