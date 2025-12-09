<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Watu Coffee</title>
        <link rel="icon" href="{{ asset('images/LOGO Produk.png') }}" type="image/png">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

        <!-- Shared Watu Theme -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- Tailwind (for Breeze components if needed) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Guest Specific Overrides */
            body { 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                min-height: 100vh;
            }

            .login-container {
                width: 100%;
                max-width: 480px;
                position: relative;
                z-index: 10;
            }

            .login-card {
                /* Inheriting Glassmorphism from .form-card in style.css */
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.8);
                padding: 3rem 2.5rem;
                border-radius: 24px;
                box-shadow: var(--shadow-xl);
                animation: fadeInUp 0.8s ease;
            }

            .logo-section h1 {
                font-size: 2.2rem;
                margin-bottom: 0.2rem;
                color: var(--color-dark);
            }

            .login-btn {
                background: var(--gradient-olive);
                color: white;
                font-weight: 700;
                letter-spacing: 1px;
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            
            .login-btn:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="login-container">
            <!-- Logo Section -->
            <div class="logo-section">
                <a href="/" class="inline-block" style="text-decoration:none;">
                    <img src="{{ asset('images/LOGO Produk.png') }}" alt="Watu Logo" style="height: 80px; width: auto; margin-bottom: 1rem;" />
                    <h1 class="font-serif">Watu Coffee</h1>
                    <span class="subtitle" style="font-size: 0.8rem; letter-spacing: 3px; color: var(--color-olive); text-transform: uppercase; font-weight: 600;">Coffee & Roastery</span>
                </a>
            </div>

            <!-- Login Card -->
            <div class="login-card">
                <div style="width: 60px; height: 4px; background: var(--gradient-olive); margin: 0 auto 2rem; border-radius: 2px;"></div>

                <!-- Session Status -->
                @if (session('status'))
                    <div style="background: rgba(95, 103, 77, 0.1); border: 1px solid var(--color-olive); color: var(--color-olive-dark); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="footer-text font-serif" style="text-align: center; margin-top: 2rem; font-size: 0.8rem; color: #888; font-style: italic;">
                &copy; {{ date('Y') }} Watu Coffee System. All rights reserved.
            </div>
        </div>

        <style>
            /* Additional Form Styles matching Theme */
            .form-group { margin-bottom: 1.5rem; }
            
            label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--color-dark);
                margin-bottom: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            input[type="email"], input[type="password"], input[type="text"] {
                width: 100%;
                padding: 0.9rem 1.2rem;
                border: 2px solid #e0e0e0;
                border-radius: 12px;
                font-size: 1rem;
                transition: all 0.3s ease;
                font-family: 'Poppins', sans-serif;
            }

            input:focus {
                border-color: var(--color-olive);
                outline: none;
                box-shadow: 0 0 0 4px rgba(95, 103, 77, 0.1);
            }

            .checkbox-container { display: flex; align-items: center; margin: 1.5rem 0; }
            input[type="checkbox"] { 
                width: 18px; height: 18px; margin-right: 0.5rem; accent-color: var(--color-olive); cursor: pointer;
            }
            
            .forgot-link {
                display: block; text-align: center; margin-top: 1.5rem;
                color: var(--color-olive); text-decoration: none; font-weight: 600; font-size: 0.9rem;
            }
            .forgot-link:hover { text-decoration: underline; color: var(--color-olive-dark); }
            
            .error-message { color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem; }
            
            /* Button Override if class is used in slot */
            .btn-primary {
                width: 100%;
                padding: 1rem;
                background: var(--gradient-olive);
                color: white;
                border: none;
                border-radius: 50px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                cursor: pointer;
                transition: all 0.3s;
                box-shadow: var(--shadow-md);
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }
        </style>
    </body>
</html>