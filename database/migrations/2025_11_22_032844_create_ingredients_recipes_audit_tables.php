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
        // --- TAMBAHKAN 3 BARIS INI (Hapus tabel lama jika ada) ---
        // Urutan penting: Hapus tabel 'anak' (recipes) dulu, baru 'induk' (ingredients)
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('audit_logs');
        // ---------------------------------------------------------

        // 1. Tabel Bahan Baku (Inventory)
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit'); // gram, ml, pcs
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(10);
            $table->timestamps();
        });

        // 2. Tabel Resep (Pivot: Produk -> Bahan Baku)
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->integer('amount_needed'); // Jumlah yang dibutuhkan per porsi
            $table->timestamps();
        });

        // 3. Tabel Audit Log (Pencegahan Fraud)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('action'); // Void, Adjustment, Login
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients_recipes_audit_tables');
    }
};
