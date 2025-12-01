<nav class="w-64 flex-shrink-0 flex flex-col justify-between h-screen sticky top-0 z-50 bg-white border-r border-gray-200">
    
    <div>
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/LOGO Produk.png') }}" alt="Logo" style="height: 32px; width: auto;">
                <span class="font-bold text-lg text-gray-800 tracking-wide">WATU <span class="text-[#d4a056]">SYSTEM</span></span>
            </a>
        </div>

        <div class="px-3 py-6 space-y-1">
            <p class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">Menu Utama</p>
            
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-[#5f674d] text-white shadow-md shadow-[#5f674d]/20' : 'text-gray-600 hover:bg-gray-50 hover:text-[#5f674d]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium text-sm">Dashboard</span>
            </x-nav-link>

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'barista']))
            <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('pos.*') ? 'bg-[#5f674d] text-white shadow-md shadow-[#5f674d]/20' : 'text-gray-600 hover:bg-gray-50 hover:text-[#5f674d]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span class="font-medium text-sm">Mesin Kasir</span>
            </x-nav-link>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner', 'roaster']))
            <x-nav-link :href="route('purchases.create')" :active="request()->routeIs('purchases.*')" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('purchases.*') ? 'bg-[#5f674d] text-white shadow-md shadow-[#5f674d]/20' : 'text-gray-600 hover:bg-gray-50 hover:text-[#5f674d]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                <span class="font-medium text-sm">Stok & Pengadaan</span>
            </x-nav-link>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
            <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-medium text-sm">Laporan & Analisa</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open" x-collapse class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.index')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.index') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Keuangan & Jurnal</span>
                    </x-nav-link>

                    <x-nav-link :href="route('reports.stock')" :active="request()->routeIs('reports.stock')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('reports.stock') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Stok Bahan Baku</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'manager', 'owner']))
            <div x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('suppliers.*') || request()->routeIs('ingredients.*') || request()->routeIs('recipes.*') ? 'true' : 'false' }} }" class="pt-2">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-[#5f674d] transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 group-hover:text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <span class="font-medium text-sm">Data Master</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open" x-collapse class="mt-1 space-y-1 pl-4 border-l-2 border-gray-100 ml-4">
                    <x-nav-link :href="route('recipes.index')" :active="request()->routeIs('recipes.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('recipes.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Resep</span>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Produk / Menu</span>
                    </x-nav-link>

                    <x-nav-link :href="route('ingredients.index')" :active="request()->routeIs('ingredients.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('ingredients.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Bahan Baku</span>
                    </x-nav-link>

                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('suppliers.*') ? 'text-[#5f674d] font-bold bg-[#5f674d]/5' : 'text-gray-500 hover:text-[#5f674d]' }}">
                        <span class="text-xs">●</span>
                        <span class="font-medium text-sm">Data Supplier</span>
                    </x-nav-link>
                </div>
            </div>
            @endif

        </div>
    </div>

    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-[#5f674d] flex items-center justify-center text-white font-bold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-gray-700 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                <p class="text-[10px] font-bold text-[#5f674d] uppercase tracking-wider mt-0.5">{{ Auth::user()->role }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:text-red-600 transition text-xs font-bold uppercase tracking-wide">
                Sign Out
            </button>
        </form>
    </div>
</nav>