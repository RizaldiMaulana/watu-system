<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            // Tambahkan kolom ingredient_id (nullable jaga-jaga jika beli barang non-stok seperti sabun cuci)
            $table->foreignId('ingredient_id')->nullable()->after('purchase_id')->constrained('ingredients')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->dropColumn('ingredient_id');
        });
    }
};
