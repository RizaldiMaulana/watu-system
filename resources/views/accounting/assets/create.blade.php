<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Aset Tetap Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('accounting.assets.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <p class="text-sm text-yellow-700">
                                <strong>Penting:</strong> Pastikan Anda sudah membuat akun yang diperlukan di Chart of Accounts (CoA) sebelum menambahkan aset.
                                <br>1. Akun Aset (Contoh: 1-201 Mesin)
                                <br>2. Akun Akumulasi Penyusutan (Contoh: 1-202 Akum. Peny. Mesin)
                                <br>3. Akun Beban Penyusutan (Contoh: 6-101 Beban Peny. Mesin)
                            </p>
                        </div>

                        <!-- Basic Info -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Aset')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Contoh: Mesin Espresso La Marzocco" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi / Nomor Seri (Opsional)')" />
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="purchase_date" :value="__('Tanggal Perolehan')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date', date('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="useful_life_years" :value="__('Umur Ekonomis (Tahun)')" />
                                <x-text-input id="useful_life_years" class="block mt-1 w-full" type="number" step="1" name="useful_life_years" :value="old('useful_life_years', 4)" required />
                                <p class="text-xs text-gray-500 mt-1">Standar: Komputer (4), Kendaraan (8), Bangunan (20).</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="cost" :value="__('Harga Perolehan (Cost)')" />
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <x-text-input id="cost" class="block w-full pl-10" type="number" name="cost" :value="old('cost')" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="salvage_value" :value="__('Nilai Sisa (Residu)')" />
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <x-text-input id="salvage_value" class="block w-full pl-10" type="number" name="salvage_value" :value="old('salvage_value', 0)" required />
                                </div>
                            </div>
                        </div>

                        <!-- Accounting Integration -->
                        <div class="border-t pt-4 mt-4">
                            <h3 class="font-bold text-gray-700 mb-3">Integrasi Akuntansi (Mapping Akun)</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="fixed_asset_account_id" :value="__('1. Akun Aset Tetap (D)')" />
                                    <select id="fixed_asset_account_id" name="fixed_asset_account_id" class="block mt-1 w-full rounded-md border-gray-300">
                                        @foreach($accounts->where('type', 'asset') as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500">Akun Harta (1-xxx)</p>
                                </div>

                                <div>
                                    <x-input-label for="accumulated_depreciation_account_id" :value="__('2. Akun Akumulasi Penyusutan (K)')" />
                                    <select id="accumulated_depreciation_account_id" name="accumulated_depreciation_account_id" class="block mt-1 w-full rounded-md border-gray-300">
                                        @foreach($accounts->where('type', 'asset') as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500">Biasanya akun Kontra-Aset (1-xxx dengan saldo Kredit)</p>
                                </div>

                                <div>
                                    <x-input-label for="depreciation_expense_account_id" :value="__('3. Akun Beban Penyusutan (D)')" />
                                    <select id="depreciation_expense_account_id" name="depreciation_expense_account_id" class="block mt-1 w-full rounded-md border-gray-300">
                                        @foreach($accounts->where('type', 'expense') as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500">Akun Biaya Operasional (6-xxx)</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Simpan Aset') }}</x-primary-button>
                            <a href="{{ route('accounting.assets.index') }}" class="text-gray-600 hover:text-gray-900 font-bold text-sm">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
