<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0)->after('stock');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0)->after('price'); // For resale items
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0)->after('price'); // Snapshot HPP at time of sale
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });
    }
};
