<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800">{{ __('Edit Data Supplier') }}</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Nama Perusahaan</label>
                    <input type="text" name="name" value="{{ $supplier->name }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ $supplier->phone }}" required class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-2">Alamat</label>
                    <textarea name="address" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-[#5f674d] focus:border-[#5f674d]">{{ $supplier->address }}</textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('suppliers.index') }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-[#5f674d] text-white font-bold rounded-lg hover:bg-[#4b523d]">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>