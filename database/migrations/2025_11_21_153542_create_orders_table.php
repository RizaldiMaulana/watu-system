<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Tabel Header Penjualan
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // CONTOH: INV-20251121-001
            $table->foreignId('user_id')->constrained(); // Kasir yang melayani
            $table->decimal('total_amount', 15, 2);
            $table->decimal('pay_amount', 15, 2); // Uang yang dibayar
            $table->decimal('change_amount', 15, 2); // Kembalian
            $table->string('payment_method')->default('cash');
            $table->timestamps();
        });

        // 2. Tabel Detail Barang Terjual
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->string('product_name'); // Simpan nama jaga-jaga jika produk dihapus
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Harga saat transaksi terjadi
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
