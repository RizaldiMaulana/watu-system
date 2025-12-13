@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Promo</h2>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-w-lg">
            <form action="{{ route('promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nama Promo</label>
                    <input type="text" name="name" value="{{ $promotion->name }}" required class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Tipe Diskon</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">
                        <option value="percentage" {{ $promotion->type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ $promotion->type == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nilai Diskon</label>
                    <input type="number" name="value" value="{{ $promotion->value }}" required min="0" step="0.01" class="w-full rounded-lg border-gray-300 focus:ring-[#5f674d] focus:border-[#5f674d]">
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ $promotion->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-[#5f674d] shadow-sm focus:border-[#5f674d] focus:ring-[#5f674d]">
                        <span class="ml-2 text-gray-700 font-bold">Status Aktif</span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-[#5f674d] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#4a503a] transition flex-1">
                        Update Promo
                    </button>
                    <a href="{{ route('promotions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300 transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
