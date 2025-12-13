<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">{{ __('Edit Kategori') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug (URL Friendly)</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Menu</label>
                            <select name="type" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                                <option value="cafe" {{ $category->type == 'cafe' ? 'selected' : '' }}>Cafe Menu</option>
                                <option value="roastery" {{ $category->type == 'roastery' ? 'selected' : '' }}>Roastery Menu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil (Sort Order)</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <a href="{{ route('categories.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-[#5f674d] text-white rounded-lg hover:bg-[#4b523d]">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
    <script>
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        // Only auto-update if user hasn't manually edited slug? 
        // Or specific behavior. for now simple simple on change if needed
    </script>
</x-app-layout>
