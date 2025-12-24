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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('purchase_date'); // Tanggal Perolehan
            
            $table->decimal('cost', 15, 2); // Harga Perolehan (Cost)
            $table->decimal('salvage_value', 15, 2)->default(0); // Nilai Sisa (Residu)
            $table->integer('useful_life_years'); // Umur Ekonomis (Tahun)
            
            // Auto-calculated fields (Caching current state)
            $table->decimal('depreciation_accumulated', 15, 2)->default(0); 
            $table->decimal('book_value', 15, 2); 
            
            // Integrated Accounting
            $table->foreignId('fixed_asset_account_id')->constrained('chart_of_accounts'); // Akun Aset (Header 1-2xx)
            $table->foreignId('accumulated_depreciation_account_id')->constrained('chart_of_accounts'); // Akun Akumulasi (Header 1-2xx, Contra Asset)
            $table->foreignId('depreciation_expense_account_id')->constrained('chart_of_accounts'); // Akun Beban (Header 6-xxx)

            $table->string('status')->default('active'); // active, sold, disposed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
