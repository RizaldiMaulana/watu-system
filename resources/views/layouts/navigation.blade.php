<nav :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        sidebarCollapsed ? 'md:w-20' : 'md:w-64'
     ]"
     class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-all duration-300 ease-in-out flex flex-col justify-between whitespace-nowrap overflow-hidden">
    
    <!-- Mobile Close Button -->
    <div class="absolute top-0 right-0 -mr-12 pt-2 md:hidden">
        <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
            <span class="sr-only">Close sidebar</span>
            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>


    
    <!-- Logo (Fixed at top) -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 flex-shrink-0" :class="sidebarCollapsed ? 'justify-center px-0' : ''">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/LOGO Produk.png') }}" alt="Logo" class="h-8 w-auto">
            <span class="font-bold text-lg text-gray-800 tracking-wide transition-opacity duration-300" 
                  :class="sidebarCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">
                WATU <span class="text-[#d4a056]">SYSTEM</span>
            </span>
        </a>
    </div>

    <!-- Scrollable Menu Section -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent hover:scrollbar-thumb-gray-400"
         style="scrollbar-width: thin;">
        <!-- Menu Title -->
        <div class="px-6 py-4" :class="sidebarCollapsed ? 'hidden' : ''">
            <h3 class="text-xs uppercase text-gray-400 font-bold tracking-wider">Menu Utama</h3>
        </div>

        <!-- Links -->
        <div class="flex flex-col gap-1 px-3 pb-4">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-[#5f674d] text-white shadow-md shadow-[#5f674d]/20' : 'text-gray-600 hover:bg-gray-50 hover:text-[#5f674d]' }}"
                x-bind:class="sidebarCollapsed ? 'justify-center' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Dashboard</span>
            </x-nav-link>

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'barista']))
            <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('pos.*') ? 'bg-[#5f674d] text-white shadow-md shadow-[#5f674d]/20' : 'text-gray-600 hover:bg-gray-50 hover:text-[#5f674d]' }}"
                x-bind:class="sidebarCollapsed ? 'justify-center' : ''">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Mesin Kasir</span>
            </x-nav-link>

            <!-- Sales Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('web-orders.*') || request()->routeIs('reservations.*') || request()->routeIs('sales.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Penjualan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('web-orders.index')" :active="request()->routeIs('web-orders.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('web-orders.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Pesanan Online</span>
                    </x-nav-link>

                    <x-nav-link :href="route('reservations.index')" :active="request()->routeIs('reservations.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reservations.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Reservasi Meja</span>
                    </x-nav-link>

                    <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('sales.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Riwayat Penjualan</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Procurement & Stock Dropdown -->
            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'roaster', 'barista']))
            <div x-data="{ open: {{ request()->routeIs('purchases.*') || request()->routeIs('reports.stock') || request()->routeIs('goods-receipt.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Stok & Pengadaan</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <!-- Real-time Stock: Admin, Manager, Owner, Roaster -->
                    @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'roaster']))
                    <x-nav-link :href="route('reports.stock')" :active="request()->routeIs('reports.stock')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.stock') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Stok Real-time</span>
                    </x-nav-link>
                    @endif

                    <!-- Input Pembelian: Admin, Manager, Owner -->
                    @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
                    <x-nav-link :href="route('purchases.create')" :active="request()->routeIs('purchases.create')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('purchases.create') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Input Pembelian</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('purchases.index')" :active="request()->routeIs('purchases.index')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('purchases.index') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Riwayat Pembelian PO</span>
                    </x-nav-link>
                    @endif

                    <!-- Penerimaan Barang: All (Admin, Manager, Owner, Roaster, Barista) -->
                    <x-nav-link :href="route('goods-receipt.index')" :active="request()->routeIs('goods-receipt.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('goods-receipt.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Penerimaan Barang</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Accounting Dropdown -->
            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
            <div x-data="{ open: {{ request()->routeIs('accounting.coa') || request()->routeIs('accounting.journal.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Akuntansi</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('accounting.coa')" :active="request()->routeIs('accounting.coa')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('accounting.coa') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Daftar Akun (CoA)</span>
                    </x-nav-link>

                    <x-nav-link :href="route('taxes.index')" :active="request()->routeIs('taxes.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('taxes.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Pajak & Layanan</span>
                    </x-nav-link>

                    <x-nav-link :href="route('accounting.journal.create')" :active="request()->routeIs('accounting.journal.create')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('accounting.journal.create') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Jurnal Manual</span>
                    </x-nav-link>
                </div>
            </div>

            @endif

            <!-- Reporting Dropdown -->
            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
            <div x-data="{ open: {{ request()->routeIs('accounting.reports.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Laporan & Analisa</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('accounting.reports.balance_sheet')" :active="request()->routeIs('accounting.reports.balance_sheet')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('accounting.reports.balance_sheet') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Neraca (Balance Sheet)</span>
                    </x-nav-link>

                    <x-nav-link :href="route('accounting.reports.income_statement')" :active="request()->routeIs('accounting.reports.income_statement')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('accounting.reports.income_statement') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Laba Rugi (P&L)</span>
                    </x-nav-link>

                    <x-nav-link :href="route('accounting.reports.cash_flow')" :active="request()->routeIs('accounting.reports.cash_flow')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('accounting.reports.cash_flow') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Arus Kas (Cash Flow)</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.index') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Laporan Jurnal</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- AP & AR Dropdown (NEW) -->
            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
            <div x-data="{ open: {{ request()->routeIs('ar.*') || request()->routeIs('accounting.reports.accounts_payable') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Hutang & Piutang</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('finance.index', ['tab' => 'receivables'])" :active="request()->fullUrlIs(route('finance.index', ['tab' => 'receivables']))" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('finance.index', ['tab' => 'receivables'])) ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Piutang Usaha (AR)</span>
                    </x-nav-link>

                    <x-nav-link :href="route('finance.index', ['tab' => 'payables'])" :active="request()->fullUrlIs(route('finance.index', ['tab' => 'payables']))" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->fullUrlIs(route('finance.index', ['tab' => 'payables'])) ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Hutang Usaha (AP)</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

            <!-- Data Master Dropdown -->
            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'roaster']))
            <div x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('recipes.*') || request()->routeIs('ingredients.*') || request()->routeIs('suppliers.*') || request()->routeIs('categories.*') || request()->routeIs('promotions.*') || request()->routeIs('settings.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="sidebarCollapsed ? sidebarCollapsed = false : open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group"
                        :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <span class="font-medium text-sm" :class="sidebarCollapsed ? 'hidden' : ''">Data Master</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open, 'hidden': sidebarCollapsed}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    
                    @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
                    <x-nav-link :href="route('recipes.index')" :active="request()->routeIs('recipes.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('recipes.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Resep (BOM)</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Produk / Menu Jual</span>
                    </x-nav-link>
                    @endif

                    <!-- Ingredients: Admin, Manager, Owner, Roaster -->
                    @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'roaster']))
                    <x-nav-link :href="route('ingredients.index')" :active="request()->routeIs('ingredients.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ingredients.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Data Bahan Baku</span>
                    </x-nav-link>
                    @endif

                    @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Data Supplier</span>
                    </x-nav-link>

                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('categories.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Kategori Menu</span>
                    </x-nav-link>

                    <x-nav-link :href="route('promotions.index')" :active="request()->routeIs('promotions.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('promotions.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Promo & Diskon</span>
                    </x-nav-link>

                    <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('settings.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Pengaturan Sistem</span>
                    </x-nav-link>

                    <x-nav-link :href="route('sliders.index')" :active="request()->routeIs('sliders.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('sliders.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Slider / CMS</span>
                    </x-nav-link>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'owner']))
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('users.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Manajemen User</span>
                    </x-nav-link>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- User Profile & Collapse Button -->
    <div class="p-4 border-t border-gray-200 bg-gray-50 relative">
        <!-- Collapse Toggle -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="hidden md:flex absolute -right-3 top-1/2 transform -translate-y-1/2 w-6 h-6 bg-white border border-gray-200 rounded-full items-center justify-center text-gray-400 hover:text-[#5f674d] shadow-sm z-50 transition-colors">
            <svg :class="sidebarCollapsed ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <div class="flex items-center gap-3 mb-3" :class="sidebarCollapsed ? 'justify-center' : ''">
            <div class="w-9 h-9 rounded-full bg-[#5f674d] flex items-center justify-center text-white font-bold text-sm shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden" :class="sidebarCollapsed ? 'hidden' : ''">
                <p class="text-sm font-bold text-gray-700 truncate w-32">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate w-32">{{ Auth::user()->email }}</p>
                <p class="text-[10px] font-bold text-[#5f674d] uppercase tracking-wider mt-0.5">{{ Auth::user()->role }}</p>
            </div>
        </div>

        <!-- Profile Settings Link -->
        <a href="{{ route('profile.edit') }}" :class="sidebarCollapsed ? 'hidden' : 'block'" 
           class="mb-2 w-full flex items-center justify-center gap-2 px-4 py-2 rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition text-xs font-bold uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span>Edit Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" :class="sidebarCollapsed ? 'hidden' : 'block'">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-red-600 transition text-xs font-bold uppercase tracking-wide">
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</nav>