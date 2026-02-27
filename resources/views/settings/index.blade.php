@extends('layouts.app')

@section('content')
    <div class="py-8" x-data="settingsTabs()" x-init="initOnboarding()">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ __('Pengaturan') }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola identitas, tampilan, dan konten sistem Anda.</p>
                </div>
                <button @click="startOnboarding()"
                    class="flex items-center gap-2 text-sm font-medium text-[#5f674d] border border-[#5f674d]/30 px-4 py-2 rounded-lg hover:bg-[#5f674d]/5 transition-all"
                    title="Panduan Pengaturan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Panduan Setup
                </button>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex border-b border-gray-100 overflow-x-auto scrollbar-none">
                    <button @click="activeTab = 'identitas'"
                        :class="activeTab === 'identitas' ? 'border-b-2 border-[#5f674d] text-[#5f674d] bg-[#5f674d]/5 font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Identitas & Logo
                    </button>
                    <button @click="activeTab = 'tampilan'"
                        :class="activeTab === 'tampilan' ? 'border-b-2 border-[#5f674d] text-[#5f674d] bg-[#5f674d]/5 font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                            </path>
                        </svg>
                        Tampilan & Bahasa
                    </button>
                    <button @click="activeTab = 'web'"
                        :class="activeTab === 'web' ? 'border-b-2 border-[#5f674d] text-[#5f674d] bg-[#5f674d]/5 font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                            </path>
                        </svg>
                        Konten Web Publik
                    </button>
                    <button @click="activeTab = 'media'"
                        :class="activeTab === 'media' ? 'border-b-2 border-[#5f674d] text-[#5f674d] bg-[#5f674d]/5 font-semibold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-6 py-4 text-sm transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Media & Slider
                    </button>
                </div>

                <!-- Tab Content inside one form -->
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- TAB: Identitas & Logo -->
                    <div x-show="activeTab === 'identitas'" x-transition class="p-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Perusahaan /
                                    Cafe</label>
                                <input type="text" name="company_name"
                                    value="{{ $settings['company_name'] ?? 'Watu Cafe & Roastery' }}"
                                    class="w-full rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] text-sm shadow-sm"
                                    placeholder="Masukkan nama perusahaan...">
                                <p class="text-xs text-gray-400 mt-1">Nama ini akan tampil di navigasi dan dokumen.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <!-- Logo Navigasi -->
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Navigasi &
                                        POS</label>
                                    @if(isset($settings['logo_navigation']))
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex justify-center">
                                            <img src="{{ asset($settings['logo_navigation']) }}" alt="Logo Navigasi"
                                                class="h-12 object-contain">
                                        </div>
                                    @else
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex items-center justify-center h-16 text-gray-300 text-xs">
                                            Belum ada logo
                                        </div>
                                    @endif
                                    <input type="file" name="logo_navigation" accept="image/*"
                                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5f674d]/10 file:text-[#5f674d] hover:file:bg-[#5f674d]/20">
                                </div>

                                <!-- Logo Invoice -->
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Struk /
                                        Invoice</label>
                                    @if(isset($settings['logo_invoice']))
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex justify-center">
                                            <img src="{{ asset($settings['logo_invoice']) }}" alt="Logo Invoice"
                                                class="h-12 object-contain">
                                        </div>
                                    @else
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex items-center justify-center h-16 text-gray-300 text-xs">
                                            Belum ada logo
                                        </div>
                                    @endif
                                    <input type="file" name="logo_invoice" accept="image/*"
                                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5f674d]/10 file:text-[#5f674d] hover:file:bg-[#5f674d]/20">
                                </div>

                                <!-- Logo Website -->
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Website
                                        Publik</label>
                                    @if(isset($settings['logo_website']))
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex justify-center">
                                            <img src="{{ asset($settings['logo_website']) }}" alt="Logo Website"
                                                class="h-12 object-contain">
                                        </div>
                                    @else
                                        <div
                                            class="mb-3 bg-white p-3 rounded-lg border border-dashed border-gray-200 flex items-center justify-center h-16 text-gray-300 text-xs">
                                            Belum ada logo
                                        </div>
                                    @endif
                                    <input type="file" name="logo_website" accept="image/*"
                                        class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#5f674d]/10 file:text-[#5f674d] hover:file:bg-[#5f674d]/20">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Tampilan & Bahasa -->
                    <div x-show="activeTab === 'tampilan'" x-transition class="p-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bahasa Sistem</label>
                                <select name="language"
                                    class="w-full rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] text-sm shadow-sm">
                                    <option value="id" {{ ($settings['language'] ?? 'id') == 'id' ? 'selected' : '' }}>🇮🇩
                                        Indonesia (ID)</option>
                                    <option value="en" {{ ($settings['language'] ?? 'id') == 'en' ? 'selected' : '' }}>🇬🇧
                                        English (EN)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Aksen Warna
                                        Utama</label>
                                    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <input type="color" name="theme_color_primary"
                                            value="{{ $settings['theme_color_primary'] ?? '#5f674d' }}"
                                            class="h-10 w-14 p-0.5 border-0 rounded-lg cursor-pointer bg-transparent"
                                            id="colorPicker">
                                        <div class="flex-1">
                                            <input type="text" id="colorHex"
                                                value="{{ $settings['theme_color_primary'] ?? '#5f674d' }}" readonly
                                                class="w-full text-sm bg-white border border-gray-200 rounded-lg px-3 py-2 font-mono">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Digunakan untuk tombol, menu aktif, dan aksen
                                        tema.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Font
                                        (Typography)</label>
                                    <select name="theme_font_family"
                                        class="w-full rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] text-sm shadow-sm">
                                        <option value="Nunito" {{ ($settings['theme_font_family'] ?? 'Nunito') == 'Nunito' ? 'selected' : '' }}>Nunito — Default (Bulat & Ramah)</option>
                                        <option value="Inter" {{ ($settings['theme_font_family'] ?? 'Nunito') == 'Inter' ? 'selected' : '' }}>Inter — Modern & Clean</option>
                                        <option value="Poppins" {{ ($settings['theme_font_family'] ?? 'Nunito') == 'Poppins' ? 'selected' : '' }}>Poppins — Round & Friendly</option>
                                        <option value="Playfair Display" {{ ($settings['theme_font_family'] ?? 'Nunito') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display — Classic
                                            Cafe</option>
                                    </select>
                                    <p class="text-xs text-gray-400 mt-1">Berlaku pada seluruh teks sistem setelah disimpan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Konten Web Publik -->
                    <div x-show="activeTab === 'web'" x-transition class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Hero -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#5f674d]/10 text-[#5f674d] text-xs font-bold">1</span>
                                    <h4 class="font-bold text-gray-700 text-sm">Bagian Atas (Hero)</h4>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Judul
                                        Utama</label>
                                    <input type="text" name="web_hero_title"
                                        value="{{ $settings['web_hero_title'] ?? 'Experience the Perfect Roast' }}"
                                        class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] shadow-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Sub-judul
                                        / Deskripsi</label>
                                    <textarea name="web_hero_subtitle" rows="3"
                                        class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] resize-none shadow-sm">{{ $settings['web_hero_subtitle'] ?? 'Discover the authentic taste of locally sourced, premium coffee beans. Roasted to perfection for those who appreciate the finer details.' }}</textarea>
                                </div>
                            </div>

                            <!-- About & Footer -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#5f674d]/10 text-[#5f674d] text-xs font-bold">2</span>
                                    <h4 class="font-bold text-gray-700 text-sm">Cerita & Footer</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Judul
                                            Our Story</label>
                                        <input type="text" name="web_about_title"
                                            value="{{ $settings['web_about_title'] ?? 'More Than Just Coffee' }}"
                                            class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] shadow-sm">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Teks
                                            Footer</label>
                                        <input type="text" name="web_footer_text"
                                            value="{{ $settings['web_footer_text'] ?? '© 2025 All Rights Reserved. DiiM' }}"
                                            class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Deskripsi
                                        Cerita (Paragraf 1)</label>
                                    <textarea name="web_about_desc_1" rows="2"
                                        class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] resize-none shadow-sm">{{ $settings['web_about_desc_1'] ?? 'At Watu Coffee, we believe that every cup tells a story. From the farmers who cultivate the beans to our master roasters, we are dedicated to excellence at every step.' }}</textarea>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Deskripsi
                                        Cerita (Paragraf 2)</label>
                                    <textarea name="web_about_desc_2" rows="2"
                                        class="w-full text-sm rounded-xl border-gray-200 focus:ring-[#5f674d] focus:border-[#5f674d] resize-none shadow-sm">{{ $settings['web_about_desc_2'] ?? 'Our journey began with a simple passion: to find the perfect bean. Today, we share that passion through our carefully curated single-origin blends and signature roasts.' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- TAB: Media & Slider -->
            <div x-show="activeTab === 'media'" x-transition class="p-8">
                <div class="space-y-6">
                    <p class="text-sm text-gray-600">Kelola gambar banner dan slider yang ditampilkan di halaman website
                        publik kafe Anda.</p>

                    <!-- Quick Link Card to Sliders -->
                    <a href="{{ route('sliders.index') }}"
                        class="flex items-center gap-5 p-5 bg-gradient-to-r from-[#5f674d]/5 to-[#5f674d]/10 border border-[#5f674d]/20 rounded-2xl hover:shadow-md hover:from-[#5f674d]/10 hover:to-[#5f674d]/15 transition-all group">
                        <div
                            class="w-14 h-14 bg-[#5f674d] rounded-2xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform shadow-md shadow-[#5f674d]/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-base">Kelola Slider & Banner</h4>
                            <p class="text-sm text-gray-500 mt-0.5">Tambah, edit, dan atur urutan gambar banner yang tampil
                                di hero dan about section website publik.</p>
                        </div>
                        <svg class="w-5 h-5 text-[#5f674d] shrink-0 group-hover:translate-x-1 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <div
                        class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-800 flex gap-3 items-start">
                        <svg class="w-5 h-5 mt-0.5 shrink-0 text-amber-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-semibold mb-0.5">Tips Upload Slider</p>
                            <p>Gunakan gambar berukuran 1200×600px atau lebih. Format JPG/PNG/WebP. Pastikan gambar sudah
                                dioptimasi agar website tetap cepat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400">Perubahan akan diterapkan setelah disimpan.</p>
                <button type="submit"
                    class="bg-[#5f674d] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#4a503a] active:scale-95 transition-all flex items-center gap-2 shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
            </form>
        </div>
    </div>

    <!-- ====== ONBOARDING OVERLAY ====== -->
    <div x-show="onboarding.active" x-transition.opacity
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[200] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <!-- Header -->
            <div class="bg-[#5f674d] px-6 py-5 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold"
                            x-text="onboarding.step + 1"></div>
                        <span class="font-bold text-lg" x-text="onboarding.steps[onboarding.step].title"></span>
                    </div>
                    <button @click="closeOnboarding()" class="text-white/60 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <!-- Progress Bar -->
                <div class="mt-4 bg-white/20 rounded-full h-1.5">
                    <div class="bg-white rounded-full h-1.5 transition-all duration-500"
                        :style="'width:' + ((onboarding.step + 1) / onboarding.steps.length * 100) + '%'"></div>
                </div>
                <p class="text-white/60 text-xs mt-2"
                    x-text="(onboarding.step + 1) + ' dari ' + onboarding.steps.length + ' langkah'"></p>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#5f674d]/10 flex items-center justify-center shrink-0 text-2xl"
                        x-text="onboarding.steps[onboarding.step].icon"></div>
                    <div>
                        <p class="text-gray-700 leading-relaxed text-sm" x-text="onboarding.steps[onboarding.step].desc">
                        </p>
                        <div class="mt-3 bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-700 flex gap-2"
                            x-show="onboarding.steps[onboarding.step].tip">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                            <span x-text="onboarding.steps[onboarding.step].tip"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Nav -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <button @click="prevStep()" :disabled="onboarding.step === 0"
                    class="text-sm text-gray-500 hover:text-gray-700 disabled:opacity-30 disabled:cursor-not-allowed flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Sebelumnya
                </button>

                <div class="flex gap-1.5">
                    <template x-for="(s, i) in onboarding.steps" :key="i">
                        <button @click="onboarding.step = i"
                            :class="onboarding.step === i ? 'bg-[#5f674d] w-4' : 'bg-gray-300 w-1.5'"
                            class="h-1.5 rounded-full transition-all duration-300"></button>
                    </template>
                </div>

                <button @click="nextStep()"
                    class="text-sm font-semibold bg-[#5f674d] text-white px-5 py-2 rounded-xl hover:bg-[#4a503a] transition-all flex items-center gap-1 active:scale-95">
                    <span x-text="onboarding.step < onboarding.steps.length - 1 ? 'Lanjut' : 'Selesai!'"></span>
                    <svg x-show="onboarding.step < onboarding.steps.length - 1" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg x-show="onboarding.step === onboarding.steps.length - 1" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    </div>

    <script>
        function settingsTabs() {
            return {
                activeTab: '{{ session("settings_tab", "identitas") }}',
                onboarding: {
                    active: false,
                    step: 0,
                    steps: [
                        {
                            title: 'Selamat Datang di Pengaturan!',
                            icon: '🎉',
                            desc: 'Panduan singkat ini akan membantu Anda menyesuaikan sistem sesuai identitas kafe Anda. Hanya membutuhkan beberapa menit untuk setup awal.',
                            tip: 'Anda bisa membuka panduan ini kapan saja lewat tombol \"Panduan Setup\" di pojok kanan atas.'
                        },
                        {
                            title: 'Tab Identitas & Logo',
                            icon: '🏪',
                            desc: 'Mulai dengan mengisi Nama Perusahaan dan mengunggah logo kafe Anda. Logo Navigasi akan tampil di sidebar sistem, Logo Invoice di struk/nota, dan Logo Website di halaman publik.',
                            tip: 'Gunakan format PNG dengan latar belakang transparan untuk hasil logo yang paling bersih.'
                        },
                        {
                            title: 'Tab Tampilan & Bahasa',
                            icon: '🎨',
                            desc: 'Sesuaikan warna tema utama sistem dengan warna branding kafe Anda. Pilih juga jenis font yang mencerminkan karakter kafe Anda, dan atur bahasa tampilan sistem.',
                            tip: 'Warna utama akan diterapkan pada tombol, menu aktif, dan elemen-elemen kunci di seluruh sistem.'
                        },
                        {
                            title: 'Tab Konten Web Publik',
                            icon: '🌐',
                            desc: 'Isi teks yang akan ditampilkan di website publik kafe Anda — mulai dari judul halaman utama, deskripsi singkat, cerita kafe, hingga teks hak cipta di bagian bawah halaman.',
                            tip: 'Pastikan teks mencerminkan identitas dan nilai unik kafe Anda agar pelanggan lebih terkesan.'
                        },
                        {
                            title: 'Simpan & Selesai! ✅',
                            icon: '🚀',
                            desc: 'Setelah mengisi semua tab, klik tombol "Simpan Pengaturan" di bagian bawah. Perubahan akan langsung diterapkan ke seluruh sistem dan website publik Anda!',
                            tip: 'Anda bisa kembali ke pengaturan kapan saja untuk memperbarui konten atau mengubah tampilan.'
                        }
                    ]
                },

                initOnboarding() {
                    // Tampilkan otomatis jika belum pernah setup (tidak ada company_name tersimpan)
                    const isFirstTime = !localStorage.getItem('watu_onboarding_done');
                    if (isFirstTime) {
                        setTimeout(() => { this.onboarding.active = true; }, 600);
                    }
                },

                startOnboarding() {
                    this.onboarding.step = 0;
                    this.onboarding.active = true;
                },

                nextStep() {
                    if (this.onboarding.step < this.onboarding.steps.length - 1) {
                        this.onboarding.step++;
                    } else {
                        this.closeOnboarding();
                    }
                },

                prevStep() {
                    if (this.onboarding.step > 0) {
                        this.onboarding.step--;
                    }
                },

                closeOnboarding() {
                    this.onboarding.active = false;
                    localStorage.setItem('watu_onboarding_done', '1');
                }
            }
        }

        // Sync color picker with hex input
        document.addEventListener('DOMContentLoaded', function () {
            const picker = document.getElementById('colorPicker');
            const hex = document.getElementById('colorHex');
            if (picker && hex) {
                picker.addEventListener('input', function () {
                    hex.value = picker.value.toUpperCase();
                });
            }
        });
    </script>
@endsection