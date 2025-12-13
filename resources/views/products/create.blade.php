<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">{{ __('Tambah Produk Baru') }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
           <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Foto Produk</label>
                    <input type="file" name="image" class="w-full border border-gray-300 rounded-lg text-sm p-2 bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#5f674d] file:text-white hover:file:bg-[#424836]">
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Nama Produk</label>
                        <input type="text" name="name" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kode (SKU)</label>
                        <input type="text" name="code" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Kategori</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Satuan</label>
                        <input type="text" name="unit" placeholder="cup, porsi, 200gr" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
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
                        <input type="number" name="price" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                    <div>
                         <label class="block text-xs font-bold text-gray-600 uppercase mb-2">HPP / Harga Pokok (Rp)</label>
                         <input type="number" name="cost_price" value="0" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Stok Awal</label>
                        <input type="number" name="stock" value="0" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                    </div>
                </div>

                {{-- CUSTOM OPTIONS SECTION (Alpine.js) --}}
                <div x-data="{
                        options: [],
                        addOption() {
                            this.options.push({ name: '', values: '' });
                        },
                        removeOption(index) {
                            this.options.splice(index, 1);
                        }
                     }" class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-bold text-blue-800 uppercase">Kustomisasi Menu (Opsional)</p>
                        <button type="button" @click="addOption()" class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 font-bold">+ Tambah Opsi</button>
                    </div>
                    
                    <p class="text-xs text-blue-600 mb-3 italic">Contoh: Nama = "Pilih Beans", Values = "Gayo, Bali, Toraja" (pisahkan dengan koma)</p>

                    <template x-for="(opt, index) in options" :key="index">
                        <div class="flex gap-3 mb-2 items-start">
                            <div class="w-1/3">
                                <input type="text" :name="'options[' + index + '][name]'" x-model="opt.name" placeholder="Nama Opsi (ex: Method)" class="w-full border-blue-200 rounded text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div class="w-2/3 flex gap-2">
                                <input type="text" :name="'options[' + index + '][values]'" x-model="opt.values" placeholder="Pilihan (ex: V60, Kalita)" class="w-full border-blue-200 rounded text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <button type="button" @click="removeOption(index)" class="text-red-500 hover:text-red-700 px-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Deskripsi / Notes</label>
                    <textarea name="description" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-[#5f674d] hover:bg-[#4b523d] text-white font-bold rounded-lg">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>