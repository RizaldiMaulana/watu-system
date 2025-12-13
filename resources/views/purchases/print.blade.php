<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order {{ $purchase->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-8 shadow-sm print:shadow-none print:max-w-none">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b pb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">PURCHASE ORDER</h1>
                <p class="text-gray-500 text-sm mt-1">Watu Coffee System</p>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-gray-700">#{{ $purchase->invoice_number }}</p>
                <p class="text-gray-500 text-sm">{{ $purchase->transaction_date }}</p>
            </div>
        </div>

        <!-- Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Supplier</h3>
                <p class="font-bold text-gray-800">{{ $purchase->supplier->name ?? '-' }}</p>
                <p class="text-sm text-gray-600">{{ $purchase->supplier->contact_person ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $purchase->supplier->phone ?? '' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</h3>
                <p class="font-bold text-gray-800 uppercase">{{ $purchase->payment_method }}</p>
                @if($purchase->payment_method == 'credit')
                    <p class="text-sm text-red-500">Jatuh Tempo: {{ $purchase->due_date }}</p>
                @endif
                <p class="text-sm text-gray-600 mt-2">Status: {{ ucfirst($purchase->payment_status) }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full mb-8">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="text-left py-3 text-sm font-bold text-gray-600">Item</th>
                    <th class="text-left py-3 text-sm font-bold text-gray-600">Tipe</th>
                    <th class="text-center py-3 text-sm font-bold text-gray-600">Qty</th>
                    <th class="text-right py-3 text-sm font-bold text-gray-600">Harga Satuan</th>
                    <th class="text-right py-3 text-sm font-bold text-gray-600">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-3 text-sm text-gray-800 font-medium">
                        {{ $item->item_name }}
                    </td>
                    <td class="py-3 text-sm text-gray-500">
                        @if($item->ingredient_id) Bahan Baku @else Produk @endif
                    </td>
                    <td class="py-3 text-sm text-center text-gray-800">{{ $item->quantity }}</td>
                    <td class="py-3 text-sm text-right text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-3 text-sm text-right text-gray-800 font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="py-4 text-right text-gray-600 font-bold">TOTAL</td>
                    <td class="py-4 text-right text-xl font-bold text-gray-900">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Notes -->
        <div class="mb-12 border-t pt-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Catatan</h3>
            <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg italic">
                {{ $purchase->notes ?? 'Tidak ada catatan khusus.' }}
            </p>
        </div>

        <!-- Signatures (Optional for PO) -->
        <div class="grid grid-cols-2 gap-8 mt-12 pt-8">
            <div class="text-center">
                <p class="text-xs font-bold text-gray-400 uppercase mb-4">Disetujui Oleh</p>
                
                <div class="h-24 flex items-center justify-center">
                    @if($purchase->creator && $purchase->creator->signature)
                        <img src="{{ asset('storage/' . $purchase->creator->signature) }}" class="h-20" alt="Signature">
                    @else
                        <div class="h-20 w-full"></div>
                    @endif
                </div>

                <div class="border-t border-gray-300 w-32 mx-auto mt-2"></div>
                <p class="text-sm font-bold text-gray-600 mt-2">{{ $purchase->creator->name ?? 'Manager' }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs font-bold text-gray-400 uppercase mb-16">Diterima Oleh</p>
                <div class="border-t border-gray-300 w-32 mx-auto"></div>
                <p class="text-sm font-bold text-gray-600 mt-2">Supplier</p>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 no-print flex gap-4">
        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Print Dokumen
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-full shadow-lg hover:bg-gray-700 transition">
            Tutup
        </button>
    </div>

</body>
</html>
