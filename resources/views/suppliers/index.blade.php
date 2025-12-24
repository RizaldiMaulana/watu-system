<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 leading-tight">{{ __('Data Supplier') }}</h2>
            <a href="{{ route('suppliers.create') }}" class="px-4 py-2 bg-[#5f674d] text-white rounded-lg text-sm font-bold hover:bg-[#4b523d] flex items-center gap-2">
                + Tambah Supplier
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Telepon</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $supplier->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $supplier->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-xs">{{ $supplier->address ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-[#d4a056] hover:text-[#b58440] mr-3">Edit</a>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" data-confirm="Hapus supplier ini?">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>