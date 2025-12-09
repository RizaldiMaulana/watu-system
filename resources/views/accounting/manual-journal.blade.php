<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            {{ __('Buat Jurnal Umum Manual') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="journalForm()">
            <div class="p-6 bg-white border-b border-gray-200">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Ada Kesalahan!</strong>
                        <ul class="mt-1 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('accounting.journal.store') }}" method="POST" @submit.prevent="submitForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi / Keterangan</label>
                            <input type="text" name="description" placeholder="Contoh: Pembayaran Biaya Listrik Bulan Ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Detail Jurnal</label>
                        <div class="border rounded-md overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Akun</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase" width="20%">Debit</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase" width="20%">Kredit</th>
                                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase" width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr>
                                            <td class="px-4 py-2">
                                                <select :name="'details['+index+'][account_id]'" x-model="row.account_id" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50" required>
                                                    <option value="">-- Pilih Akun --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'details['+index+'][debit]'" x-model.number="row.debit" min="0" class="block w-full text-sm text-right rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'details['+index+'][credit]'" x-model.number="row.credit" min="0" class="block w-full text-sm text-right rounded-md border-gray-300 shadow-sm focus:border-[#5f674d] focus:ring focus:ring-[#5f674d] focus:ring-opacity-50">
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700" :disabled="rows.length <= 2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-gray-50 font-bold">
                                    <tr>
                                        <td class="px-4 py-2 text-right">Total:</td>
                                        <td class="px-4 py-2 text-right" :class="totalDebit == totalCredit ? 'text-green-600' : 'text-red-600'">
                                            <span x-text="formatNumber(totalDebit)"></span>
                                        </td>
                                        <td class="px-4 py-2 text-right" :class="totalDebit == totalCredit ? 'text-green-600' : 'text-red-600'">
                                            <span x-text="formatNumber(totalCredit)"></span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr x-show="totalDebit != totalCredit">
                                        <td colspan="4" class="px-4 py-2 text-center text-red-500 text-xs uppercase tracking-wide">
                                            Debit dan Kredit Harus Seimbang (Selisih: <span x-text="formatNumber(Math.abs(totalDebit - totalCredit))"></span>)
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-2">
                            <button type="button" @click="addRow()" class="text-sm text-[#5f674d] font-bold hover:underline">+ Tambah Baris</button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('accounting.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-[#5f674d] text-white rounded-lg hover:bg-[#4a503b] transition disabled:opacity-50 disabled:cursor-not-allowed" :disabled="totalDebit != totalCredit || totalDebit == 0">Simpan Jurnal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('journalForm', () => ({
                rows: [
                    { account_id: '', debit: 0, credit: 0 },
                    { account_id: '', debit: 0, credit: 0 }
                ],
                
                get totalDebit() {
                    return this.rows.reduce((sum, row) => sum + (parseFloat(row.debit) || 0), 0);
                },

                get totalCredit() {
                    return this.rows.reduce((sum, row) => sum + (parseFloat(row.credit) || 0), 0);
                },

                addRow() {
                    this.rows.push({ account_id: '', debit: 0, credit: 0 });
                },

                removeRow(index) {
                    if (this.rows.length > 2) {
                        this.rows.splice(index, 1);
                    }
                },

                formatNumber(value) {
                    return new Intl.NumberFormat('id-ID').format(value);
                },

                submitForm(event) {
                    if (this.totalDebit !== this.totalCredit) {
                        alert('Debit dan Kredit tidak seimbang!');
                        return;
                    }
                    if (this.totalDebit === 0) {
                        alert('Total tidak boleh 0!');
                        return;
                    }
                    event.target.submit();
                }
            }));
        });
    </script>
</x-app-layout>
