@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Smart Restock Recommendation</h2>
                        <p class="text-sm text-gray-500">Decision Support System (SAW Method)</p>
                    </div>
                    <div class="text-right text-xs text-gray-500">
                        <p>Criteria Weights:</p>
                        <p><span class="font-semibold text-red-500">Stock (Low)</span>: 40% | <span class="font-semibold text-green-500">Sales (High)</span>: 40% | <span class="font-semibold text-blue-500">Margin (High)</span>: 20%</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock
                                    <span class="block text-[10px] lowercase text-red-400 font-light">cost attr</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sales (30d)
                                    <span class="block text-[10px] lowercase text-green-400 font-light">benefit attr</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Margin
                                    <span class="block text-[10px] lowercase text-blue-400 font-light">benefit attr</span>
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-800 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rankings as $index => $item)
                            <tr class="@if($index < 5) bg-red-50 @endif hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-gray-100 text-gray-600' }} font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['category'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm {{ $item['stock'] <= 5 ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $item['stock'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $item['sales_30d'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    Rp {{ number_format($item['margin'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-blue-600">
                                    {{ number_format($item['score'], 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('purchases.create', ['product_id' => $item['id']]) }}" class="text-indigo-600 hover:text-indigo-900">Restock</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
