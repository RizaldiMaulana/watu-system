<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Users (Admin, Manager, Barista, Roaster) - Using firstOrCreate to prevent duplicates
        User::firstOrCreate(
            ['email' => 'admin@watu.com'],
            [
                'name' => 'Admin Watu',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner@watu.com'],
            [
                'name' => 'Owner Watu',
                'password' => Hash::make('password'),
                'role' => 'owner'
            ]
        );

        User::firstOrCreate(
            ['email' => 'manager@watu.com'],
            [
                'name' => 'Manajer Watu',
                'password' => Hash::make('password'),
                'role' => 'manager'
            ]
        );

        User::firstOrCreate(
            ['email' => 'barista@watu.com'],
            [
                'name' => 'Barista Watu',
                'password' => Hash::make('password'),
                'role' => 'barista'
            ]
        );

        User::firstOrCreate(
            ['email' => 'roaster@watu.com'],
            [
                'name' => 'Roaster Watu',
                'password' => Hash::make('password'),
                'role' => 'roaster'
            ]
        );

        // 2. Chart of Accounts (CoA) - Essential for System Functionality
        $coas = [
            ['code' => '1-101', 'name' => 'Persediaan Bahan Baku', 'type' => 'asset'],
            ['code' => '1-102', 'name' => 'Kas Besar', 'type' => 'asset'],
            ['code' => '1-103', 'name' => 'Bank BCA', 'type' => 'asset'],
            ['code' => '2-101', 'name' => 'Utang Dagang', 'type' => 'liability'],
            ['code' => '4-100', 'name' => 'Penjualan Cafe', 'type' => 'revenue'],
            ['code' => '4-101', 'name' => 'Penjualan Roastery', 'type' => 'revenue'],
            ['code' => '5-100', 'name' => 'Beban Gaji', 'type' => 'expense'],
            ['code' => '5-101', 'name' => 'Beban Listrik & Air', 'type' => 'expense'],
            ['code' => '5-102', 'name' => 'Beban Sewa', 'type' => 'expense'],
            ['code' => '5-300', 'name' => 'Harga Pokok Penjualan (COGS)', 'type' => 'expense'],
        ];

        foreach ($coas as $coa) {
            ChartOfAccount::firstOrCreate(
                ['code' => $coa['code']],
                $coa
            );
        }

        // 3. Suppliers Dummy Data
        Supplier::firstOrCreate(['name' => 'PT. Kopi Nusantara'], ['phone' => '08123456789']);
        Supplier::firstOrCreate(['name' => 'UD. Susu Segar'], ['phone' => '08987654321']);
        Supplier::firstOrCreate(['name' => 'Toko Plastik Jaya'], ['phone' => '08112233445']);

        // 4. Create Storage Folders (Just in case AppServiceProvider didn't catch it yet)
        $folders = [
            storage_path('app/public/signatures'),
            storage_path('app/public/delivery_proofs'),
            storage_path('app/public/products'),
        ];

        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                @mkdir($folder, 0755, true);
            }
        }
    }
}