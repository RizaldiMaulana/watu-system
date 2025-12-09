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
    <div class="min-h-screen bg-gray-100 flex" 
         x-data="{ 
             sidebarOpen: false, 
             sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
         }"
         x-init="$watch('sidebarCollapsed', value => localStorage.setItem('sidebarCollapsed', value))"
         @open-sidebar.window="sidebarOpen = true">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden" 
             style="display: none;"></div>

        @include('layouts.navigation')

        <main class="flex-1 h-screen overflow-y-auto transition-all duration-300 ease-in-out"
              :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'"
              :style="'margin-left: ' + (window.innerWidth >= 768 ? (sidebarCollapsed ? '5rem' : '16rem') : '0')">
            <header class="bg-watu-cream/90 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-30 flex items-center">
                <!-- Mobile Menu Button (only visible on mobile) -->
                <button @click="sidebarOpen = true" class="md:hidden p-4 text-gray-500 hover:text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @if (isset($header))
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex-1">
                        {{ $header }}
                    </div>
                @endif
            </header>

            <div class="py-6 px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    <!-- Global Notification System -->
    <div x-data="{ 
            notifications: [],
            add(e) {
                this.notifications.push({
                    id: Date.now(),
                    title: e.detail.title || 'Info',
                    message: e.detail.message,
                    type: e.detail.type || 'success',
                    visible: true
                });
                setTimeout(() => { this.remove(this.notifications.length - 1) }, 3000);
            },
            remove(index) {
                this.notifications[index].visible = false;
                setTimeout(() => { 
                    this.notifications = this.notifications.filter((n, i) => i !== index); 
                }, 300);
            }
         }"
         @notify.window="add($event)"
         class="fixed top-24 left-1/2 -translate-x-1/2 z-[10000] flex flex-col gap-3 pointer-events-none items-center">
        
        <template x-for="(note, index) in notifications" :key="note.id">
            <div x-show="note.visible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="pointer-events-auto bg-white/90 backdrop-blur-md px-4 py-3 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] border border-white/50 min-w-[320px] max-w-sm flex items-start gap-3 transform transition-all hover:scale-102 hover:shadow-[0_12px_40px_rgba(0,0,0,0.15)]">
                
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-inner"
                     :class="{
                         'bg-green-100 text-green-600': note.type === 'success',
                         'bg-red-100 text-red-600': note.type === 'error',
                         'bg-blue-100 text-blue-600': note.type === 'info',
                         'bg-amber-100 text-amber-600': note.type === 'warning'
                     }">
                     <template x-if="note.type === 'success'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     </template>
                     <template x-if="note.type === 'error'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                     </template>
                </div>
                
                <div class="flex-1 pt-0.5">
                    <h4 class="font-bold text-sm text-gray-800" x-text="note.title"></h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed" x-text="note.message"></p>
                </div>

                <button @click="remove(index)" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Global Confirmation Modal -->
    <div x-data="{ 
            open: false,
            title: 'Konfirmasi',
            message: 'Apakah Anda yakin?',
            action: null,
            confirm() {
                if (this.action) this.action();
                this.open = false;
            }
         }"
         @confirm.window="
            open = true; 
            title = $event.detail.title || 'Konfirmasi'; 
            message = $event.detail.message || 'Apakah Anda yakin?'; 
            action = $event.detail.action;
         ">
        
        <template x-teleport="body">
            <div class="relative z-[10001]" 
                 aria-labelledby="modal-title" 
                 role="dialog" 
                 aria-modal="true" 
                 x-show="open"
                 style="display: none;">
                
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     x-show="open"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"></div>

                <div class="fixed inset-0 z-[10001] w-screen overflow-y-auto">
                    <div class="flex min-h-full items-center justify-center p-4 text-center">
                        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-lg"
                             @click.away="open = false"
                             x-show="open"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title" x-text="title"></h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500" x-text="message"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                <button type="button" 
                                        class="inline-flex w-full justify-center rounded-lg bg-[#5f674d] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:outputs-[#4b523d] sm:ml-3 sm:w-auto transition-colors"
                                        @click="confirm()">
                                    Konfirmasi
                                </button>
                                <button type="button" 
                                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors"
                                        @click="open = false">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

</body>
</html>