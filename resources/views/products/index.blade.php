<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">
                    {{ __('Master Data Produk') }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">Kelola menu, harga, dan stok barang.</p>
            </div>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-[#5f674d] hover:bg-[#4b523d] text-white text-sm font-bold rounded-lg shadow-sm transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        
        <div class="mb-4 flex justify-end">
            <form method="GET" action="{{ route('products.index') }}" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d] text-sm w-64">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-0">
                                    <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">Kode: {{ $product->code ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $product->category == 'roast_bean' ? 'bg-brown-100 text-brown-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucwords(str_replace('_', ' ', $product->category)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->formatted_price }} <span class="text-xs text-gray-400">/ {{ $product->unit }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->stock <= 5)
                                <span class="text-red-600 font-bold text-sm">{{ $product->stock }}</span>
                            @else
                                <span class="text-gray-900 text-sm">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-[#d4a056] hover:text-[#b58440] mr-3">Edit</a>
                            
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>