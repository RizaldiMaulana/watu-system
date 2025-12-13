<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('options')->nullable()->after('description'); 
            // format: [{"name": "Method", "type": "select", "values": ["V60", "Kalita"]}, ...]
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->json('options')->nullable()->after('price'); 
            // format: {"Method": "V60", "Bean": "Bali Karana"}
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('options');
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
