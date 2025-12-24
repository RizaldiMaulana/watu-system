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
                            <select name="unit" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                <option value="gr">Gram (gr)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="ml">Milliliter (ml)</option>
                                <option value="l">Liter (l)</option>
                                <option value="pcs">Pcs</option>
                                <option value="cup">Cup</option>
                                <option value="pack">Pack</option>
                                <option value="box">Box</option>
                                <option value="galon">Galon</option>
                            </select>
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

            <div class="md:col-span-2" x-data="{ editOpen: false, editItem: {} }">
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
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi / Update Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($ingredients as $item)
                            <tr class="hover:bg-gray-50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                            <div class="text-xs text-gray-500">Min: {{ $item->minimum_stock }} {{ $item->unit }}</div>
                                        </div>
                                        <!-- Edit Button (Master Data) -->
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="editOpen = true; editItem = { id: {{ $item->id }}, name: '{{ addslashes($item->name) }}', unit: '{{ $item->unit }}', minimum_stock: {{ $item->minimum_stock }} }"
                                                class="text-blue-500 hover:text-blue-700 p-1 bg-blue-50 rounded">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <form action="{{ route('ingredients.destroy', $item->id) }}" method="POST" data-confirm="Hapus bahan ini?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->stock <= $item->minimum_stock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $item->stock }} {{ $item->unit }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <!-- Stock Opname Form -->
                                    <form action="{{ route('ingredients.update', $item->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action_type" value="stock_opname">
                                        
                                        <div class="relative">
                                            <input type="number" name="stock" 
                                                   class="w-24 py-1 px-2 border-gray-300 rounded text-sm focus:ring-[#5f674d] focus:border-[#5f674d] text-right" 
                                                   placeholder="{{ $item->stock }}" required step="0.01">
                                            <span class="absolute right-0 -bottom-4 text-[10px] text-gray-400">Opname (Fisik)</span>
                                        </div>

                                        <button type="submit" class="bg-[#5f674d] text-white border border-[#5f674d] hover:bg-[#4b523d] p-1.5 rounded transition shadow-sm" title="Simpan Opname">
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

                <!-- Edit Modal (Shared) - Moved Outside Table -->
                <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="editOpen = false">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <form :action="'/ingredients/' + editItem.id" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="action_type" value="update_data">
                                
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Data Bahan Baku</h3>
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Nama Bahan</label>
                                        <input type="text" name="name" x-model="editItem.name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Satuan</label>
                                        <select name="unit" x-model="editItem.unit" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                            <option value="gr">Gram (gr)</option>
                                            <option value="kg">Kilogram (kg)</option>
                                            <option value="ml">Milliliter (ml)</option>
                                            <option value="l">Liter (l)</option>
                                            <option value="pcs">Pcs</option>
                                            <option value="cup">Cup</option>
                                            <option value="pack">Pack</option>
                                            <option value="box">Box</option>
                                            <option value="galon">Galon</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Minimum Stok (Alert)</label>
                                        <input type="number" name="minimum_stock" x-model="editItem.minimum_stock" class="w-full border-gray-300 rounded-lg text-sm focus:ring-[#5f674d] focus:border-[#5f674d]">
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5f674d] text-base font-medium text-white hover:bg-[#4b523d] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                        Simpan Perubahan
                                    </button>
                                    <button type="button" @click="editOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>