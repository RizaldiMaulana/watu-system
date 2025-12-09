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
        $transactions = \App\Models\Transaction::whereNull('uuid')->get();
        foreach ($transactions as $transaction) {
            $transaction->uuid = \Illuminate\Support\Str::uuid();
            $transaction->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert specifically as column might be dropped or we keep UUIDs.
    }
};
