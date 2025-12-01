<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Watu System - {{ isset($header) ? strip_tags($header) : (auth()->check() ? ucwords(str_replace('_', ' ', auth()->user()->role)) : 'Guest') }}</title>

    <link rel="icon" href="{{ asset('images/LOGO Produk.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Scrollbar Styling agar rapi */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="font-sans antialiased bg-watu-cream text-watu-dark">
    <div class="min-h-screen flex"> @include('layouts.navigation')

        <main class="flex-1 h-screen overflow-y-auto">
            @if (isset($header))
                <header class="bg-watu-cream/90 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-30">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <div class="py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>