<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Receipt {{ $purchase->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto bg-white p-8 shadow-sm print:shadow-none print:max-w-none text-black font-sans text-sm">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center gap-4">
                {{-- Logo Placeholder --}}
                <div class="w-16 h-16 border border-gray-800 flex items-center justify-center font-bold text-xs p-1 text-center">
                    WATU LOGO
                </div>
                <div>
                    <h1 class="font-bold text-lg uppercase tracking-wider">Watu Coffee System</h1>
                    <p class="text-xs">Jalan Raya Tlogomas No. 1</p>
                    <p class="text-xs">Malang, Jawa Timur</p>
                    <p class="text-xs">Tel. +62 812 3456 7890</p>
                </div>
            </div>
            <div class="text-xs">
                1/1
            </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold uppercase underline tracking-wide">BUKTI PENERIMAAN BARANG</h2>
        </div>

        <!-- Info Block -->
        <div class="flex justify-between mb-6">
            <div class="w-1/2">
                <table class="w-full text-xs">
                    <tr>
                        <td class="w-24">Nama Supplier</td>
                        <td class="w-2 text-center">:</td>
                        <td class="font-bold uppercase">{{ $purchase->supplier->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="align-top">Alamat</td>
                        <td class="align-top text-center">:</td>
                        <td class="align-top">{{ $purchase->supplier->address ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="w-1/3">
                <table class="w-full text-xs">
                    <tr>
                        <td class="w-20">Nomor</td>
                        <td class="w-2 text-center">:</td>
                        <td class="font-bold">GR/{{ $purchase->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td class="w-2 text-center">:</td>
                        <td>{{ $purchase->signed_at ? \Carbon\Carbon::parse($purchase->signed_at)->format('d/m/Y') : date('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>No. PO</td>
                        <td class="w-2 text-center">:</td>
                        <td>{{ $purchase->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>No. SJ</td>
                        <td class="w-2 text-center">:</td>
                        <td>-</td> {{-- Surat Jalan dari Supplier --}}
                    </tr>
                </table>
            </div>
        </div>

        <p class="mb-2 text-xs">Telah diterima dengan baik barang-barang sebagai berikut :</p>

        <!-- Main Table -->
        <table class="w-full border border-black mb-4 text-xs">
            <thead>
                <tr class="border-b border-black text-center">
                    <th class="border-r border-black py-1 px-2 w-10">No.</th>
                    <th class="border-r border-black py-1 px-2 w-24">Kode Barang</th>
                    <th class="border-r border-black py-1 px-2 text-left">Nama Barang</th>
                    <th class="border-r border-black py-1 px-2 w-16">Satuan</th>
                    <th class="border-r border-black py-1 px-2 w-16">Kuantum</th>
                    <th class="py-1 px-2">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $index => $item)
                <tr class="border-b border-black">
                    <td class="border-r border-black py-1 px-2 text-center">{{ $index + 1 }}</td>
                    <td class="border-r border-black py-1 px-2 font-mono">{{ $item->ingredient_id ? 'ING-' . $item->ingredient_id : 'PRD-' . $item->product_id }}</td>
                    <td class="border-r border-black py-1 px-2">{{ $item->item_name }}</td>
                    <td class="border-r border-black py-1 px-2 text-center">Unit</td>
                    <td class="border-r border-black py-1 px-2 text-right font-bold">{{ $item->quantity }}</td>
                    <td class="py-1 px-2 italic">Baik</td>
                </tr>
                @endforeach
                {{-- Fill empty rows if needed for height, skipping for now --}}
            </tbody>
        </table>

        <!-- Footer Note -->
        <div class="mb-6 text-xs">
            <span class="font-bold">Keterangan :</span> {{ $purchase->notes ?? '-' }}
        </div>

        <!-- Signature Block (4 Columns) -->
        <table class="w-full border border-black text-xs text-center">
            <thead>
                <tr class="border-b border-black font-bold bg-gray-50">
                    <th class="border-r border-black py-1 w-1/4">Dibuat</th>
                    <th class="border-r border-black py-1 w-1/4">Mengetahui</th>
                    <th class="border-r border-black py-1 w-1/4">Gudang</th>
                    <th class="py-1 w-1/4">Pengirim</th>
                </tr>
            </thead>
            <tbody>
                <tr class="h-24">
                    <!-- Dibuat (Admin/Staff) -->
                    <td class="border-r border-black align-bottom pb-2">
                        ( .................................. )
                    </td>
                    
                    <!-- Mengetahui (Manager/Signer) -->
                    <td class="border-r border-black align-middle p-2 relative">
                        @if($purchase->signer && $purchase->signer->signature)
                            <div class="flex flex-col items-center justify-center h-full">
                                <img src="{{ asset('storage/' . $purchase->signer->signature) }}" class="h-16 object-contain z-10" alt="Signature">
                                <span class="font-bold mt-1 z-20">{{ $purchase->signer->name }}</span>
                            </div>
                        @else
                            <div class="h-full flex items-end justify-center pb-2">
                                ( .................................. )
                            </div>
                        @endif
                    </td>

                    <!-- Gudang (Receiver) -->
                    <td class="border-r border-black align-bottom pb-2">
                        ( .................................. )
                    </td>

                    <!-- Pengirim (Supplier) -->
                    <td class="align-bottom pb-2">
                        ( .................................. )
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="fixed bottom-8 right-8 no-print flex gap-4">
        <button onclick="window.print()" class="px-6 py-3 bg-[#5f674d] text-white font-bold rounded-full shadow-lg hover:bg-[#4b523d] transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Print Dokumen
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-full shadow-lg hover:bg-gray-700 transition">
            Tutup
        </button>
    </div>

</body>
</html>
