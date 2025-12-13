<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCoaForSalesSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            ['name' => 'Hutang Pajak (PB1/PPN)', 'code' => '2-102', 'type' => 'Liability'],
            ['name' => 'Potongan Penjualan', 'code' => '4-102', 'type' => 'Revenue'], // Contra-revenue
            ['name' => 'Beban Promosi (Complimentary)', 'code' => '5-102', 'type' => 'Expense'],
        ];

        foreach ($accounts as $acc) {
            DB::table('chart_of_accounts')->updateOrInsert(
                ['code' => $acc['code']],
                ['name' => $acc['name'], 'type' => $acc['type'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
