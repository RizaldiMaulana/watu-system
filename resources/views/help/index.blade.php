@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50">

        <!-- Hero Header -->
        <div class="bg-gradient-to-br from-[#5f674d] via-[#6e784f] to-[#4a503a] px-6 py-12">
            <div class="max-w-4xl mx-auto text-center text-white">
                <div
                    class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-widest mb-5 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Pusat Panduan
                </div>
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-3">Butuh Bantuan?</h1>
                <p class="text-white/70 text-base max-w-lg mx-auto">Temukan jawaban dan panduan lengkap penggunaan seluruh
                    fitur sistem manajemen kafe ini.</p>

                <!-- Search Bar -->
                <div class="mt-8 max-w-xl mx-auto relative" x-data="{ q: '' }">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input x-model="q" type="text" placeholder="Cari topik atau pertanyaan..."
                        class="w-full pl-12 pr-4 py-4 rounded-2xl border-0 text-gray-800 text-sm shadow-xl focus:ring-2 focus:ring-white/50 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Quick Nav Pills -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-5">
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none" x-data="{ active: null }">
                @php
                    $categories = [
                        ['icon' => '🏠', 'label' => 'Dashboard'],
                        ['icon' => '🖥️', 'label' => 'Kasir & POS'],
                        ['icon' => '📦', 'label' => 'Stok'],
                        ['icon' => '📊', 'label' => 'Laporan'],
                        ['icon' => '⚙️', 'label' => 'Pengaturan'],
                        ['icon' => '👥', 'label' => 'User'],
                    ];
                @endphp
                @foreach($categories as $cat)
                    <button
                        class="flex items-center gap-1.5 whitespace-nowrap bg-white border border-gray-200 text-gray-600 text-xs font-semibold px-4 py-2 rounded-full shadow-sm hover:border-[#5f674d] hover:text-[#5f674d] transition-all">
                        <span>{{ $cat['icon'] }}</span> {{ $cat['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Content Area -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8" x-data="{ open: null }">

            @php
                $guides = [
                    [
                        'icon' => '🏠',
                        'title' => 'Dashboard',
                        'badge' => '2 topik',
                        'color' => 'blue',
                        'items' => [
                            ['q' => 'Apa itu Dashboard?', 'a' => 'Dashboard adalah halaman utama yang menampilkan ringkasan bisnis Anda hari ini — total penjualan, jumlah transaksi, stok kritis, dan top produk. Data diperbarui secara real-time setiap kali halaman dibuka.'],
                            ['q' => 'Bagaimana cara membaca grafik penjualan?', 'a' => 'Grafik menampilkan tren penjualan harian/mingguan. Hover pada titik grafik untuk melihat detail nilai penjualan. Klik kartu angka di atas grafik untuk menuju halaman detail terkait.'],
                        ]
                    ],
                    [
                        'icon' => '🖥️',
                        'title' => 'Mesin Kasir (POS)',
                        'badge' => '3 topik',
                        'color' => 'green',
                        'items' => [
                            ['q' => 'Cara membuat transaksi baru?', 'a' => "1. Buka menu Mesin Kasir\n2. Klik produk untuk menambah ke keranjang\n3. Atur jumlah jika perlu\n4. Klik \"Proses Pembayaran\"\n5. Pilih metode bayar → klik \"Bayar\"\n\nStruk bisa langsung dicetak setelah transaksi berhasil."],
                            ['q' => 'Bagaimana menerapkan diskon atau promosi?', 'a' => 'Di halaman kasir, setelah memilih produk, klik ikon promo/diskon pada produk atau di keranjang. Diskon akan otomatis terhitung jika ada promosi aktif yang telah dikonfigurasi sebelumnya.'],
                            ['q' => 'Cara membatalkan (void) transaksi?', 'a' => 'Buka menu Penjualan → cari transaksi → klik "Void". Hanya Admin/Manager/Owner yang memiliki akses untuk melakukan void transaksi yang sudah selesai.'],
                        ]
                    ],
                    [
                        'icon' => '📦',
                        'title' => 'Stok & Pengadaan',
                        'badge' => '3 topik',
                        'color' => 'orange',
                        'items' => [
                            ['q' => 'Cara membuat Purchase Order (PO)?', 'a' => "1. Buka Stok & Pengadaan → Pembelian\n2. Klik \"Buat PO\"\n3. Pilih supplier\n4. Tambahkan item beserta jumlahnya\n5. Simpan\n\nPO akan masuk antrian dan siap diproses saat barang datang."],
                            ['q' => 'Bagaimana cara menerima barang?', 'a' => 'Setelah PO dibuat, buka Penerimaan Barang → cari PO → klik "Terima" → verifikasi item dan jumlah barang yang datang → simpan. Stok akan otomatis bertambah sesuai penerimaan.'],
                            ['q' => 'Sistem peringatan stok kritis?', 'a' => 'Sistem otomatis memberi peringatan merah jika stok produk atau bahan baku di bawah batas minimum. Cek Dashboard atau menu DSS Restock untuk mendapatkan rekomendasi pembelian otomatis.'],
                        ]
                    ],
                    [
                        'icon' => '📊',
                        'title' => 'Laporan & Analisa',
                        'badge' => '3 topik',
                        'color' => 'purple',
                        'items' => [
                            ['q' => 'Cara melihat laporan keuangan?', 'a' => 'Buka menu Laporan & Analisa → Laporan Keuangan. Tersedia laporan Jurnal, Laba Rugi, Neraca, dan Arus Kas. Gunakan filter periode tanggal untuk menyempurnakan rentang data yang ditampilkan.'],
                            ['q' => 'Cara mengekspor laporan?', 'a' => 'Di halaman laporan, gunakan tombol "Export" atau "Print" di pojok kanan atas untuk mengunduh laporan dalam format PDF atau Excel sesuai kebutuhan.'],
                            ['q' => 'Apa itu Hutang & Piutang?', 'a' => 'Menu Hutang & Piutang menampilkan semua tagihan yang belum dibayar — baik hutang ke supplier maupun piutang dari pelanggan. Klik item untuk melihat detail dan mencatat pembayaran masuk/keluar.'],
                        ]
                    ],
                    [
                        'icon' => '⚙️',
                        'title' => 'Pengaturan Sistem',
                        'badge' => '4 topik',
                        'color' => 'gray',
                        'items' => [
                            ['q' => 'Cara mengubah nama kafe dan logo?', 'a' => 'Buka Pengaturan → Tab "Identitas & Logo" → isi nama kafe → upload logo → klik Simpan. Logo akan langsung berubah di sidebar, struk, dan website publik.'],
                            ['q' => 'Cara mengubah tampilan/tema warna?', 'a' => 'Pengaturan → Tab "Tampilan & Bahasa" → pilih warna aksen utama dan font yang diinginkan → Simpan. Perubahan tema berlaku di seluruh antarmuka sistem.'],
                            ['q' => 'Cara mengubah konten website publik?', 'a' => 'Pengaturan → Tab "Konten Web Publik" → edit teks hero, cerita kafe, dan footer → Simpan. Pengunjung website akan langsung melihat perubahan tanpa perlu rebuild apapun.'],
                            ['q' => 'Cara mengelola slider/banner website?', 'a' => 'Pengaturan → Tab "Media & Slider" → klik "Kelola Slider & Banner" untuk menambah, mengedit, atau menghapus gambar banner di website publik.'],
                        ]
                    ],
                    [
                        'icon' => '👥',
                        'title' => 'Manajemen User',
                        'badge' => '3 topik',
                        'color' => 'red',
                        'items' => [
                            ['q' => 'Cara menambah user/karyawan baru?', 'a' => 'Data Master → Manajemen User → Tambah User → isi nama, email, password, dan role → Simpan. User baru bisa langsung login. Panduan setup interaktif akan muncul otomatis saat user pertama login.'],
                            ['q' => 'Apa saja role yang tersedia?', 'a' => "• Owner — akses penuh semua fitur\n• Admin — akses penuh kecuali beberapa konfigurasi kritis\n• Manager — akses laporan, stok, dan pengadaan\n• Kasir — hanya akses Mesin Kasir dan penjualan dasar"],
                            ['q' => 'Cara reset password user?', 'a' => 'Sebagai Admin/Owner, buka Data Master → Manajemen User → klik "Edit" pada user yang ingin diubah → masukkan password baru → Simpan.'],
                        ]
                    ],
                ];
            @endphp

            @foreach($guides as $gi => $guide)
                <div
                    class="mb-4 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Section Header -->
                    <button @click="open = open === {{ $gi }} ? null : {{ $gi }}"
                        class="w-full flex items-center justify-between p-5 text-left focus:outline-none group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-11 h-11 bg-gray-50 rounded-xl flex items-center justify-center text-xl border border-gray-100 group-hover:bg-[#5f674d]/5 group-hover:border-[#5f674d]/20 transition-colors shrink-0">
                                {{ $guide['icon'] }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-base">{{ $guide['title'] }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $guide['badge'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs hidden sm:block text-gray-400 group-hover:text-[#5f674d] transition-colors">
                                <span x-show="open !== {{ $gi }}">Lihat topik</span>
                                <span x-show="open === {{ $gi }}">Tutup</span>
                            </span>
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-[#5f674d]/10 transition-colors shrink-0">
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-300"
                                    :class="open === {{ $gi }} ? 'rotate-180 text-[#5f674d]' : ''" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </button>

                    <!-- FAQ Items -->
                    <div x-show="open === {{ $gi }}" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        class="border-t border-gray-50">
                        @foreach($guide['items'] as $qi => $item)
                            <div class="{{ $qi < count($guide['items']) - 1 ? 'border-b border-gray-50' : '' }}"
                                x-data="{ show: false }">
                                <button @click="show = !show"
                                    class="w-full flex items-start gap-4 px-6 py-4 text-left hover:bg-gray-50/80 transition-colors focus:outline-none group/q">
                                    <div class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors"
                                        :class="show ? 'border-[#5f674d] bg-[#5f674d]' : 'border-gray-200 bg-white'">
                                        <span class="text-[9px] font-black leading-none"
                                            :class="show ? 'text-white' : 'text-gray-400'">Q</span>
                                    </div>
                                    <span
                                        class="text-sm font-semibold text-gray-700 group-hover/q:text-gray-900 flex-1 text-left">{{ $item['q'] }}</span>
                                    <svg class="w-4 h-4 text-gray-300 ml-auto shrink-0 mt-0.5 transition-transform duration-200"
                                        :class="show ? 'rotate-180 text-[#5f674d]' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="show" x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5">
                                    <div class="ml-9 bg-[#5f674d]/4 border-l-2 border-[#5f674d]/30 rounded-r-xl pl-4 pr-4 py-3">
                                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $item['a'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- CTA Footer -->
            <div class="mt-8 rounded-2xl overflow-hidden">
                <div
                    class="bg-gradient-to-r from-[#5f674d] to-[#4a503a] p-6 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-base">Masih bingung atau butuh tur interaktif?</p>
                        <p class="text-white/70 text-sm mt-0.5">Buka Panduan Setup di halaman Pengaturan untuk walkthrough
                            langkah demi langkah.</p>
                    </div>
                    <a href="{{ route('settings.index') }}"
                        class="shrink-0 inline-flex items-center gap-2 bg-white text-[#5f674d] px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Buka Panduan Setup
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection