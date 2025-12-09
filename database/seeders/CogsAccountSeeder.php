<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class CogsAccountSeeder extends Seeder
{
    public function run()
    {
        // ID 6 (or next available)
        ChartOfAccount::firstOrCreate(
            ['code' => '5-101'],
            [
                'name' => 'Beban Pokok Penjualan (HPP)', 
                'type' => 'expense'
            ]
        );
    }
}
