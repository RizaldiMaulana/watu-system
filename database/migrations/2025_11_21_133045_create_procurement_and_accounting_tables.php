<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Supplier
        Schema::create('suppliers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('phone')->nullable();
        $table->timestamps();
        });
        
        // 2. Tabel Akun Akuntansi (Chart of Accounts)
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Misal: 1-100 (Kas), 5-100 (Beban)
            $table->string('name');
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->timestamps();
        });

        // 3. Tabel Transaksi Pembelian (Header)
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // Nomor Faktur
            $table->foreignId('supplier_id')->constrained();
            $table->date('transaction_date');
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_method'); // 'cash' atau 'credit'
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Detail Barang yang dibeli
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 5. Tabel Jurnal Umum (General Ledger Header) - INTI SIA
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('ref_number'); // Referensi ke invoice pembelian
            $table->date('transaction_date');
            $table->text('description');
            $table->decimal('total_debit', 15, 2);
            $table->decimal('total_credit', 15, 2);
            $table->timestamps();
        });

        // 6. Tabel Detail Jurnal (Debit/Kredit)
        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained('chart_of_accounts'); // Relasi ke COA
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_details');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('suppliers');
    }
};