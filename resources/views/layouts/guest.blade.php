<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Watu Coffee</title>
        <link rel="icon" href="{{ asset('images/LOGO Produk.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #F9F7F2;
                background-image: url("{{ asset('images/Bg.png') }}");
                background-size: 400px;
                background-blend-mode: multiply;
            }
            .font-serif { font-family: 'Playfair Display', serif; }
            
            /* Card Glass Effect */
            .login-card {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 20px 40px rgba(95, 103, 77, 0.1);
                padding: 2rem;
                border-radius: 1.5rem;
            }

        </style>
    </head>
    <body class="text-gray-900 antialiased min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
        
        <div class="mb-8  text-center">
            <a href="/" class="flex flex-col items-center group">
                <img src="{{ asset('images/LOGO Produk.png') }}" 
                     alt="Watu Logo" 
                     class="w-auto drop-shadow-sm transition transform group-hover:scale-105 duration-300" 
                     style="height: 80px; width: auto;" /> 
                
                <h1 class="mt-4 text-3xl font-serif font-bold text-[#2b2623]">Watu Coffee</h1>
                <span class="text-xs tracking-[0.3em] text-[#5f674d] uppercase mt-1 font-medium">Coffee & Roastery</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 login-card overflow-hidden sm:rounded-3xl">
            <div class="w-16 h-1 bg-[#5f674d] mx-auto mb-8 rounded-full"></div>
            
            {{ $slot }}
        </div>
        
        <div class="mt-8 text-center text-xs text-gray-500 font-serif italic">
            &copy; {{ date('Y') }} Watu Coffee System.
        </div>
    </body>
</html>