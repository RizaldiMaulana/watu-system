<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('recipes.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">
                Atur Resep: <span class="text-[#5f674d]">{{ $product->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Tambah Bahan Baku</h3>
                
                <form action="{{ route('recipes.store', $product->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Bahan Baku</label>
                        <select name="ingredient_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">*Stok Gudang: Bahan yang tersedia</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Takaran / Jumlah</label>
                        <input type="number" name="amount_needed" step="0.1" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" placeholder="Contoh: 15">
                        <p class="text-[10px] text-gray-400 mt-1">Jumlah yang dikurangi per 1 porsi produk.</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-[#5f674d] text-white font-bold rounded-lg hover:bg-[#4b523d] transition shadow-md flex justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambahkan ke Resep
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-700">Komposisi Saat Ini</h3>
                        <p class="text-xs text-gray-500">Setiap penjualan 1 {{ $product->unit }} {{ $product->name }} akan mengurangi stok di bawah ini.</p>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Bahan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Takaran</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($product->recipes as $recipe)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                {{ $recipe->ingredient->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ $recipe->amount_needed }} {{ $recipe->ingredient->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" data-confirm="Hapus bahan ini dari resep?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($product->recipes->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">
                                <svg class="w-12 h-12 mx-auto mb-2 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Belum ada resep yang diatur.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>