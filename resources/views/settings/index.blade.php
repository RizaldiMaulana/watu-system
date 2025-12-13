@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Sistem</h2>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-w-lg">
             <div class="text-center py-6">
                <svg class="w-16 h-16 text-[#5f674d] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Pengaturan Pajak Dipindahkan</h3>
                <p class="text-gray-500 mb-6">Pengelolaan pajak dan layanan (Service Charge) kini dapat diakses melalui menu Akuntansi.</p>
                <a href="{{ route('taxes.index') }}" class="inline-flex items-center gap-2 bg-[#5f674d] text-white px-6 py-2 rounded-lg font-bold hover:bg-[#4a503a] transition">
                    <span>Ke Halaman Pajak</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
             </div>
        </div>
    </div>
</div>
@endsection
