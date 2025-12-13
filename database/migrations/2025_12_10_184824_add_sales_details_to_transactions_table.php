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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('subtotal_amount', 15, 2)->default(0)->after('type'); // Before Tax/Disc
            $table->decimal('tax_amount', 15, 2)->default(0)->after('total_amount');
            $table->decimal('tax_rate', 5, 2)->default(10.00)->after('tax_amount'); // Default 10%
            $table->decimal('discount_amount', 15, 2)->default(0)->after('tax_rate');
            $table->string('discount_reason')->nullable()->after('discount_amount');
            $table->decimal('adjustment_amount', 15, 2)->default(0)->after('discount_reason'); // Rounding
            $table->boolean('is_complimentary')->default(false)->after('adjustment_amount');
            $table->timestamp('voided_at')->nullable()->after('payment_method');
            $table->string('void_reason')->nullable()->after('voided_at');
            $table->text('notes')->nullable()->after('void_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_amount',
                'tax_amount',
                'tax_rate',
                'discount_amount',
                'discount_reason',
                'adjustment_amount',
                'is_complimentary',
                'voided_at',
                'void_reason',
                'notes'
            ]);
        });
    }
};
