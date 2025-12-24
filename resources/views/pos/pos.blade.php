<x-app-layout>
    <style>
        header { display: none !important; }
        .min-h-screen { height: 100vh; overflow: hidden; }
        .main-content { padding: 0 !important; }
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="posSystem">
    <div class="fixed top-0 right-0 bottom-0 left-64 bg-[#F9F7F2] flex z-40 font-sans h-screen w-[calc(100vw-16rem)]">
        
        <div class="w-[420px] flex flex-col bg-white border-r border-gray-200 shadow-2xl z-20 h-full flex-shrink-0">
            <div class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-5 flex-shrink-0">
                <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg flex-1 mr-2">
                    <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#5f674d] shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div class="leading-tight w-full">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pelanggan</p>
                        <select x-model="selectedCustomerId" class="bg-transparent border-none p-0 text-sm font-bold text-[#2b2623] focus:ring-0 w-full cursor-pointer">
                            <option value="">Walk-in Customer</option>
                            <template x-for="cust in customers" :key="cust.id">
                                <option :value="cust.id" x-text="cust.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <button @click="cart = []" class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition" title="Reset">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar bg-[#F9F7F2]/30 relative">
                <template x-if="cart.length === 0">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-300 select-none">
                        <svg class="w-20 h-20 mb-4 opacity-20 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="text-sm font-medium text-gray-400">Keranjang Kosong</span>
                    </div>
                </template>
                <ul class="divide-y divide-gray-100">
                    <template x-for="(item, index) in cart" :key="index">
                        <li class="group relative bg-white p-3 hover:bg-[#F9F7F2] transition-colors cursor-pointer border-l-4 border-transparent hover:border-[#5f674d]">
                            <div class="flex justify-between items-start gap-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-[#2b2623] text-sm mb-1 font-serif" x-text="item.name"></h4>
                                    <div class="flex items-center text-xs text-gray-500 font-mono gap-2">
                                        <div class="flex items-center border rounded bg-gray-50">
                                            <button @click.stop="decreaseQty(index)" class="px-2 hover:bg-gray-200 text-gray-600 font-bold">-</button>
                                            <span class="px-2 font-bold text-[#5f674d]" x-text="item.qty"></span>
                                            <button @click.stop="increaseQty(index)" class="px-2 hover:bg-gray-200 text-gray-600 font-bold">+</button>
                                        </div>
                                        <span x-text="'@ ' + formatRupiah(item.price)"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-[#2b2623] text-sm" x-text="formatRupiah(item.price * item.qty)"></div>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="bg-white border-t border-gray-200 p-5 z-30 flex-shrink-0 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
                <div class="flex justify-between items-end mb-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total</p>
                        <p class="text-xs text-gray-400">Termasuk Pajak</p>
                    </div>
                    <div class="text-3xl font-black text-[#5f674d] tracking-tight font-serif" x-text="formatRupiah(total)"></div>
                </div>

                <button @click.stop="openPaymentModal()" 
                        :disabled="cart.length === 0"
                        class="w-full py-4 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold text-lg rounded-xl shadow-lg shadow-[#5f674d]/30 transition-all active:scale-95 disabled:opacity-50 flex justify-center items-center gap-2">
                    <span>Bayar Sekarang</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-[#F9F7F2] relative h-full min-w-0">
            <div class="h-20 px-6 flex items-center justify-between gap-6 bg-[#F9F7F2]/95 backdrop-blur-md border-b border-[#e5e7eb] z-10 flex-shrink-0 sticky top-0">
                <div class="flex-1 flex gap-2 overflow-x-auto no-scrollbar py-2">
                    <button @click="filterCategory('all')" :class="selectedCategory === 'all' ? 'bg-[#5f674d] text-white shadow-md' : 'bg-white text-gray-500 hover:text-[#5f674d] shadow-sm'" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all">Semua</button>
                    @foreach($categories as $cat)
                    <button @click="filterCategory({{ $cat->id }})" :class="selectedCategory === {{ $cat->id }} ? 'bg-[#5f674d] text-white shadow-md' : 'bg-white text-gray-500 hover:text-[#5f674d] shadow-sm'" class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all capitalize">{{ $cat->name }}</button>
                    @endforeach
                </div>
                <div class="w-64 relative">
                    <input type="text" x-model="search" placeholder="Cari..." class="w-full pl-10 pr-4 py-2.5 rounded-full border-none bg-white shadow-sm text-sm focus:ring-2 focus:ring-[#5f674d]">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex-1 p-6 custom-scrollbar" style="overflow-y: auto;">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 pb-24">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" class="bg-white rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:shadow-xl hover:border-[#5f674d]/50 hover:-translate-y-1 transition-all duration-300 flex flex-col h-60 overflow-hidden group">
                            <div class="h-32 w-full bg-[#F0EFE9] relative overflow-hidden flex items-center justify-center">
                                <img :src="product.image ? '/storage/' + product.image : 'https://placehold.co/400x300/F0EFE9/5f674d?text=' + product.name.substring(0,1).toUpperCase()" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90 group-hover:opacity-100">
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-md shadow-sm flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full" :class="product.stock > 5 ? 'bg-green-500' : 'bg-red-500'"></div>
                                    <span class="text-[10px] font-bold text-gray-600" x-text="product.stock"></span>
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <h3 class="font-serif font-bold text-[#2b2623] text-sm leading-tight group-hover:text-[#5f674d] transition-colors line-clamp-2" x-text="product.name"></h3>
                                <div class="flex justify-between items-end">
                                    <span class="text-[#5f674d] font-bold text-lg" x-text="formatRupiah(product.price)"></span>
                                    <div class="w-8 h-8 rounded-full bg-[#5f674d] text-white flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all translate-y-4 group-hover:translate-y-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>


    </div>
    <template x-teleport="body">
        <div x-show="showPaymentModal" 
             x-transition.opacity
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
             style="display: none; z-index: 9999;">
            
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all"
                 @click.away="showPaymentModal = false">
                
                <div class="bg-[#5f674d] p-5 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg font-serif tracking-wide">Metode Pembayaran</h3>
                    <button @click="showPaymentModal = false" class="hover:bg-white/20 p-1 rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="p-8 max-h-[80vh] overflow-y-auto">
                    <!-- Advanced Sales Options -->
                    <div class="mb-6 space-y-4 border-b border-gray-100 pb-6">
                        <div class="flex gap-4">
                            <!-- Discount Selection -->
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Promo / Diskon</label>
                                <select x-model="selectedPromotionId" @change="applyPromotion()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#5f674d]">
                                    <option value="">-- Tidak Ada Promo --</option>
                                    <template x-for="promo in promotions" :key="promo.id">
                                        <option :value="promo.id" x-text="promo.name + ' (' + (promo.type === 'percentage' ? promo.value + '%' : 'Rp ' + Number(promo.value).toLocaleString('id-ID')) + ')'"></option>
                                    </template>
                                </select>
                                <div x-show="discountAmount > 0" class="text-xs text-green-600 mt-1 font-bold">
                                    Potongan: Rp <span x-text="formatRupiah(discountAmount)"></span>
                                </div>
                            </div>
                            
                            <!-- Taxes & Charges Info -->
                            <div class="flex flex-col gap-2 pt-6">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="tax" x-model="taxEnabled" class="rounded text-[#5f674d] focus:ring-[#5f674d]">
                                    <label for="tax" class="text-sm font-bold text-gray-700 select-none">Aktifkan Pajak & Layanan</label>
                                </div>
                                
                                <template x-if="taxEnabled">
                                    <div class="pl-6 text-xs text-gray-500 space-y-1">
                                        <template x-for="tax in activeTaxes" :key="tax.id">
                                            <div class="flex justify-between">
                                                <span x-text="tax.name + ' (' + tax.rate + '%)'"></span>
                                                <span x-text="formatRupiah(calculateTaxAmount(tax))"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                         <div class="flex gap-4 items-center">
                            <!-- Complimentary Toggle -->
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="comp" x-model="isComplimentary" @change="if(isComplimentary) { discountAmount=0; selectedPromotionId=''; }" class="rounded text-purple-600 focus:ring-purple-600">
                                <label for="comp" class="text-sm font-bold text-purple-700 select-none">Complimentary (Gratis)</label>
                            </div>
                             <!-- Notes -->
                            <div class="flex-1">
                                <input type="text" x-model="notes" placeholder="Catatan Transaksi..." class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#5f674d]">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Harus Dibayar</p>
                        <h2 class="text-5xl font-black text-[#2b2623]" x-text="formatRupiah(total)"></h2>
                        <div x-show="taxEnabled" class="text-xs text-gray-400 mt-1">Termasuk Pajak</div>
                    </div>

                    <div class="mb-6 space-y-3">
                        <template x-for="(payment, index) in payments" :key="index">
                            <div class="flex gap-2 items-end">
                                <div class="w-1/3">
                                    <label class="block text-xs text-gray-400 mb-1" x-show="index===0">Metode</label>
                                    <select x-model="payment.method" class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#5f674d]">
                                        <option value="Cash">Tunai</option>
                                        <option value="QRIS">QRIS</option>
                                        <option value="Debit">Debit</option>
                                        <option value="Transfer">Transfer</option>
                                        <template x-if="isRoasteryCustomer()">
                                            <option value="Credit" class="text-red-600 font-bold">Credit / Tempo</option>
                                        </template>
                                    </select>
                                    <!-- Term Input for Credit -->
                                    <div x-show="payment.method === 'Credit'" class="mt-1">
                                        <select x-model="paymentTerm" class="w-full text-xs rounded border-gray-200 bg-red-50 text-red-800">
                                            <option value="net7">Net 7 Days</option>
                                            <option value="net14">Net 14 Days</option>
                                            <option value="net30">Net 30 Days</option>
                                            <option value="net60">Net 60 Days</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-400 mb-1" x-show="index===0">Nominal</label>
                                    <input type="number" x-model="payment.amount" class="w-full rounded-lg border-gray-300 text-sm font-bold focus:ring-[#5f674d]">
                                </div>
                                <div>
                                    <button @click="removePayment(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg h-[42px] w-[42px] flex items-center justify-center border border-transparent hover:border-red-200" x-show="payments.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                         <button @click="addPayment()" class="text-sm text-[#5f674d] font-bold hover:underline py-2">+ Tambah Pembayaran</button>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-500 text-sm">Total Bayar</span>
                            <span class="font-bold text-lg" x-text="formatRupiah(totalPaid)"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500" x-text="remainingDue > 0 ? 'Kurang Bayar' : 'Kembalian'"></span>
                            <span class="font-bold" :class="remainingDue > 0 ? 'text-red-600' : 'text-green-600'" x-text="remainingDue > 0 ? formatRupiah(remainingDue) : formatRupiah(changeAmount)"></span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button @click="processPayment()" 
                                :disabled="remainingDue > 0" 
                                class="w-full py-4 text-white font-bold text-lg rounded-xl transition-all flex justify-center items-center gap-2 shadow-xl"
                                :class="remainingDue > 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#5f674d] hover:bg-[#4b523d]'">
                            <span>Selesaikan Transaksi</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
    
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posSystem', () => ({
                products: {{ \Illuminate\Support\Js::from($products) }},
                promotions: {{ \Illuminate\Support\Js::from($promotions) }},
                taxes: {{ \Illuminate\Support\Js::from($taxes) }},
                search: '',
                customers: {{ \Illuminate\Support\Js::from($customers) }},
                selectedCustomerId: '', 
                selectedCategory: 'all',
                customerName: 'Walk-in Customer', // Display name
                taxEnabled: true,
                discountAmount: 0,
                selectedPromotionId: '',
                isComplimentary: false,
                notes: '',
                transactionUuid: '', 

                paymentTerm: 'net30', // Default term
                cart: [],
                payments: [],
                showPaymentModal: false,
                
                init() {
                    // Watch selection to update Name
                    this.$watch('selectedCustomerId', (val) => {
                        if(val) {
                            let c = this.customers.find(x => x.id == val);
                            this.customerName = c ? c.name : 'Walk-in Customer';
                        } else {
                            this.customerName = 'Walk-in Customer';
                        }
                    });
                    // Check if order data is passed from controller
                    let loadedOrder = {{ \Illuminate\Support\Js::from($loadedOrder ?? null) }};
                    if (loadedOrder) {
                        this.transactionUuid = loadedOrder.uuid;
                        this.customerName = loadedOrder.customer_name;
                        this.notes = loadedOrder.notes ?? '';
                        
                        // Map items to cart
                        if (loadedOrder.items) {
                            this.cart = loadedOrder.items.map(item => ({
                                id: item.product_id,
                                name: item.product ? item.product.name : 'Unknown Product',
                                price: Number(item.price),
                                qty: Number(item.quantity)
                            }));
                        }
                    }

                    this.$watch('cart', () => {
                        // Watch logic if needed
                    });
                },

                filterCategory(cat) { this.selectedCategory = cat; },
                
                get filteredProducts() {
                    return this.products.filter(p => (this.selectedCategory === 'all' || p.category_id == this.selectedCategory) && p.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                
                get subtotal() {
                     return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },
                
                get activeTaxes() {
                    return this.taxes.filter(t => t.is_active);
                },

                calculateTaxAmount(tax) {
                    let base = Math.max(0, this.subtotal - this.discountAmount);
                    if (tax.type === 'service_charge') {
                        return base * (tax.rate / 100);
                    } else if (tax.type === 'tax') {
                        // PB1 is calculated on (Base + Service Charge)
                        let serviceCharge = this.activeTaxes
                            .filter(t => t.type === 'service_charge')
                            .reduce((sum, t) => sum + (base * (t.rate / 100)), 0);
                        return (base + serviceCharge) * (tax.rate / 100);
                    }
                    return 0;
                },

                get total() { 
                    if (this.isComplimentary) return 0;
                    
                    let base = Math.max(0, this.subtotal - this.discountAmount);
                    
                    if (!this.taxEnabled) return base;

                    let totalTax = 0;
                    // Calculate Service Charges first
                    let serviceCharges = 0;
                    this.activeTaxes.filter(t => t.type === 'service_charge').forEach(t => {
                        serviceCharges += base * (t.rate / 100);
                    });

                    // Calculate PB1 (on Base + SC)
                    let taxBase = base + serviceCharges;
                    let pb1 = 0;
                    this.activeTaxes.filter(t => t.type === 'tax').forEach(t => {
                        pb1 += taxBase * (t.rate / 100);
                    });

                    return base + serviceCharges + pb1;
                },

                get totalPaid() {
                    return this.payments.reduce((sum, p) => sum + Number(p.amount), 0);
                },

                get remainingDue() {
                    return Math.max(0, this.total - this.totalPaid);
                },

                get changeAmount() {
                    return Math.max(0, this.totalPaid - this.total);
                },

                addPayment() {
                    let amount = this.remainingDue > 0 ? this.remainingDue : 0;
                    this.payments.push({ method: 'Cash', amount: amount, reference: '' });
                },

                removePayment(index) {
                    this.payments.splice(index, 1);
                },

                openPaymentModal() {
                    this.payments = [{ method: 'Cash', amount: this.total, reference: '' }];
                    this.showPaymentModal = true;
                },

                applyPromotion() {
                    if (!this.selectedPromotionId) {
                        this.discountAmount = 0;
                        return;
                    }
                    
                    const promo = this.promotions.find(p => p.id == this.selectedPromotionId);
                    if (promo) {
                        if (promo.type === 'percentage') {
                            this.discountAmount = this.subtotal * (promo.value / 100);
                        } else {
                            this.discountAmount = Number(promo.value);
                        }
                    }
                },
                
                isRoasteryCustomer() {
                    if (!this.selectedCustomerId) return false;
                    const c = this.customers.find(x => x.id == this.selectedCustomerId);
                    return c && c.type === 'roastery';
                },
                
                
                addToCart(product) {
                    let item = this.cart.find(i => i.id === product.id);
                    if (item) {
                        item.qty++; 
                    } else {
                        this.cart.push({ id: product.id, name: product.name, price: parseFloat(product.price), qty: 1 });
                    }
                },
                
                increaseQty(idx) { this.cart[idx].qty++; },
                
                decreaseQty(idx) { 
                    if(this.cart[idx].qty > 1) {
                        this.cart[idx].qty--; 
                    } else {
                        this.removeItem(idx); 
                    }
                },
                
                removeItem(idx) { this.cart.splice(idx, 1); },
                
                formatRupiah(num) { 
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num); 
                },

                async processPayment() {
                    if (this.remainingDue > 0) {
                        this.showToast('Gagal', 'Pembayaran belum lunas.', 'error');
                        return;
                    }

                    let formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}'); // Ensure CSRF token is always sent
                    
                    // Add items
                    formData.append('cart', JSON.stringify(this.cart));
                    formData.append('customer_id', this.selectedCustomerId); // NEW
                    formData.append('customer_name', this.customerName);
                    formData.append('payment_term', this.paymentTerm); // NEW
                    
                    // Add Transaction UUID if updating
                    if (this.transactionUuid) {
                        formData.append('transaction_uuid', this.transactionUuid);
                    }
                    
                    // Split Payments
                    formData.append('payments', JSON.stringify(this.payments));
                    let primaryMethod = this.payments.length > 1 ? 'Split' : (this.payments[0]?.method || 'Cash');
                    formData.append('payment_method', primaryMethod);

                    // Pricing
                    formData.append('discount_amount', this.discountAmount);
                    
                    let reason = '';
                    if (this.selectedPromotionId) {
                         const promo = this.promotions.find(p => p.id == this.selectedPromotionId);
                         if (promo) reason = promo.name;
                    }
                    formData.append('discount_reason', reason);
                    
                    formData.append('tax_rate', this.taxRate);
                    formData.append('tax_enabled', this.taxEnabled ? 1 : 0);
                    formData.append('is_complimentary', this.isComplimentary ? 1 : 0);
                    formData.append('notes', this.notes);
                    
                    formData.append('total_amount', this.total);
                    
                    try {
                        let res = await fetch('{{ route("pos.store") }}', { 
                            method: 'POST', 
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData 
                        });
                        
                        let result = await res.json();
                        
                        if (result.status === 'success') {
                            this.showPaymentModal = false;
                            // Print Receipt
                            const printWindow = window.open('/pos/print/' + result.transaction_uuid, '_blank', 'width=300,height=500');
                            if (printWindow) printWindow.focus();
                            
                            // Reset
                            this.cart = []; 
                            this.payAmount = 0;
                            this.showPaymentModal = false; 
                            this.paymentMethod = 'Cash';
                            this.discountAmount = 0;
                            this.discountReason = '';
                            this.isComplimentary = false;
                            this.notes = '';
                            
                            this.showToast('Sukses!', 'Transaksi Berhasil!', 'success'); 
                        } else {
                            this.showToast('Gagal!', result.message || 'Terjadi kesalahan.', 'error');
                        }
                    } catch (e) { 
                        console.error(e);
                        this.showToast('Error', 'Gagal memproses transaksi. Periksa koneksi atau coba lagi.', 'error'); 
                    }
                }
            }));
        });
    </script>
    <style>.custom-scrollbar::-webkit-scrollbar{width:5px}.custom-scrollbar::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:10px}[x-cloak]{display:none!important}</style>
</x-app-layout>