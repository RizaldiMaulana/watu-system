<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Reservasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Filters --}}
                    <form method="GET" action="{{ route('reservations.index') }}" class="mb-6 flex gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Semua Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-[#5f674d] hover:bg-[#4a503a] text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Filter
                            </button>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="overflow-visible min-h-[500px]"> {{-- Changed from overflow-x-auto to overflow-visible and added min-height to allow dropdown expansion --}}
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pax</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note & Pre-order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservations as $res)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $res->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $res->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($res->booking_date)->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ date('H:i', strtotime($res->booking_time)) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $res->pax }} Orang
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($res->special_note)
                                            <div class="text-xs italic bg-yellow-50 p-1 mb-1 rounded">Note: {{ $res->special_note }}</div>
                                        @endif
                                        @if($res->transaction)
                                            <div class="text-xs font-bold text-blue-600">
                                                Pre-order: Rp {{ number_format($res->transaction->total_amount, 0, ',', '.') }}
                                                <span class="text-gray-400 font-normal">({{ $res->transaction->payment_status }})</span>
                                            </div>
                                            <ul class="text-[10px] text-gray-500 mt-1">
                                                @foreach($res->transaction->items as $item)
                                                    <li>- {{ $item->quantity }}x {{ $item->product->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if(!$res->special_note && !$res->transaction)
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $res->status == 'Confirmed' || $res->status == 'Completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $res->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $res->status == 'Cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $res->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        
                                        {{-- WhatsApp Button --}}
                                        <button type="button" 
                                                class="text-green-600 hover:text-green-900 mr-2"
                                                onclick="
                                                    let message = 'Halo {{ $res->name }}, reservasi Anda di Watu Coffee:\n' +
                                                                '📅 {{ \Carbon\Carbon::parse($res->booking_date)->format('d M Y') }} {{ date('H:i', strtotime($res->booking_time)) }}\n' +
                                                                '👥 {{ $res->pax }} Orang\n';
                                                    window.open('https://wa.me/{{ preg_replace('/^0/', '62', $res->phone) }}?text=' + encodeURIComponent(message), '_blank');
                                                "
                                                title="WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-2xl"></i> {{-- Bigger Icon --}}
                                        </button>

                                        {{-- Actions Dropdown (Alpine) --}}
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600 focus:outline-none p-2 rounded-full hover:bg-gray-100 transition-colors">
                                                <i class="fa-solid fa-ellipsis-vertical text-2xl"></i>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                                                <div class="py-1">
                                                    {{-- Process to POS --}}
                                                    <a href="{{ route('reservations.process_pos', $res->id) }}" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50 font-bold">
                                                        <i class="fa-solid fa-cash-register mr-1"></i> Proses di Kasir
                                                    </a>
                                                    
                                                    <div class="border-t border-gray-100 my-1"></div>

                                                    {{-- Edit --}}
                                                    <a href="{{ route('reservations.edit', $res->id) }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Edit Detail
                                                    </a>

                                                    {{-- Status Updates --}}
                                                    @if($res->status != 'Confirmed')
                                                    <form action="{{ route('reservations.updateStatus', $res->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="Confirmed">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mark Confirmed</button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if($res->status != 'Completed')
                                                    <form action="{{ route('reservations.updateStatus', $res->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="Completed">
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mark Completed</button>
                                                    </form>
                                                    @endif

                                                    <div class="border-t border-gray-100 my-1"></div>

                                                    {{-- Delete --}}
                                                    <form action="{{ route('reservations.destroy', $res->id) }}" method="POST" onsubmit="return confirm('Hapus reservasi ini?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        Tidak ada data reservasi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                     <div class="mt-4">
                        {{ $reservations->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
