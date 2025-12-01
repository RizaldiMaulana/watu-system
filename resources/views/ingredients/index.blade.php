<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            {{ __('Manajemen Bahan Baku (Inventory)') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-800 mb-4 text-lg border-b pb-2">Tambah Bahan Baru</h3>
                    <form action="{{ route('ingredients.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Nama Bahan</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" placeholder="Misal: Susu UHT">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan</label>
                            <input type="text" name="unit" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" placeholder="ml, gr, pcs">
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Stok Awal</label>
                                <input type="number" name="stock" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Min. Stok</label>
                                <input type="number" name="minimum_stock" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" value="10">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-[#5f674d] text-dark font-bold rounded-lg hover:bg-[#4b523d] transition shadow-md">
                            + Simpan Data
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Daftar Stok Gudang</h3>
                        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">Total: {{ $ingredients->total() }} Item</span>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Bahan</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Sistem</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Opname (Fisik)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($ingredients as $item)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500">Min: {{ $item->minimum_stock }} {{ $item->unit }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->stock <= $item->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('ingredients.update', $item->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="reason" value="Stok Opname Harian">
                                        <input type="number" name="stock" 
                                               class="w-24 py-1 px-2 border-gray-300 rounded text-sm focus:ring-[#5f674d] focus:border-[#5f674d]" 
                                               placeholder="{{ $item->stock }}" required>
                                        <button type="submit" class="bg-white border border-gray-300 text-gray-600 hover:text-[#5f674d] hover:border-[#5f674d] p-1.5 rounded transition" title="Simpan Perubahan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">
                                    Belum ada data bahan baku. Silakan tambah di form sebelah kiri.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    @if($ingredients->hasPages())
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        {{ $ingredients->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>