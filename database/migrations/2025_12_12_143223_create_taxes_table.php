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
        Schema::create('taxes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->decimal('rate', 5, 2); // e.g., 10.00
            $table->string('type')->default('tax'); // 'tax' or 'service_charge'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Migrate existing tax_rate setting if it exists
        $existingRate = \DB::table('settings')->where('key', 'tax_rate')->value('value');
        if ($existingRate) {
            \DB::table('taxes')->insert([
                'id' => \Str::uuid(),
                'name' => 'PB1 (Restaurant Tax)',
                'rate' => $existingRate,
                'type' => 'tax',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
