<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">{{ __('Edit Produk') }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ $product->name }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kode (SKU)</label>
                        <input type="text" name="code" value="{{ $product->code }}" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                            <option value="coffee" {{ $product->category == 'coffee' ? 'selected' : '' }}>Coffee Drink</option>
                            <option value="non_coffee" {{ $product->category == 'non_coffee' ? 'selected' : '' }}>Non Coffee</option>
                            <option value="roast_bean" {{ $product->category == 'roast_bean' ? 'selected' : '' }}>Roast Bean (Biji Kopi)</option>
                            <option value="food" {{ $product->category == 'food' ? 'selected' : '' }}>Makanan Berat</option>
                            <option value="snack" {{ $product->category == 'snack' ? 'selected' : '' }}>Camilan / Snack</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Satuan</label>
                        <input type="text" name="unit" value="{{ $product->unit }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-3 border-b border-gray-200 pb-1">Detail Roast Bean (Opsional)</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Varietal</label>
                                <input type="text" name="varietal" 
                                    value="{{ old('varietal', $product->varietal ?? '') }}" 
                                    placeholder="Contoh: Sigararutang, Kartika"
                                    class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Proses (Process)</label>
                                <select name="process" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d] text-sm">
                                    <option value="" selected>-- Pilih Proses --</option>
                                    <option value="Natural" {{ (old('process', $product->process ?? '') == 'Natural') ? 'selected' : '' }}>Natural (Dry)</option>
                                    <option value="Full Wash" {{ (old('process', $product->process ?? '') == 'Full Wash') ? 'selected' : '' }}>Full Wash (Basah)</option>
                                    <option value="Semi Wash" {{ (old('process', $product->process ?? '') == 'Semi Wash') ? 'selected' : '' }}>Semi Wash (Giling Basah)</option>
                                    <option value="Honey" {{ (old('process', $product->process ?? '') == 'Honey') ? 'selected' : '' }}>Honey Process</option>
                                    <option value="Experimental" {{ (old('process', $product->process ?? '') == 'Experimental') ? 'selected' : '' }}>Experimental / Fermented</option>
                                </select>
                            </div>
                        </div>
                    </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ $product->price }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ $product->stock }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Deskripsi / Notes</label>
                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">{{ $product->description }}</textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-lg">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>