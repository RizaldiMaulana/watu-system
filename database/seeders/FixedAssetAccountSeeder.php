<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class FixedAssetAccountSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            // ASSETS (1-2xx)
            ['code' => '1-201', 'name' => 'Mesin & Peralatan', 'type' => 'asset'],
            ['code' => '1-202', 'name' => 'Akum. Peny. Mesin & Peralatan', 'type' => 'asset'], // Contra Asset
            
            ['code' => '1-203', 'name' => 'Kendaraan', 'type' => 'asset'],
            ['code' => '1-204', 'name' => 'Akum. Peny. Kendaraan', 'type' => 'asset'], // Contra Asset

            ['code' => '1-205', 'name' => 'Inventaris Kantor & Furniture', 'type' => 'asset'],
            ['code' => '1-206', 'name' => 'Akum. Peny. Inventaris', 'type' => 'asset'], // Contra Asset

            // EXPENSES (6-2xx for Depreciation)
            ['code' => '6-201', 'name' => 'Beban Peny. Mesin & Peralatan', 'type' => 'expense'],
            ['code' => '6-202', 'name' => 'Beban Peny. Kendaraan', 'type' => 'expense'],
            ['code' => '6-203', 'name' => 'Beban Peny. Inventaris', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            ChartOfAccount::firstOrCreate(
                ['code' => $acc['code']],
                ['name' => $acc['name'], 'type' => $acc['type']]
            );
        }
    }
}
