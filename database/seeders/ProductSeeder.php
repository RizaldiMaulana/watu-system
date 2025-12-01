<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // --- 1. ROAST BEANS (Update Data Lengkap) ---
        $beans = [
            [
                'name' => 'Arabica Java Pangalengan',
                'code' => 'BN-PNG-200',
                'category' => 'roast_bean',
                'price' => 95000,
                'unit' => '200gr',
                'varietal' => 'Sigararutang', // Kolom Baru
                'process' => 'Natural',       // Kolom Baru
                'description' => 'Taste Notes: Berry, Floral, Sweet Aftertaste. Medium Roast.',
                'stock' => 20
            ],
            [
                'name' => 'Arabica Bali Kintamani',
                'code' => 'BN-KIN-200',
                'category' => 'roast_bean',
                'price' => 151000,
                'unit' => '200gr',
                'varietal' => 'Kopyol / Kartika',
                'process' => 'Koji Fermented',
                'description' => 'Taste Notes: Green Apple, Molasses, Lime. Unique fermentation process.',
                'stock' => 20
            ],
            [
                'name' => 'Arabica Flores Bajawa',
                'code' => 'BN-BJW-200',
                'category' => 'roast_bean',
                'price' => 98000,
                'unit' => '200gr',
                'varietal' => 'S795 (Jember)',
                'process' => 'Full Wash',
                'description' => 'Taste Notes: Nutty, Caramel, Medium Body.',
                'stock' => 20
            ],
            [
                'name' => 'Robusta Tabanan Bali',
                'code' => 'BN-TBN-200',
                'category' => 'roast_bean',
                'price' => 65000,
                'unit' => '200gr',
                'varietal' => 'Klon BP-42',
                'process' => 'Natural',
                'description' => 'Taste Notes: Bold, Dark Chocolate, Earthy.',
                'stock' => 20
            ],
            [
                'name' => 'Watu House Blend',
                'code' => 'BN-HSE-1KG',
                'category' => 'roast_bean',
                'price' => 220000,
                'unit' => '1kg',
                'varietal' => 'Mix Arabica & Robusta',
                'process' => 'Semi Wash',
                'description' => 'Campuran khusus untuk mesin Espresso.',
                'stock' => 20
            ],
        ];

        foreach ($beans as $item) {
            Product::create(array_merge($item, ['category' => 'roast_bean', 'stock' => 20]));
        }

        // --- 2. MINUMAN KOPI (Cafe Menu) ---
        $coffeeDrinks = [
            ['name' => 'Es Kopi Susu Watu', 'price' => 24000, 'description' => 'Es Kopi Susu dengan Creamer/Gula Aren khas Watu'],
            ['name' => 'Es Kopi Hitam', 'price' => 23000, 'description' => 'Double Shot Espresso + Ice + Water'],
            ['name' => 'Manual Brew V60', 'price' => 28000, 'description' => 'Pilihan Beans: Arabica Gayo/Bali/Java'],
            ['name' => 'Japanese Iced Coffee', 'price' => 30000, 'description' => 'Manual Brew dingin menyegarkan'],
            ['name' => 'Cappuccino Hot', 'price' => 25000, 'description' => 'Espresso + Steamed Milk + Foam tebal'],
            ['name' => 'Caffe Latte', 'price' => 25000, 'description' => 'Espresso + Steamed Milk (Light Foam)'],
            ['name' => 'Kopi Tubruk Watu', 'price' => 18000, 'description' => 'Kopi hitam tradisional ampas'],
        ];

        foreach ($coffeeDrinks as $item) {
            Product::create(array_merge($item, ['category' => 'coffee', 'unit' => 'cup', 'stock' => 100]));
        }

        // --- 3. NON KOPI & REFRESHER ---
        $nonCoffee = [
            ['name' => 'Es Teh Susu', 'price' => 23000, 'description' => 'Teh Saring + Susu Kental Manis/Creamer'],
            ['name' => 'Es Milo Malaysia', 'price' => 22000, 'description' => 'Milo kental tabur bubuk'],
            ['name' => 'Watu Honey Lemon', 'price' => 28000, 'description' => 'Madu Watu (Talasi) + Lemon segar'],
            ['name' => 'Es Lychee Tea', 'price' => 25000, 'description' => 'Teh rasa leci dengan buah asli'],
            ['name' => 'Chocolate Signature', 'price' => 26000, 'description' => 'Coklat pekat panas/dingin'],
        ];

        foreach ($nonCoffee as $item) {
            Product::create(array_merge($item, ['category' => 'non_coffee', 'unit' => 'cup', 'stock' => 100]));
        }

        // --- 4. MAKANAN & CAMILAN (Food) ---
        $foods = [
            ['name' => 'Singkong Goreng Watu', 'price' => 20000, 'description' => 'Singkong goreng mekar, gurih, empuk'],
            ['name' => 'Pisang Goreng Srikaya', 'price' => 25000, 'description' => 'Pisang goreng dengan selai srikaya'],
            ['name' => 'Cireng Rujak', 'price' => 18000, 'description' => 'Cireng garing dengan bumbu rujak pedas manis'],
            ['name' => 'Kentang Goreng', 'price' => 21000, 'description' => 'Shoestring french fries'],
            ['name' => 'Nasi Goreng Jawa', 'price' => 28000, 'description' => 'Nasi goreng bumbu rempah jawa + Telur + Kerupuk'],
            ['name' => 'Roti Bakar Keju Susu', 'price' => 22000, 'description' => 'Roti bakar tebal topping melimpah'],
        ];

        foreach ($foods as $item) {
            Product::create(array_merge($item, ['category' => 'food', 'unit' => 'porsi', 'stock' => 50]));
        }
    }
}