<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                {{ __('Input Penerimaan Barang') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('goods-receipt.store-manual') }}" enctype="multipart/form-data">
                @csrf

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <x-input-label for="supplier_id" :value="__('Supplier')" />
                        <select name="supplier_id" id="supplier_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#5f674d] focus:ring-[#5f674d]" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="transaction_date" :value="__('Tanggal Terima')" />
                        <x-text-input id="transaction_date" class="block mt-1 w-full" type="date" name="transaction_date" :value="date('Y-m-d')" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                     <!-- Upload Invoice -->
                    <div>
                        <x-input-label for="proof_file" :value="__('Foto / Scan Invoice Fisik')" />
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition mt-1">
                            <input type="file" name="proof_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-xs text-gray-500">Klik atau drag file invoice di sini</p>
                        </div>
                    </div>
                     <!-- Payment Method -->
                     <div>
                        <x-input-label for="payment_method" :value="__('Metode Pembayaran (Sesuai Invoice)')" />
                        <select name="payment_method" id="payment_method" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#5f674d] focus:ring-[#5f674d] mt-1" onchange="toggleDueDate()" required>
                            <option value="cash">Cash / Tunai / Transfer</option>
                            <option value="credit">Credit / Utang (Tempo)</option>
                        </select>
                        <div id="due_date_container" class="hidden mt-4">
                            <x-input-label for="due_date" :value="__('Jatuh Tempo (Due Date)')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" />
                        </div>
                    </div>
                </div>

                <!-- ITEM INPUT (AlpineJS) -->
                <div x-data="itemManager()" class="mb-8">
                    <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Item Barang</h3>
                    
                    <template x-for="(item, index) in items" :key="index">
                        <div class="grid grid-cols-12 gap-4 mb-4 items-end bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <!-- Type Selection -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Item</label>
                                <select :name="'items['+index+'][type_id]'" x-model="item.selectedId" @change="updateType(index)" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                                    <option value="">-- Pilih Item --</option>
                                    <optgroup label="Bahan Baku (Ingredients)">
                                        @foreach($ingredients as $ing)
                                            <option value="ing_{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Produk Jual (Resale)">
                                        @foreach($products as $prod)
                                            <option value="prod_{{ $prod->id }}">{{ $prod->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <!-- Hidden Inputs for Backend Logic -->
                                <input type="hidden" :name="'items['+index+'][ingredient_id]'" :value="item.ingredient_id">
                                <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                            </div>

                            <div class="col-span-6 md:col-span-3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Qty Diterima</label>
                                <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" placeholder="0">
                            </div>

                            <div class="col-span-6 md:col-span-4">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Harga Satuan (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                    <input type="number" :name="'items['+index+'][price]'" x-model="item.price" required class="w-full border-gray-300 rounded-lg text-sm pl-9 focus:ring-[#5f674d] focus:border-[#5f674d] py-2" placeholder="0">
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-1 text-center">
                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 bg-red-100 p-2 rounded-lg" title="Hapus Item">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="mt-2 px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Item Lain
                    </button>
                    
                    <!-- Total Readonly -->
                    <div class="mt-6 p-4 bg-gray-100 rounded-xl text-right">
                        <span class="text-gray-600 font-medium mr-4">Total Estimasi:</span>
                        <span class="text-2xl font-bold text-[#2b2623]" x-text="'Rp ' + calculateTotal().toLocaleString('id-ID')">Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('goods-receipt.index') }}" class="px-6 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-bold text-sm transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDueDate() {
            const method = document.getElementById('payment_method').value;
            const container = document.getElementById('due_date_container');
            const input = document.getElementById('due_date');
            
            if (method === 'credit') {
                container.classList.remove('hidden');
                input.required = true;
            } else {
                container.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }

        function itemManager() {
            return {
                items: [
                    { selectedId: '', ingredient_id: '', product_id: '', quantity: 1, price: 0 }
                ],
                addItem() {
                    this.items.push({ selectedId: '', ingredient_id: '', product_id: '', quantity: 1, price: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                updateType(index) {
                    const val = this.items[index].selectedId;
                    if (val.startsWith('ing_')) {
                        this.items[index].ingredient_id = val.replace('ing_', '');
                        this.items[index].product_id = null;
                    } else if (val.startsWith('prod_')) {
                        this.items[index].product_id = val.replace('prod_', '');
                        this.items[index].ingredient_id = null;
                    } else {
                        this.items[index].ingredient_id = null;
                        this.items[index].product_id = null;
                    }
                },
                calculateTotal() {
                    return this.items.reduce((total, item) => {
                        return total + (item.quantity * item.price);
                    }, 0);
                }
            }
        }
    </script>
</x-app-layout>
