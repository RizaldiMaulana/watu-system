<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Watu System -
        {{ isset($header) ? strip_tags($header) : (auth()->check() ? ucwords(str_replace('_', ' ', auth()->user()->role)) : 'Guest') }}
    </title>

    <link rel="icon" href="{{ asset(setting('logo_navigation', 'images/LOGO Produk.png')) }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- PWA Manifest & Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#5f674d">
    <link rel="apple-touch-icon" href="{{ asset(setting('logo_navigation', 'images/LOGO Produk.png')) }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset('sw.js') }}');
        }
    </script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary:
                {{ setting('theme_color_primary', '#5f674d') }}
            ;
            --font-main: '{{ setting('theme_font_family', 'Nunito') }}', sans-serif;
        }

        body {
            font-family: var(--font-main);
        }

        /* Custom Theme Utility Classes (Replacing Tailwind Arbitrary Values) */
        .bg-primary {
            background-color: var(--color-primary) !important;
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .border-primary {
            border-color: var(--color-primary) !important;
        }

        .hover\:text-primary:hover {
            color: var(--color-primary) !important;
        }

        .hover\:bg-primary:hover {
            background-color: var(--color-primary) !important;
        }

        .group:hover .group-hover\:text-primary {
            color: var(--color-primary) !important;
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary) 50%, transparent) !important;
        }

        .focus\:border-primary:focus {
            border-color: var(--color-primary) !important;
        }

        .bg-primary-5 {
            background-color: color-mix(in srgb, var(--color-primary) 8%, transparent) !important;
        }

        .shadow-primary-20 {
            box-shadow: 0 4px 6px -1px color-mix(in srgb, var(--color-primary) 40%, transparent) !important;
        }

        /* Scrollbar Styling agar rapi */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="antialiased bg-watu-cream text-watu-dark">
    <div class="min-h-screen bg-gray-100 flex" x-data="{ 
             sidebarOpen: false, 
             sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
         }" x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
        @open-sidebar.window="sidebarOpen = true">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden" style="display: none;"></div>

        @include('layouts.navigation')

        <main class="flex-1 h-screen overflow-y-auto transition-all duration-300 ease-in-out"
            :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'"
            :style="'margin-left: ' + (window.innerWidth >= 768 ? (sidebarCollapsed ? '5rem' : '16rem') : '0')">
            <header
                class="bg-watu-cream/90 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-30 flex items-center">
                <!-- Mobile Menu Button (only visible on mobile) -->
                <button @click="sidebarOpen = true"
                    class="md:hidden p-4 text-gray-500 hover:text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @if (isset($header))
                    <div class="max-w-7xl flex-1 py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @else
                    <div class="flex-1"></div>
                @endif

                <!-- Notification Bell (Moved to Top Right Header) -->
                <div class="relative mr-4 sm:mr-6" x-data="{ 
                    notifOpen: false, 
                    count: 0, 
                    list: [],
                    init() {
                        this.fetchNotifs();
                        setInterval(() => this.fetchNotifs(), 30000); // Poll every 30s
                    },
                    fetchNotifs() {
                        fetch('{{ route('notifications.index') }}')
                            .then(res => res.json())
                            .then(data => {
                                this.count = data.unread_count;
                                this.list = data.notifications;
                            });
                    },
                    markAllRead() {
                        fetch('{{ route('notifications.read-all') }}', { 
                            method: 'POST', 
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                        }).then(() => {
                            this.count = 0;
                            this.list = [];
                        });
                    }
                }">
                    <button @click="notifOpen = !notifOpen"
                        class="relative p-2 text-gray-500 hover:text-[#5f674d] transition-transform hover:scale-105">
                        <!-- BIGGER ICON (w-8 h-8) -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span x-show="count > 0" x-text="count"
                            class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white"></span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="notifOpen" @click.away="notifOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl overflow-hidden z-50 border border-gray-100 ring-1 ring-black ring-opacity-5"
                        style="display: none;">

                        <div
                            class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 backdrop-blur-sm">
                            <h3 class="text-sm font-bold text-gray-700">Notifikasi</h3>
                            <button @click="markAllRead()"
                                class="text-xs font-medium text-[#5f674d] hover:text-[#4a503a] hover:underline">Tandai
                                semua dibaca</button>
                        </div>

                        <div class="max-h-[20rem] overflow-y-auto custom-scrollbar">
                            <template x-for="item in list" :key="item.id">
                                <a :href="`{{ url('notifications') }}/${item.id}/read`"
                                    class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors group">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 pt-1">
                                            <div class="w-9 h-9 rounded-full flex items-center justify-center shadow-sm"
                                                :class="{
                                                    'bg-red-50 text-red-600': item.color === 'red',
                                                    'bg-green-50 text-green-600': item.color === 'green',
                                                    'bg-blue-50 text-blue-600': item.color === 'blue',
                                                    'bg-yellow-50 text-yellow-600': item.color === 'yellow',
                                                    'bg-gray-50 text-gray-600': item.color === 'gray'
                                                 }">
                                                <i class="fas fa-bell text-sm" x-show="!item.icon"></i>
                                                <!-- Fallback -->
                                                <!-- Dynamic Icon using FontAwesome classes if passed, or generic SVG -->
                                                <i :class="`fas fa-${item.icon}`" class="text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-800 group-hover:text-[#5f674d] transition-colors"
                                                x-text="item.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="item.message">
                                            </p>
                                            <p class="text-[10px] text-gray-400 mt-1 font-medium"
                                                x-text="item.created_at"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                            <div x-show="list.length === 0"
                                class="px-4 py-8 text-center flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Tidak ada notifikasi baru</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="py-6 px-4 sm:px-6 lg:px-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </main>
    </div>
    <!-- SweetAlert2 Integration -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Notification System (Toast)
        window.addEventListener('notify', event => {
            const data = event.detail;

            // Toasts for standard notifications
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: data.type || 'success',
                title: data.message // SweetAlert Toast uses 'title' for the main text often
            });
        });

        // Global Confirmation System
        window.addEventListener('confirm', event => {
            const data = event.detail;

            Swal.fire({
                title: data.title || 'Konfirmasi',
                text: data.message || 'Apakah Anda yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--color-primary)', // Menggunakan warna tema Watu (Olive)
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof data.action === 'function') {
                        data.action();
                    }
                }
            });
        });

        // Handle Session Flash Messages from Backend
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: 'var(--color-primary)'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: 'var(--color-primary)'
            });
        @endif


        // Global Confirmation Handler for 'data-confirm' attribute
        document.addEventListener('click', function (e) {
            let target = e.target.closest('[data-confirm]');
            if (!target) return;

            e.preventDefault();
            const message = target.getAttribute('data-confirm');

            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--color-primary)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (target.tagName === 'FORM') {
                        target.removeAttribute('data-confirm');
                        target.submit();
                    } else if (target.tagName === 'A') {
                        window.location.href = target.href;
                    } else if (target.tagName === 'BUTTON' && target.type === 'submit') {
                        // Find closest form and submit
                        const form = target.closest('form');
                        if (form) {
                            form.submit();
                        }
                    } else if (target.tagName === 'BUTTON' || target.tagName === 'INPUT') {
                        // Generic button/input, just clicking it programmatically might loop if not careful? 
                        // For now, assume mainly Forms and Links use this.
                        // If it's a submit button inside a form w/o form logic, handled above.
                    }
                }
            });
        });

        // Handle Forms with data-confirm directly on the form tag
        document.addEventListener('submit', function (e) {
            if (e.target.hasAttribute('data-confirm')) {
                e.preventDefault();
                const message = e.target.getAttribute('data-confirm');
                const form = e.target;

                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--color-primary)',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.removeAttribute('data-confirm');
                        form.submit();
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Request Notification Permission on load
            if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
                Notification.requestPermission();
            }

            // Polling for Device Notifications (Popups)
            setInterval(() => {
                if (Notification.permission === 'granted') {
                    fetch('{{ route("notifications.check") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Only trigger popup if there is a NEW notification (logic handled by backend check or by comparing IDs)
                            // For simplicity in this poller, we just show the latest if it's unread.
                            // Ideally, backend should flag 'pushed' state. 
                            // But here we'll just check if we have data.
                            if (data.count > 0 && data.latest) {
                                // prevent spamming the same notification loop?
                                // Simple client-side prevention: store ID
                                const lastId = localStorage.getItem('last_notif_id');
                                if (data.latest.url && lastId != data.latest.url) { // using URL as proxy for ID if needed or add ID to check response
                                    const notification = new Notification(data.latest.title, {
                                        body: data.latest.message,
                                        icon: '/{{ setting('logo_navigation', 'images/LOGO Produk.png') }}'
                                    });
                                    notification.onclick = function () {
                                        window.open(data.latest.url, '_blank');
                                    };
                                    localStorage.setItem('last_notif_id', data.latest.url);
                                }
                            }
                        })
                        .catch(error => console.error('Error checking notifications:', error));
                }
            }, 30000); // Check every 30s
        });
    </script>
