<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pesanan Online') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Search --}}
                    <form method="GET" action="{{ route('web-orders.index') }}" class="mb-6 flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Customer / Invoice..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <button type="submit" class="bg-[#5f674d] hover:bg-[#4a503a] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cari
                        </button>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $order->invoice_number }}
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d M H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <ul class="text-xs text-gray-600 list-disc pl-4">
                                            @foreach($order->items as $item)
                                                <li>{{ $item->product ? $item->product->name : 'Item dihapus' }} ({{ $item->quantity }}x)</li>
                                            @endforeach
                                        </ul>
                                        @if($order->notes)
                                            <div class="text-xs italic bg-yellow-50 p-1 mt-1 rounded text-gray-500">Note: {{ $order->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        
                                        {{-- Edit --}}
                                        <a href="{{ route('web-orders.edit', $order->uuid) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('web-orders.destroy', $order->uuid) }}" method="POST" class="inline-block" data-confirm="Batalkan pesanan ini?">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Batalkan">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                        {{-- Process --}}
                                        <a href="{{ route('pos.index', ['order_id' => $order->uuid]) }}" class="inline-flex items-center px-3 py-1 bg-[#5f674d] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4b523d] active:bg-[#4b523d] focus:outline-none focus:border-[#4b523d] focus:ring ring-[#4b523d] disabled:opacity-25 transition ease-in-out duration-150">
                                            Proses POS
                                        </a>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        Tidak ada pesanan online baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
