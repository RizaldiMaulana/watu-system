<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laporan' }} - Watu Coffee System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
            /* Force background colors */
            .bg-gray-100 { background-color: #f3f4f6 !important; }
            .bg-gray-50 { background-color: #f9fafb !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto bg-white p-8 shadow-sm print:shadow-none print:max-w-none print:p-0">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6 border-b pb-6">
            <div class="flex items-center gap-4">
                {{-- <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto" alt="Logo"> --}}
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 uppercase">{{ $title ?? 'Laporan' }}</h1>
                    <p class="text-gray-500 text-sm mt-1">Watu Coffee System</p>
                    <p class="text-xs text-gray-400">Jl. Contoh No. 123, Semarang</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-700">Tanggal Cetak</p>
                <p class="text-gray-500 text-sm">{{ date('d M Y H:i') }}</p>
                <p class="text-xs text-gray-400 mt-1">Oleh: {{ Auth::user()->name ?? 'System' }}</p>
            </div>
        </div>

        <!-- Subtitle / Filter Info -->
        @if(isset($subtitle))
        <div class="mb-6 bg-gray-50 p-3 rounded border border-gray-100 text-center">
            <p class="font-bold text-gray-700">{{ $subtitle }}</p>
        </div>
        @endif

        <!-- Content -->
        <div class="mb-8 min-h-[400px]">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 flex justify-between items-end">
            <div class="text-xs text-gray-400 italic">
                Dicetak otomatis oleh Watu System
            </div>
            <!-- Signature Area (Optional) -->
            <div class="text-center w-40">
                <div class="h-16 border-b border-gray-300 mb-2"></div>
                <p class="text-xs font-bold text-gray-600">Disetujui Oleh</p>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-8 right-8 no-print flex gap-4">
        <!-- Export Excel Trigger (Just downloads HTML as XLS) -->
        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="px-6 py-3 bg-green-600 text-white font-bold rounded-full shadow-lg hover:bg-green-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </a>

        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2M7 7h10a2 2 0 012 2v2M7 7H5a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Print PDF
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-full shadow-lg hover:bg-gray-700 transition">
            Tutup
        </button>
    </div>

</body>
</html>
