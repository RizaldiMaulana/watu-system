<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV-2023...
            $table->string('customer_name')->nullable();
            $table->string('type'); // Dine-in, Web-Reservation, Web-Beans
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_status')->default('Unpaid'); // Unpaid, Paid
            $table->string('payment_method')->nullable(); // Cash, QRIS, Transfer
            $table->timestamps();
        });

        // Tabel detail barang yang dibeli (Pivot)
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Harga saat transaksi terjadi
            $table->decimal('subtotal', 15, 2);
        });
    }
};
