<?php

$file = 'c:\laragon\www\watu-system\resources\views\layouts\navigation.blade.php';
$content = file_get_contents($file);

$replacements = [
    '>Menu Utama<' => '>{{ __(\'Menu Utama\') }}<',
    '>Dashboard<' => '>{{ __(\'Dashboard\') }}<',
    '>Mesin Kasir<' => '>{{ __(\'Mesin Kasir\') }}<',
    '>Penjualan<' => '>{{ __(\'Penjualan\') }}<',
    '>Pesanan Online<' => '>{{ __(\'Pesanan Online\') }}<',
    '>Reservasi Meja<' => '>{{ __(\'Reservasi Meja\') }}<',
    '>Riwayat Penjualan<' => '>{{ __(\'Riwayat Penjualan\') }}<',
    '>Promo & Diskon<' => '>{{ __(\'Promo & Diskon\') }}<',
    '>Stok & Pengadaan<' => '>{{ __(\'Stok & Pengadaan\') }}<',
    '>Stok &amp; Pengadaan<' => '>{{ __(\'Stok & Pengadaan\') }}<',
    '>Stok Real-time<' => '>{{ __(\'Stok Real-time\') }}<',
    '>Input Pembelian<' => '>{{ __(\'Input Pembelian\') }}<',
    '>Riwayat Pembelian PO<' => '>{{ __(\'Riwayat Pembelian PO\') }}<',
    '>Penerimaan Barang<' => '>{{ __(\'Penerimaan Barang\') }}<',
    '>Manajemen Produk<' => '>{{ __(\'Manajemen Produk\') }}<',
    '>Produk / Menu Jual<' => '>{{ __(\'Produk / Menu Jual\') }}<',
    '>Resep (BOM)<' => '>{{ __(\'Resep (BOM)\') }}<',
    '>Kategori Menu<' => '>{{ __(\'Kategori Menu\') }}<',
    '>Akuntansi<' => '>{{ __(\'Akuntansi\') }}<',
    '>Daftar Akun (CoA)<' => '>{{ __(\'Daftar Akun (CoA)\') }}<',
    '>Pajak & Layanan<' => '>{{ __(\'Pajak & Layanan\') }}<',
    '>Pajak &amp; Layanan<' => '>{{ __(\'Pajak & Layanan\') }}<',
    '>Jurnal Manual<' => '>{{ __(\'Jurnal Manual\') }}<',
    '>Aset Tetap (Baru)<' => '>{{ __(\'Aset Tetap (Baru)\') }}<',
    '>Laporan & Analisa<' => '>{{ __(\'Laporan & Analisa\') }}<',
    '>Laporan &amp; Analisa<' => '>{{ __(\'Laporan & Analisa\') }}<',
    '>Neraca (Balance Sheet)<' => '>{{ __(\'Neraca (Balance Sheet)\') }}<',
    '>Laba Rugi (P&L)<' => '>{{ __(\'Laba Rugi (P&L)\') }}<',
    '>Laba Rugi (P&amp;L)<' => '>{{ __(\'Laba Rugi (P&L)\') }}<',
    '>Arus Kas (Cash Flow)<' => '>{{ __(\'Arus Kas (Cash Flow)\') }}<',
    '>Laporan Jurnal<' => '>{{ __(\'Laporan Jurnal\') }}<',
    '>Hutang & Piutang<' => '>{{ __(\'Hutang & Piutang\') }}<',
    '>Hutang &amp; Piutang<' => '>{{ __(\'Hutang & Piutang\') }}<',
    '>Data Master<' => '>{{ __(\'Data Master\') }}<',
    '>Data Bahan Baku<' => '>{{ __(\'Data Bahan Baku\') }}<',
    '>Data Pelanggan<' => '>{{ __(\'Data Pelanggan\') }}<',
    '>Data Supplier<' => '>{{ __(\'Data Supplier\') }}<',
    '>Pengaturan Sistem<' => '>{{ __(\'Pengaturan Sistem\') }}<',
    '>Slider / CMS<' => '>{{ __(\'Slider / CMS\') }}<',
    '>Manajemen User<' => '>{{ __(\'Manajemen User\') }}<',
    '>Edit Profile<' => '>{{ __(\'Edit Profile\') }}<',
    '>Sign Out<' => '>{{ __(\'Sign Out\') }}<'
];

// Special handle for multiline text like 'Stok &\n Pengadaan' -> 'Stok & Pengadaan'
$content = preg_replace('/>Stok &\s+Pengadaan</', '>{{ __(\'Stok & Pengadaan\') }}<', $content);
$content = preg_replace('/>Manajemen\s+Produk</', '>{{ __(\'Manajemen Produk\') }}<', $content);
$content = preg_replace('/>Laporan &\s+Analisa</', '>{{ __(\'Laporan & Analisa\') }}<', $content);

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "Done";
