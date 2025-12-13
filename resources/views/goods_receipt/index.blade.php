<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Penerimaan Barang') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto" x-data="grTable()">
        
        <!-- PENDING PURCHASES (WAITING FOR RECEIPT) -->
        <div class="mb-10">
            <h3 class="font-serif font-bold text-lg text-[#2b2623] mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-sm">1</span>
                    Menunggu Penerimaan (PO System)
                </span>
                
                {{-- Button Removed --}}
            </h3>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($pendingPurchases->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">No. Invoice PO</th>
                                <th class="px-6 py-4">Supplier</th>
                                <th class="px-6 py-4">Tanggal Order</th>
                                <th class="px-6 py-4 text-right">Total</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pendingPurchases as $po)
                            <tr class="hover:bg-[#F9F7F2] transition">
                                <td class="px-6 py-4 font-bold text-[#2b2623]">{{ $po->invoice_number }}</td>
                                <td class="px-6 py-4">{{ $po->supplier->name }}</td>
                                <td class="px-6 py-4">{{ date('d M Y', strtotime($po->transaction_date)) }}</td>
                                <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('goods-receipt.create', $po->id) }}" style="background-color: #F59E0B;" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg hover:bg-yellow-600 transition font-bold text-xs shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Terima Barang
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p>Tidak ada PO yang menunggu penerimaan.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- RECEIVED PURCHASES (HISTORY & VERIFICATION) -->
        <div>
            <h3 class="font-serif font-bold text-lg text-[#2b2623] mb-4 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-sm">2</span>
                Riwayat Penerimaan & Validasi
            </h3>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">No. Invoice PO</th>
                                <th class="px-6 py-4">Supplier</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Bukti Terima</th>
                                <th class="px-6 py-4 text-center">Validation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($receivedPurchases->merge($historyPurchases) as $po)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-[#2b2623]">{{ $po->invoice_number }}</td>
                                <td class="px-6 py-4">{{ $po->supplier->name }}</td>
                                <td class="px-6 py-4">
                                    @if($po->status == 'verified')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Verified</span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu Validasi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($po->delivery_proof || $po->proof_file)
                                        <div class="flex flex-col items-center gap-1">
                                            <a href="{{ asset('storage/' . ($po->delivery_proof ?? $po->proof_file)) }}" target="_blank" class="text-[#5f674d] hover:underline font-bold text-xs flex justify-center items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Lihat Bukti
                                            </a>
                                            @if($po->status !== 'verified')
                                                <button type="button" @click.prevent="openUploadModal({{ $po->id }}, '{{ $po->invoice_number }}', '{{ asset('storage/' . ($po->delivery_proof ?? $po->proof_file)) }}')" 
                                                    class="text-[10px] text-blue-500 hover:text-blue-700 underline">
                                                    Ganti
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        @if($po->status !== 'verified')
                                            <button type="button" @click.prevent="openUploadModal({{ $po->id }}, '{{ $po->invoice_number }}', '')" 
                                                    class="px-3 py-1 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold hover:bg-blue-100 transition flex items-center justify-center gap-1 border border-blue-200 mx-auto">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                Upload
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($po->status !== 'verified' && (auth()->user()->role == 'admin' || auth()->user()->role == 'manager' || auth()->user()->role == 'owner'))
                                        <a href="{{ route('goods-receipt.validate', $po->id) }}" 
                                           class="inline-flex items-center justify-center gap-1 px-3 py-1 bg-[#5f674d] text-white rounded-md text-[10px] font-bold hover:bg-[#4b523d] transition shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Validasi Penerimaan
                                        </a>
                                    @elseif($po->status === 'verified')
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="flex items-center justify-center gap-1 text-green-600 font-bold text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Terverifikasi
                                            </div>
                                            @if($po->signer)
                                                <div class="text-[10px] text-gray-500 text-center leading-tight">
                                                    Oleh: {{ $po->signer->name }} <br>
                                                    <span class="text-gray-400">{{ $po->signed_at ? \Carbon\Carbon::parse($po->signed_at)->format('d M H:i') : '' }}</span>
                                                </div>
                                            @endif
                                            
                                            <a href="{{ route('goods-receipt.print', $po->id) }}" target="_blank" class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold hover:bg-gray-200 transition flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                Print Bukti Terima
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    Belum ada riwayat penerimaan barang.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- UPLOAD PROOF MODAL --}}
        <div x-show="showUploadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showUploadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showUploadModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showUploadModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form :action="uploadAction" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Upload Bukti Penerimaan
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Upload foto surat jalan, nota supplier, atau foto barang fisik untuk PO <span class="font-bold" x-text="poNumber"></span>.
                                        </p>
                                        
                                        <div x-show="currentProof" class="mb-4">
                                            <p class="text-xs font-bold text-gray-400 mb-1">Bukti Saat Ini:</p>
                                            <img :src="currentProof" class="h-32 rounded border border-gray-200">
                                        </div>

                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-[#5f674d] transition-colors group">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-[#5f674d]" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-[#5f674d] hover:text-[#4b523d] focus-within:outline-none">
                                                        <span>Ambil Foto / Pilih Gambar</span>
                                                        <input id="file-upload" name="delivery_proof" type="file" class="sr-only" required accept="image/*">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, up to 2MB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#5f674d] text-base font-medium text-white hover:bg-[#4b523d] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Upload Bukti
                            </button>
                            <button type="button" @click="showUploadModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function grTable() {
                return {
                    showUploadModal: false,
                    poNumber: '',
                    uploadAction: '',
                    currentProof: '',
                    
                    openUploadModal(id, number, proofUrl) {
                        this.poNumber = number;
                        this.uploadAction = `{{ url('/goods-receipt') }}/${id}/upload-proof`;
                        this.currentProof = proofUrl;
                        this.showUploadModal = true;
                    }
                }
            }
        </script>
    </div>
</x-app-layout>
