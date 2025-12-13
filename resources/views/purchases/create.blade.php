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

    <div class="max-w-4xl mx-auto" x-data="purchaseForm()">
        
        <!-- STEP INDICATOR -->
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step === 1 ? 'bg-[#5f674d] text-white' : 'bg-green-100 text-green-700'">1</div>
                <span class="ml-2 text-sm font-bold text-gray-700">Input Data</span>
            </div>
            <div class="w-16 h-1 bg-gray-200 mx-4 rounded"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors"
                     :class="step === 2 ? 'bg-[#5f674d] text-white' : 'bg-gray-200 text-gray-400'">2</div>
                <span class="ml-2 text-sm font-bold" :class="step === 2 ? 'text-gray-700' : 'text-gray-400'">Preview & Sign</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- STEP 1: INPUT FORM -->
            <div x-show="step === 1" x-transition>
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
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl" role="alert">
                            <strong class="font-bold">Terdapat Kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="po-form" action="{{ route('purchases.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="group">
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Transaksi</label>
                                <div class="relative">
                                    <input type="date" name="transaction_date" x-model="formData.transaction_date"
                                           class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#5f674d]/20 focus:border-[#5f674d] transition font-medium">
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Supplier</label>
                                <div class="relative">
                                    <select name="supplier_id" x-model="formData.supplier_id"
                                            class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#5f674d]/20 focus:border-[#5f674d] appearance-none cursor-pointer font-medium">
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" data-name="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DYNAMIC ITEMS COMPONENT -->
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 mb-8 relative">
                            <div class="absolute top-0 left-0 bg-[#5f674d] text-white text-[10px] font-bold px-3 py-1 rounded-br-lg rounded-tl-lg">
                                ITEM PEMBELIAN
                            </div>

                            <template x-for="(item, index) in items" :key="index">
                                <div class="grid grid-cols-12 gap-4 mt-4 border-b pb-4 last:border-b-0 last:pb-0">
                                    <div class="col-span-12 md:col-span-5">
                                        <select :name="`items[${index}][item_id]`" x-model="item.id" @change="updateItemDetails(index, $event.target.value)"
                                                class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5">
                                            <option value="" disabled selected>-- Pilih Item --</option>
                                            <optgroup label="Bahan Baku">
                                                @foreach($ingredients as $ing)
                                                    <option value="ing_{{ $ing->id }}" data-type="ingredient" data-price="{{ $ing->cost_price }}" data-name="{{ $ing->name }}">
                                                        {{ $ing->name }} (Stok: {{ $ing->stock }} {{ $ing->unit }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Produk Jual">
                                                @foreach($products as $prod)
                                                    <option value="prod_{{ $prod->id }}" data-type="product" data-price="{{ $prod->cost_price }}" data-name="{{ $prod->name }}">
                                                        {{ $prod->name }} (Stok: {{ $prod->stock }} {{ $prod->unit }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        <!-- Hidden Inputs for Backend Logic -->
                                        <input type="hidden" :name="`items[${index}][ingredient_id]`" :value="item.type === 'ingredient' ? item.real_id : ''">
                                        <input type="hidden" :name="`items[${index}][product_id]`" :value="item.type === 'product' ? item.real_id : ''">
                                    </div>

                                    <div class="col-span-6 md:col-span-2">
                                        <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" required 
                                               class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5" placeholder="Qty">
                                    </div>

                                    <div class="col-span-6 md:col-span-4">
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">Rp</span>
                                            <input type="number" :name="`items[${index}][price]`" x-model="item.price" required 
                                                   class="w-full border-gray-300 rounded-lg text-sm pl-9 focus:ring-[#5f674d] focus:border-[#5f674d] py-2.5" placeholder="0">
                                        </div>
                                    </div>
                                    
                                    <div class="col-span-12 md:col-span-1 flex items-center">
                                         <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700" x-show="items.length > 1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                         </button>
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" @click="addItem()" class="mt-4 text-xs font-bold text-[#5f674d] hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Item Baris
                            </button>
                        </div>

                        <div class="mb-8">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" name="payment_method" id="pm_cash" value="cash" x-model="formData.payment_method" class="peer sr-only">
                                    <label for="pm_cash" 
                                           class="block p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-200
                                                  hover:bg-gray-50 hover:border-gray-300
                                                  peer-checked:border-[#5f674d] peer-checked:bg-[#5f674d]/10 peer-checked:shadow-md relative">
                                        <div class="font-bold text-gray-700 text-sm peer-checked:text-[#5f674d]">Tunai (Cash)</div>
                                        <div class="text-xs text-gray-400">Dari Kas Besar</div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_credit" value="credit" x-model="formData.payment_method" class="peer sr-only">
                                    <label for="pm_credit" 
                                           class="block p-4 rounded-xl border-2 border-gray-200 cursor-pointer transition-all duration-200
                                                  hover:bg-gray-50 hover:border-gray-300
                                                  peer-checked:border-[#d4a056] peer-checked:bg-[#d4a056]/10 peer-checked:shadow-md relative">
                                        <div class="font-bold text-gray-700 text-sm peer-checked:text-[#d4a056]">Utang (Credit)</div>
                                        <div class="text-xs text-gray-400">Tempo Supplier</div>
                                    </label>
                                </div>
                            </div>

                            <!-- TERMS & DUE DATE (Credit Only) -->
                            <div x-show="formData.payment_method === 'credit'" class="mt-4 grid grid-cols-2 gap-4" x-transition>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Termin</label>
                                    <select name="payment_term" x-model="formData.payment_term" @change="calculateDueDate()"
                                            class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4a056]/20 focus:border-[#d4a056] appearance-none font-medium">
                                        <option value="net7">Net 7 (7 Hari)</option>
                                        <option value="net14">Net 14 (14 Hari)</option>
                                        <option value="net30">Net 30 (30 Hari)</option>
                                        <option value="manual">Manual Input</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">Jatuh Tempo</label>
                                    <input type="date" name="due_date" x-model="formData.due_date"
                                           class="w-full bg-white border border-gray-300 text-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4a056]/20 focus:border-[#d4a056] transition font-medium"
                                           :readonly="formData.payment_term !== 'manual'"
                                           :class="{'bg-gray-100': formData.payment_term !== 'manual', 'bg-white': formData.payment_term === 'manual'}">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mb-5">
                            <button type="button" @click="goToPreview()" class="px-8 py-3 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <span>Preview PO</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- STEP 2: PREVIEW DOCUMENT -->
            <div x-show="step === 2" x-transition class="p-8 bg-gray-50">
                <div class="max-w-3xl mx-auto bg-white p-8 shadow-lg rounded-none relative" id="printable-area">
                    <!-- PO Layout (Similar to Print view) -->
                    <div class="flex justify-between items-start mb-8 border-b pb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">PURCHASE ORDER</h1>
                            <p class="text-gray-500 text-sm mt-1">Watu Coffee System</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded inline-block">PREVIEW / DRAFT</p>
                            <p class="text-gray-500 text-sm mt-1" x-text="formatDate(formData.transaction_date)"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Supplier</h3>
                            <p class="font-bold text-gray-800" x-text="getSupplierName(formData.supplier_id)"></p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</h3>
                            <p class="font-bold text-gray-800 uppercase" x-text="formData.payment_method"></p>
                            <p class="text-sm text-red-500" x-show="formData.payment_method === 'credit'" x-text="'Jatuh Tempo: ' + formatDate(formData.due_date)"></p>
                        </div>
                    </div>

                    <table class="w-full mb-8">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 text-sm font-bold text-gray-600">Item</th>
                                <th class="text-center py-3 text-sm font-bold text-gray-600">Qty</th>
                                <th class="text-right py-3 text-sm font-bold text-gray-600">Harga</th>
                                <th class="text-right py-3 text-sm font-bold text-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="item in items">
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 text-sm text-gray-800 font-medium" x-text="item.name"></td>
                                    <td class="py-3 text-sm text-center text-gray-800" x-text="item.quantity"></td>
                                    <td class="py-3 text-sm text-right text-gray-800" x-text="formatCurrency(item.price)"></td>
                                    <td class="py-3 text-sm text-right text-gray-800 font-bold" x-text="formatCurrency(item.quantity * item.price)"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="py-4 text-right text-gray-600 font-bold">TOTAL ESTIMASI</td>
                                <td class="py-4 text-right text-xl font-bold text-gray-900" x-text="formatCurrency(calculateTotal())"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Signature Block -->
                    <div class="grid grid-cols-2 gap-8 mt-12 pt-8">
                        <div class="text-center">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-4">Disetujui Oleh</p>
                            
                            <!-- Signature Display -->
                            <div class="h-24 flex items-center justify-center">
                                @if(Auth::user()->signature)
                                    <img src="{{ asset('storage/' . Auth::user()->signature) }}" class="h-20" alt="Signature">
                                @else
                                    <div class="border-2 border-dashed border-gray-300 p-4 rounded text-xs text-gray-400">
                                        (Belum ada Tanda Tangan Digital) <br>
                                        <a href="{{ route('users.edit', Auth::user()->id) }}" target="_blank" class="text-blue-500 underline">Upload di Profil</a>
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-gray-300 w-32 mx-auto mt-2"></div>
                            <p class="text-sm font-bold text-gray-600 mt-2">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">Manager</p>
                        </div>
                    </div>
                </div>

                <!-- Preview Actions -->
                <div class="mt-8 flex justify-between items-center max-w-3xl mx-auto">
                    <button type="button" @click="step = 1" class="text-gray-500 font-bold text-sm hover:text-gray-800">
                        &larr; Edit Kembali
                    </button>
                    <div class="flex gap-3">
                         <button type="button" @click="printPreview()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Print / PDF
                         </button>
                         <button type="button" @click="shareWhatsApp()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Kirim WA
                         </button>
                         <button type="button" @click="submitForm()" class="px-6 py-2 bg-[#5f674d] text-white rounded-lg hover:bg-[#4b523d] text-sm font-bold shadow-lg flex items-center gap-2">
                            <span>Konfirmasi & Simpan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                         </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function purchaseForm() {
            return {
                step: 1,
                formData: {
                    transaction_date: '{{ date('Y-m-d') }}',
                    supplier_id: '{{ $suppliers->first()->id ?? '' }}',
                    payment_method: 'cash',
                    payment_term: 'net7',
                    due_date: '{{ date('Y-m-d', strtotime('+7 days')) }}',
                },
                items: [
                    @if(isset($prefilledItem))
                    {
                        id: '{{ $prefilledItem['type'] == 'ingredient' ? 'ing_' : 'prod_' }}{{ $prefilledItem['id'] }}',
                        real_id: '{{ $prefilledItem['id'] }}',
                        type: '{{ $prefilledItem['type'] }}',
                        name: '{{ $prefilledItem['name'] }}',
                        quantity: 10,
                        price: {{ $prefilledItem['price'] ?? 0 }}
                    }
                    @else
                    { id: '', real_id: '', type: '', name: '', quantity: 1, price: 0 }
                    @endif
                ],
                
                addItem() {
                    this.items.push({ id: '', real_id: '', type: '', name: '', quantity: 1, price: 0 });
                },
                
                removeItem(index) {
                    if(this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateItemDetails(index, value) {
                    let select = document.querySelector(`select[name="items[${index}][item_id]"]`);
                    let option = select.options[select.selectedIndex];
                    
                    if(option) {
                        let type = option.getAttribute('data-type');
                        let price = option.getAttribute('data-price');
                        let name = option.getAttribute('data-name');
                        
                        this.items[index].type = type;
                        this.items[index].real_id = value.split('_')[1];
                        this.items[index].price = price;
                        this.items[index].name = name;
                    }
                },
                
                calculateDueDate() {
                    let base = new Date(this.formData.transaction_date);
                    let add = 0;
                    if(this.formData.payment_term === 'net7') add = 7;
                    if(this.formData.payment_term === 'net14') add = 14;
                    if(this.formData.payment_term === 'net30') add = 30;
                    
                    if(add > 0) {
                        base.setDate(base.getDate() + add);
                        this.formData.due_date = base.toISOString().split('T')[0];
                    }
                },
                
                goToPreview() {
                    // Validations usually handled by required attr, but good to check
                    if(this.items.length === 0 || !this.items[0].id) {
                        alert('Mohon pilih minimal satu item.');
                        return;
                    }
                    this.step = 2;
                    window.scrollTo(0, 0);
                },

                formatDate(dateStr) {
                    if(!dateStr) return '-';
                    let date = new Date(dateStr);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                },
                
                formatCurrency(val) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
                },
                
                calculateTotal() {
                    return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
                },
                
                getSupplierName(id) {
                    let select = document.querySelector('select[name="supplier_id"]');
                    if(!select) return '-';
                    for(let opt of select.options) {
                        if(opt.value == id) return opt.text;
                    }
                    return '-';
                },

                submitForm() {
                    document.getElementById('po-form').submit();
                },
                
                printPreview() {
                    let printContent = document.getElementById('printable-area').innerHTML;
                    let originalContent = document.body.innerHTML;
                    
                    document.body.innerHTML = printContent;
                    window.print();
                    document.body.innerHTML = originalContent;
                    location.reload(); // Reload to restore state listeners
                },
                
                shareWhatsApp() {
                    let supplier = this.getSupplierName(this.formData.supplier_id);
                    let total = this.formatCurrency(this.calculateTotal());
                    let text = `Halo ${supplier}, Berikut adalah PO baru:\n\n`;
                    this.items.forEach(item => {
                        text += `- ${item.quantity}x ${item.name}\n`;
                    });
                    text += `\nTotal: ${total}`;
                    text += `\n\nMohon diproses. Terima kasih.`;
                    
                    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
                }
            }
        }
    </script>
</x-app-layout>