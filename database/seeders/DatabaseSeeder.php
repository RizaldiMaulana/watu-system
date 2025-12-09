<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // User
        User::create([
            'name' => 'Admin Watu',
            'email' => 'admin@watu.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // 1. Manager / Owner (Akses Penuh)
        User::create([
            'name' => 'Manajer/Owner Watu',
            'email' => 'manager@watu.com',
            'password' => Hash::make('password'),
            'role' => 'manager'
        ]);

        // 2. Barista (Akses Terbatas: POS & Order)
        User::create([
            'name' => 'Barista Watu',
            'email' => 'barista@watu.com',
            'password' => Hash::make('password'),
            'role' => 'barista'
        ]);

        // 3. Roaster (Akses Terbatas: Produksi/Stok)
        User::create([
            'name' => 'Roaster Watu',
            'email' => 'roaster@watu.com',
            'password' => Hash::make('password'),
            'role' => 'roaster'
        ]);

        // ID 1
        ChartOfAccount::create([
            'code' => '1-101', 
            'name' => 'Persediaan Bahan Baku', 
            'type' => 'asset'
        ]);

        // ID 2
        ChartOfAccount::create([
            'code' => '1-102', 
            'name' => 'Kas Besar', 
            'type' => 'asset'
        ]);

        // ID 3
        ChartOfAccount::create([
            'code' => '2-101', 
            'name' => 'Utang Dagang', 
            'type' => 'liability'
        ]);
        
        // Tambahan Akun Lain (Opsional)
        ChartOfAccount::create(['code' => '4-100', 'name' => 'Penjualan Cafe', 'type' => 'revenue']); // ID 4
        ChartOfAccount::create(['code' => '4-101', 'name' => 'Penjualan Roastery', 'type' => 'revenue']); // ID 5
        ChartOfAccount::create(['code' => '5-100', 'name' => 'Beban Gaji', 'type' => 'expense']);

        // 3. Buat Data Supplier Dummy
        Supplier::create(['name' => 'PT. Kopi Nusantara', 'phone' => '08123456789']);
        Supplier::create(['name' => 'UD. Susu Segar', 'phone' => '08987654321']);
    }
}