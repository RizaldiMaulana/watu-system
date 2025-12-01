<x-app-layout>
    <style>
        header.sticky { display: none !important; }
        main { padding: 0 !important; }
        .py-6 { padding-top: 0 !important; padding-bottom: 0 !important; }
    </style>

    <div class="fixed inset-0 left-64 bg-[#F9F7F2] flex z-10 font-sans h-screen w-[calc(100vw-16rem)]" 
         x-data="posSystem()">
        
        <div class="w-[420px] flex flex-col bg-white border-r border-gray-200 shadow-2xl z-20 h-full flex-shrink-0">
            
            <div class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-5 flex-shrink-0">
                <div class="flex items-center gap-3 text-[#5f674d] cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-xl transition">
                    <div class="w-9 h-9 rounded-full bg-[#5f674d]/10 flex items-center justify-center text-[#5f674d]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pelanggan</p>
                        <p class="text-sm font-bold text-[#2b2623] leading-tight">Walk-in Customer</p>
                    </div>
                </div>
                <button @click="cart = []" class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition" title="Reset Cart" x-show="cart.length > 0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-[#F9F7F2]/30 relative">
                <template x-if="cart.length === 0">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300 select-none pointer-events-none">
                        <svg class="w-20 h-20 mb-4 opacity-20 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-sm font-medium text-gray-400">Keranjang Kosong</span>
                    </div>
                </template>

                <ul class="divide-y divide-gray-100">
                    <template x-for="(item, index) in cart" :key="index">
                        <li class="group relative bg-white p-4 hover:bg-[#F9F7F2] transition-colors cursor-pointer border-l-4 border-transparent hover:border-[#5f674d]">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-[#2b2623] text-sm mb-1 font-serif" x-text="item.name"></h4>
                                    <div class="flex items-center text-xs text-gray-500 font-mono">
                                        <span class="bg-[#5f674d]/10 text-[#5f674d] px-2 py-0.5 rounded font-bold mr-2" x-text="item.qty + 'x'"></span>
                                        <span x-text="'@ ' + formatRupiah(item.price)"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-[#2b2623] text-sm" x-text="formatRupiah(item.price * item.qty)"></div>
                                    <button @click.stop="removeItem(index)" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase tracking-wider opacity-0 group-hover:opacity-100 transition mt-2">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="bg-white border-t border-gray-200 p-5 z-30 flex-shrink-0 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-xs text-gray-400">Termasuk Pajak</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-black text-[#5f674d] tracking-tight font-serif" x-text="formatRupiah(total)"></div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2">
                    <button class="col-span-1 h-10 rounded-lg bg-[#F0EFE9] text-[#5f674d] font-bold text-xs hover:bg-[#e0dfd9]">Qty</button>
                    <button class="col-span-1 h-10 rounded-lg bg-[#F0EFE9] text-[#5f674d] font-bold text-xs hover:bg-[#e0dfd9]">Disc</button>
                    <button class="col-span-1 h-10 rounded-lg bg-[#F0EFE9] text-[#5f674d] font-bold text-xs hover:bg-[#e0dfd9]">Price</button>
                    <button class="col-span-1 h-10 rounded-lg bg-red-50 text-red-500 font-bold hover:bg-red-100 flex items-center justify-center" @click="if(cart.length>0) removeItem(cart.length-1)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"></path></svg>
                    </button>

                    <button @click="showPaymentModal = true" 
                            :disabled="cart.length === 0"
                            class="col-span-4 h-14 mt-2 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex justify-between items-center px-6">
                        <span class="font-serif tracking-wide">Bayar Sekarang</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-[#F9F7F2] relative h-full min-w-0">
            <div class="h-20 px-6 flex items-center justify-between gap-6 bg-[#F9F7F2]/95 backdrop-blur-md border-b border-[#e5e7eb] z-10 flex-shrink-0">
                <div class="flex-1 flex gap-2 overflow-x-auto no-scrollbar py-2">
                    <button @click="filterCategory('all')" :class="selectedCategory === 'all' ? 'bg-[#5f674d] text-white shadow-md' : 'bg-white text-gray-500 hover:text-[#5f674d] shadow-sm'" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all">Semua</button>
                    @foreach($categories as $cat)
                    <button @click="filterCategory('{{ $cat }}')" :class="selectedCategory === '{{ $cat }}' ? 'bg-[#5f674d] text-white shadow-md' : 'bg-white text-gray-500 hover:text-[#5f674d] shadow-sm'" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all capitalize">{{ str_replace('_', ' ', $cat) }}</button>
                    @endforeach
                </div>
                <div class="w-72 relative">
                    <input type="text" x-model="search" placeholder="Cari menu..." class="w-full pl-10 pr-4 py-2.5 rounded-full border-none bg-white shadow-sm text-sm focus:ring-2 focus:ring-[#5f674d]">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex-1 p-6 custom-scrollbar" style="overflow-y: auto;">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 pb-24">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" class="bg-white rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-xl hover:border-[#5f674d]/50 hover:-translate-y-1 transition-all duration-300 flex flex-col h-64 overflow-hidden group">
                            <div class="h-36 w-full bg-[#F0EFE9] relative overflow-hidden flex items-center justify-center">
                                <img :src="product.image ? '/storage/' + product.image : 'https://placehold.co/400x300/F0EFE9/5f674d?text=' + product.name.substring(0,1).toUpperCase()" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 group-hover:opacity-100">
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-md shadow-sm flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full" :class="product.stock > 5 ? 'bg-green-500' : 'bg-red-500'"></div>
                                    <span class="text-[10px] font-bold text-gray-600" x-text="product.stock"></span>
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div><h3 class="font-serif font-bold text-[#2b2623] text-sm leading-tight group-hover:text-[#5f674d] transition-colors line-clamp-2" x-text="product.name"></h3></div>
                                <div class="flex justify-between items-end mt-2">
                                    <span class="text-[#5f674d] font-bold text-lg" x-text="formatRupiah(product.price)"></span>
                                    <div class="w-8 h-8 rounded-full bg-[#5f674d] text-white flex items-center justify-center shadow-lg transform translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="showPaymentModal" 
             style="display: none;"
             class="flex relative items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity p-4">
            
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all"
                 @click.away="showPaymentModal = false">
                
                <div class="bg-[#5f674d] p-5 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg font-serif tracking-wide">Metode Pembayaran</h3>
                    <button @click="showPaymentModal = false" type="button" class="hover:bg-white/20 p-1 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8">
                    <div class="text-center mb-8">
                        <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Harus Dibayar</p>
                        <h2 class="text-5xl font-black text-[#2b2623]" x-text="formatRupiah(total)"></h2>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <button type="button" @click="paymentMethod = 'Cash'; payAmount = 0" :class="paymentMethod === 'Cash' ? 'bg-[#5f674d]/10 border-[#5f674d] text-[#5f674d] ring-2 ring-[#5f674d]' : 'border-gray-200 text-gray-400 hover:border-gray-300'" class="flex flex-col items-center justify-center py-4 border-2 rounded-xl transition-all group">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="font-bold text-xs uppercase tracking-wider">Tunai</span>
                        </button>
                        <button type="button" @click="paymentMethod = 'QRIS'; payAmount = total" :class="paymentMethod === 'QRIS' ? 'bg-[#5f674d]/10 border-[#5f674d] text-[#5f674d] ring-2 ring-[#5f674d]' : 'border-gray-200 text-gray-400 hover:border-gray-300'" class="flex flex-col items-center justify-center py-4 border-2 rounded-xl transition-all group">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            <span class="font-bold text-xs uppercase tracking-wider">QRIS</span>
                        </button>
                        <button type="button" @click="paymentMethod = 'Debit'; payAmount = total" :class="paymentMethod === 'Debit' ? 'bg-[#5f674d]/10 border-[#5f674d] text-[#5f674d] ring-2 ring-[#5f674d]' : 'border-gray-200 text-gray-400 hover:border-gray-300'" class="flex flex-col items-center justify-center py-4 border-2 rounded-xl transition-all group">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <span class="font-bold text-xs uppercase tracking-wider">Debit</span>
                        </button>
                    </div>

                    <div x-show="paymentMethod === 'Cash'" class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute left-4 top-4 font-bold text-gray-400">Rp</span>
                            <input type="number" x-model="payAmount" class="w-full pl-12 pr-4 py-3.5 rounded-xl border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d] text-xl font-bold text-gray-800 shadow-sm" placeholder="0">
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                            <span class="text-sm font-bold text-gray-500">Kembalian</span>
                            <span class="text-xl font-black" :class="payAmount - total < 0 ? 'text-red-500' : 'text-green-600'" x-text="formatRupiah(payAmount - total)"></span>
                        </div>
                    </div>

                    <button type="button" @click="processPayment()" 
                            :disabled="paymentMethod === 'Cash' && payAmount < total"
                            :class="(paymentMethod === 'Cash' && payAmount < total) ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#5f674d] hover:bg-[#4b523d] shadow-xl hover:-translate-y-1'"
                            class="w-full py-4 text-white font-bold text-lg rounded-xl transition-all flex justify-center items-center gap-2">
                        <span>Selesaikan Transaksi</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function posSystem() {
            return {
                products: {{ \Illuminate\Support\Js::from($products) }},
                search: '',
                selectedCategory: 'all',
                cart: [],
                showPaymentModal: false,
                paymentMethod: 'Cash',
                payAmount: 0,

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchesSearch = p.name.toLowerCase().includes(this.search.toLowerCase());
                        const matchesCategory = this.selectedCategory === 'all' || p.category === this.selectedCategory;
                        return matchesSearch && matchesCategory;
                    });
                },
                get total() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
                filterCategory(cat) { this.selectedCategory = cat; },
                addToCart(product) {
                    let item = this.cart.find(i => i.id === product.id);
                    if (item) { item.qty++; } else {
                        this.cart.push({ id: product.id, name: product.name, price: parseFloat(product.price), qty: 1 });
                    }
                },
                removeItem(index) { this.cart.splice(index, 1); },
                formatRupiah(num) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num); },

                async processPayment() {
                    if (this.paymentMethod === 'Cash' && this.payAmount < this.total) { alert('Uang Kurang!'); return; }
                    
                    let formData = new FormData();
                    formData.append('cart', JSON.stringify(this.cart));
                    formData.append('total_amount', this.total);
                    formData.append('payment_method', this.paymentMethod);
                    formData.append('customer_name', 'Walk-in Customer');
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        let response = await fetch('{{ route("pos.store") }}', { method: 'POST', body: formData });
                        let result = await response.json();
                        if (result.status === 'success') {
                            this.showPaymentModal = false;
                            window.open('/pos/print/' + result.transaction_id, '_blank', 'width=300,height=500');
                            this.cart = [];
                            this.payAmount = 0;
                            alert('Sukses!');
                        } else { alert(result.message); }
                    } catch (error) { console.error(error); alert('Error'); }
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</x-app-layout>