</body>

@auth
    @if(!auth()->user()->has_seen_onboarding)
        {{-- ===== ONBOARDING OVERLAY (New User Only) ===== --}}
        <div id="onboardingOverlay" x-data="systemOnboarding()" x-show="active"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">

            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100">

                <!-- Color Bar Top -->
                <div class="h-1.5 bg-gradient-to-r from-[#5f674d] via-[#8a9a6d] to-[#5f674d]"></div>

                <!-- Header -->
                <div class="bg-[#5f674d] px-7 py-6 text-white">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Langkah <span
                                    x-text="step + 1"></span> dari <span x-text="steps.length"></span></p>
                            <h3 class="text-xl font-bold leading-snug" x-text="steps[step].title"></h3>
                        </div>
                        <span class="text-3xl mt-1 shrink-0" x-text="steps[step].icon"></span>
                    </div>
                    <!-- Progress -->
                    <div class="mt-4 grid gap-1" :style="'grid-template-columns: repeat(' + steps.length + ', 1fr)'">
                        <template x-for="(s, i) in steps" :key="i">
                            <div :class="i <= step ? 'bg-white' : 'bg-white/25'"
                                class="h-1 rounded-full transition-all duration-500"></div>
                        </template>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-7">
                    <p class="text-gray-700 text-sm leading-relaxed mb-5" x-text="steps[step].desc"></p>

                    <!-- Tip -->
                    <div x-show="steps[step].tip" x-transition
                        class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3 text-sm text-amber-800">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <span x-text="steps[step].tip"></span>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between mt-7">
                        <button @click="prev()" :disabled="step === 0"
                            class="text-sm text-gray-500 hover:text-gray-700 disabled:opacity-30 flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </button>

                        <div class="flex gap-1.5 items-center">
                            <template x-for="(s, i) in steps" :key="i">
                                <button @click="step = i"
                                    :class="step === i ? 'bg-[#5f674d] w-5' : 'bg-gray-200 hover:bg-gray-300 w-1.5'"
                                    class="h-1.5 rounded-full transition-all duration-300"></button>
                            </template>
                        </div>

                        <button @click="next()"
                            class="flex items-center gap-2 bg-[#5f674d] text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#4a503a] transition-all active:scale-95">
                            <span x-text="step < steps.length - 1 ? 'Lanjut' : 'Mulai Gunakan!'"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    :d="step < steps.length - 1 ? 'M9 5l7 7-7 7' : 'M5 13l4 4L19 7'" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-xs text-center text-gray-400 mt-4">
                        Panduan ini bisa dibuka kembali kapan saja melalui menu
                        <a href="{{ route('help.index') }}" class="text-[#5f674d] font-semibold hover:underline">Panduan</a> di
                        sidebar.
                    </p>
                </div>
            </div>
        </div>

        <script>
            function systemOnboarding() {
                return {
                    active: true,
                    step: 0,
                    steps: [
                        { icon: '👋', title: 'Selamat Datang di Watu System!', desc: 'Panduan singkat ini akan memandu Anda mengenal dan menggunakan sistem manajemen kafe ini. Hanya membutuhkan beberapa menit.', tip: 'Anda bisa melewati panduan ini dan membukannya kembali kapan saja lewat menu "Panduan" di sidebar.' },
                        { icon: '🏠', title: 'Dashboard', desc: 'Dashboard adalah halaman utama Anda. Di sini Anda bisa melihat ringkasan penjualan hari ini, stok kritis, dan tren bisnis secara real-time.', tip: 'Klik pada kartu angka di Dashboard untuk langsung masuk ke halaman detailnya.' },
                        { icon: '🖥️', title: 'Mesin Kasir (POS)', desc: 'Buka Mesin Kasir untuk memproses transaksi pelanggan. Klik produk → atur jumlah → klik Bayar. Struk bisa langsung dicetak atau dikirim.', tip: 'Gunakan fitur "Reservasi" di POS untuk meja yang memesan terlebih dahulu.' },
                        { icon: '📦', title: 'Stok & Pengadaan', desc: 'Kelola stok bahan baku dan produk dari menu ini. Buat Purchase Order ke supplier, terima barang, dan pantau pergerakan stok secara akurat.', tip: 'Aktifkan batas stok minimum agar sistem memberi peringatan otomatis saat stok kritis.' },
                        { icon: '📊', title: 'Laporan & Akuntansi', desc: 'Lihat laporan penjualan harian, laporan keuangan (Laba Rugi, Neraca), dan kelola hutang piutang dari menu Laporan & Akuntansi.', tip: 'Laporan bisa diekspor ke PDF atau Excel untuk keperluan pembukuan dan pajak.' },
                        { icon: '⚙️', title: 'Pengaturan Sistem', desc: 'Di menu Pengaturan, ubah nama kafe, upload logo, sesuaikan tema warna, dan edit konten website publik kafe Anda sesuai identitas brand.', tip: 'Buka tab Panduan Setup di halaman Pengaturan untuk tur khusus setup awal sistem.' },
                        { icon: '🚀', title: 'Siap Memulai!', desc: 'Anda sudah mengenal semua fitur utama sistem. Jika ada pertanyaan atau lupa cara penggunaan fitur tertentu, buka menu "Panduan" di sidebar kapan saja.', tip: null },
                    ],
                    next() {
                        if (this.step < this.steps.length - 1) {
                            this.step++;
                        } else {
                            this.finish();
                        }
                    },
                    prev() {
                        if (this.step > 0) this.step--;
                    },
                    finish() {
                        this.active = false;
                        fetch('{{ route('onboarding.done') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                        });
                    }
                }
            }
        </script>
    @endif
@endauth

</html>