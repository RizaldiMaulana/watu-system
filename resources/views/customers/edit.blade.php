<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Pelanggan</h1>
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pelanggan / Bisnis</label>
                            <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                        </div>

                        <!-- Tipe Pelanggan (CRITICAL) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Pelanggan</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                                <option value="general" {{ $customer->type == 'general' ? 'selected' : '' }}>General (Umum)</option>
                                <option value="roastery" {{ $customer->type == 'roastery' ? 'selected' : '' }}>Roastery (B2B)</option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Pilih 'Roastery' untuk mengaktifkan opsi pembayaran Tempo/Credit di POS.</p>
                        </div>

                        <!-- Limit Kredit -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Limit Kredit (Rp)</label>
                            <input type="number" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                        </div>

                        <!-- Kontak -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                        </div>

                        <!-- Alamat -->
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">{{ old('address', $customer->address) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-bold">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-[#5f674d] text-white rounded-lg hover:bg-[#4b523d] font-bold shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
