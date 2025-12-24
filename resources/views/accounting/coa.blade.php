<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            {{ __('Chart of Accounts (COA)') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" x-data="{ openModal: false, editMode: false, currentId: null, form: { code: '', name: '', type: 'asset' } }">
        
        <!-- Add Button -->
        <div class="mb-4 flex justify-end">
            <button @click="openModal = true; editMode = false; form = { code: '', name: '', type: 'asset' }" class="bg-[#5f674d] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#4a503b] transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Akun
            </button>
        </div>

        <!-- COA Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Akun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Akun</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($accounts as $account)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $account->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $account->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($account->type == 'asset') bg-green-100 text-green-800 
                                    @elseif($account->type == 'liability') bg-red-100 text-red-800
                                    @elseif($account->type == 'equity') bg-blue-100 text-blue-800
                                    @elseif($account->type == 'revenue') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($account->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="openModal = true; editMode = true; currentId = {{ $account->id }}; form = { code: '{{ $account->code }}', name: '{{ $account->name }}', type: '{{ $account->type }}' }" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                
                                <form action="{{ route('accounting.coa.destroy', $account->id) }}" method="POST" class="inline-block" data-confirm="Yakin ingin menghapus akun ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form :action="editMode ? '/accounting/coa/' + currentId : '{{ route('accounting.coa.store') }}'" method="POST">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PATCH">
                        </template>

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="editMode ? 'Edit Akun' : 'Tambah Akun Baru'"></h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kode Akun</label>
                                    <input type="text" name="code" x-model="form.code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Akun</label>
                                    <input type="text" name="name" x-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Akun</label>
                                    <select name="type" x-model="form.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                                        <option value="asset">Asset (Harta)</option>
                                        <option value="liability">Liability (Kewajiban)</option>
                                        <option value="equity">Equity (Modal)</option>
                                        <option value="revenue">Revenue (Pendapatan)</option>
                                        <option value="expense">Expense (Beban)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5f674d] text-base font-medium text-white hover:bg-[#4a503b] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
