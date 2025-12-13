<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap');

        :root {
            --watu-olive: #5f674d;
            --watu-cream: #F9F7F2;
            --watu-dark: #2b2623;
            --watu-gold: #d4a056;
        }
        
        body { background-color: var(--watu-cream); }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }
    </style>

    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                   <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                     <p class="text-2xl font-bold text-gray-800">{{ $todayOrdersCount ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center">
                <div class="p-3 rounded-full {{ isset($webOrders) && count($webOrders) > 0 ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-400' }}">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Order Online Pending</p>
                    <p class="text-2xl font-bold {{ isset($webOrders) && count($webOrders) > 0 ? 'text-orange-600' : 'text-gray-800' }}">
                        {{ isset($webOrders) ? count($webOrders) : 0 }} Menunggu
                    </p>
                </div>
            </div>
        </div>

            
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 250px;">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#5f674d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Jadwal Reservasi Meja
                    </h3>
                    @if(isset($reservations))
                        <span class="text-xs bg-[#5f674d] text-white px-2 py-1 rounded-full">{{ count($reservations) }} Booking</span>
                    @endif
                </div>
                <div class="overflow-y-auto grow">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th>
                                <th class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu</th>
                                <th class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase">Pax</th>
                                <th class="px-3 py-3 text-right text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if(isset($reservations) && count($reservations) > 0)
                                @foreach($reservations as $res)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 text-sm font-medium text-gray-800">
                                        {{ $res->name }} <br>
                                        <span class="text-xs text-gray-400">{{ $res->phone }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($res->booking_date)->format('d M') }} <br>
                                        <span class="font-bold text-[#5f674d]">{{ date('H:i', strtotime($res->booking_time)) }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center text-sm text-gray-600">
                                        {{ $res->pax }} Orang
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" 
                                                    class="px-2 py-1 {{ $res->status == 'Pending' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} text-xs rounded font-bold hover:bg-green-200 transition"
                                                    onclick="
                                                        let message = 'Halo {{ $res->name }}, reservasi Anda di Watu Coffee:\n' +
                                                                    '📅 {{ \Carbon\Carbon::parse($res->booking_date)->format('d M Y') }} {{ date('H:i', strtotime($res->booking_time)) }}\n' +
                                                                    '👥 {{ $res->pax }} Orang\n';
                                                        
                                                        let note = '{{ $res->special_note ? addslashes($res->special_note) : '-' }}';
                                                        if(note !== '-') message += '📝 Note: ' + note + '\n';
                                                        
                                                        let itemsHtml = '';
                                                        @if($res->transaction && $res->transaction->items)
                                                            message += '☕ Pre-order:\n';
                                                            itemsHtml = '<div class=\'text-left mt-3 pt-3 border-t max-h-40 overflow-y-auto\'><h5 class=\'font-bold text-xs mb-2\'>Pre-order Menu:</h5><ul class=\'text-xs space-y-1\'>';
                                                            @foreach($res->transaction->items as $item)
                                                                message += '- {{ $item->quantity }}x {{ $item->product->name }}\n';
                                                                itemsHtml += '<li class=\'flex justify-between\'><span>{{ $item->quantity }}x {{ $item->product->name }}</span><span class=\'font-mono\'>{{ number_format($item->subtotal,0,',','.') }}</span></li>';
                                                            @endforeach
                                                            itemsHtml += '</ul><div class=\'text-right font-bold mt-2 text-xs\'>Total: Rp {{ number_format($res->transaction->total_amount,0,',','.') }}</div></div>';
                                                        @endif
                                                        
                                                        message += '\nKami tunggu kedatangannya!';
                                                        
                                                        let phone = '{{ $res->phone }}';
                                                        if(phone.startsWith('0')) phone = '62' + phone.substring(1);
                                                        
                                                        let waLink = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message);
                                                        
                                                        Swal.fire({
                                                            title: 'Detail Reservasi',
                                                            html: '<div class=\'text-left text-sm\'>' +
                                                                  '<p><strong>Nama:</strong> {{ $res->name }}</p>' +
                                                                  '<p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($res->booking_date)->format('d M Y') }} - {{ date('H:i', strtotime($res->booking_time)) }}</p>' +
                                                                  '<p><strong>Pax:</strong> {{ $res->pax }} Orang</p>' +
                                                                  (note !== '-' ? '<p class=\'mt-2 bg-yellow-50 p-2 rounded text-xs\'><strong>Note:</strong> ' + note + '</p>' : '') +
                                                                  itemsHtml +
                                                                  '</div>',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Konfirmasi (WhatsApp)',
                                                            cancelButtonText: 'Tutup',
                                                            confirmButtonColor: '#25D366'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                window.open(waLink, '_blank');
                                                            }
                                                        });
                                                    ">
                                                Proses
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-400 text-sm italic">
                                        Tidak ada reservasi mendatang.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 250px;">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Pesanan Online (Web)
                    </h3>
                    @if(isset($webOrders))
                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">{{ count($webOrders) }} Pending</span>
                    @endif
                </div>
                <div class="overflow-y-auto overflow-x-auto grow">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @if(isset($webOrders) && count($webOrders) > 0)
                                @foreach($webOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-800">
                                        {{ $order->invoice_number }} <br>
                                        <span class="text-xs text-gray-400">{{ $order->customer_name }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-sm font-bold text-[#5f674d]">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('pos.index', ['order_id' => $order->uuid]) }}" class="p-2 bg-[#5f674d] hover:bg-[#4b523d] text-white rounded transition shadow-sm inline-flex items-center justify-center" title="Proses di POS">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-400 text-sm italic">
                                        Belum ada pesanan online baru.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- GOODS RECEIPT VALIDATION WIDGET (Manager/Owner Only) -->
            @if(isset($pendingReceipts) && count($pendingReceipts) > 0)
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: 250px;">
                <div class="px-6 py-4 border-b border-gray-100 bg-amber-50 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-amber-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Validasi Penerimaan Barang
                    </h3>
                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full">{{ count($pendingReceipts) }} Menunggu</span>
                </div>
                <div class="overflow-y-auto overflow-x-auto grow">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/4">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Supplier</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase w-1/6">Bukti</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase w-1/4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pendingReceipts as $po)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-medium text-gray-800">{{ $po->invoice_number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $po->supplier->name }}</td>
                                <td class="px-6 py-3 text-center">
                                    @if($po->proof_file)
                                        <a href="{{ asset('storage/' . $po->proof_file) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <form id="dash-verify-{{ $po->id }}" action="{{ route('goods-receipt.verify', $po->id) }}" method="POST">
                                        @csrf
                                        <button type="button" 
                                                class="ml-auto flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 hover:bg-green-600 hover:text-white rounded-lg transition-colors duration-200"
                                                title="Validasi (Terima Barang)"
                                                onclick="
                                                    let itemsHtml = `
                                                        <div class='text-left text-sm mb-4'>
                                                            <p><strong>Supplier:</strong> {{ $po->supplier->name }}</p>
                                                            <p><strong>Invoice:</strong> {{ $po->invoice_number }}</p>
                                                            <p class='mt-2'>Silakan cek kesesuaian barang yang diterima dengan pesanan:</p>
                                                        </div>
                                                        <div class='overflow-x-auto border rounded-lg h-40 overflow-y-auto'>
                                                            <table class='w-full text-xs text-left'>
                                                                <thead class='bg-gray-100 font-bold uppercase sticky top-0'>
                                                                    <tr>
                                                                        <th class='px-3 py-2'>Item</th>
                                                                        <th class='px-3 py-2 text-right'>Qty</th>
                                                                        <th class='px-3 py-2 text-right'>Harga</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class='divide-y'>
                                                                    @foreach($po->items as $item)
                                                                    <tr>
                                                                        <td class='px-3 py-2'>{{ $item->product ? $item->product->name : ($item->ingredient ? $item->ingredient->name : 'Unknown') }}</td>
                                                                        <td class='px-3 py-2 text-right font-bold'>{{ $item->quantity }}</td>
                                                                        <td class='px-3 py-2 text-right'>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @if($po->proof_file)
                                                        <div class='mt-4 text-center'>
                                                            <a href='{{ asset('storage/' . $po->proof_file) }}' target='_blank' class='inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-bold text-xs'>
                                                                Lihat Bukti Foto / Faktur
                                                            </a>
                                                        </div>
                                                        @endif
                                                    `;
                                                    
                                                    Swal.fire({
                                                        title: 'Validasi & Cek Barang',
                                                        html: itemsHtml,
                                                        icon: 'info',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Ya, Validasi',
                                                        cancelButtonText: 'Batal',
                                                        width: '600px'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('dash-verify-{{ $po->id }}').submit();
                                                        }
                                                    });
                                                ">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        @if(Auth::user()->role !== 'barista')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex justify-between items-center">
                    <h3 class="font-bold text-red-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Stok Menipis (Perlu Restock)
                    </h3>
                    <a href="{{ route('purchases.create') }}" class="text-xs font-bold text-red-600 hover:underline">Buat PO &rarr;</a>
                </div>
                <div class="p-0">
                    @if(isset($criticalStock) && $criticalStock->count() > 0)
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">Bahan</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase w-1/6">Sisa</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase w-1/6">Min</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase w-1/3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($criticalStock as $item)
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-800">{{ $item->name }}</td>
                                    <td class="px-6 py-3 text-center text-sm font-bold text-red-600">{{ $item->stock }} {{ $item->unit }}</td>
                                    <td class="px-6 py-3 text-center text-sm text-gray-500">{{ $item->minimum_stock }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-bold">Darurat</span>
                                        <a href="{{ route('purchases.create', ['ingredient_id' => $item->id]) }}" 
                                           class="ml-2 inline-flex items-center px-2 py-1 bg-white border border-red-200 rounded text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 transition shadow-sm"
                                           title="Buat PO untuk item ini">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Restock
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-center text-gray-500 text-sm">
                            Semua stok aman.
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col h-full">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Produk Terlaris</h3>
                <ul class="space-y-4 flex-1">
                    @if(isset($bestSellers) && count($bestSellers) > 0)
                        @foreach($bestSellers as $product)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-[#5f674d]/10 flex items-center justify-center text-[#5f674d] font-bold text-xs shrink-0">
                                    #{{ $loop->iteration }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 line-clamp-1">{{ $product->name }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900 shrink-0">{{ $product->total_qty }} Terjual</span>
                        </li>
                        @endforeach
                    @else
                        <li class="text-sm text-gray-400 italic text-center py-4">Belum ada data penjualan.</li>
                    @endif
                </ul>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>