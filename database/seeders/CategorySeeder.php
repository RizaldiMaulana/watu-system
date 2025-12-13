<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Cafe Menu
            [
                'name' => 'Signature Coffee', 
                'slug' => 'signature', 
                'type' => 'cafe',
                'sort_order' => 1
            ],
            [
                'name' => 'Classic Coffee', 
                'slug' => 'classic', 
                'type' => 'cafe', 
                'sort_order' => 2
            ],
            [
                'name' => 'Filter Brew (Manual)', 
                'slug' => 'manual_brew', 
                'type' => 'cafe',
                'sort_order' => 3
            ],
            [
                'name' => 'Non Coffee', 
                'slug' => 'non_coffee', 
                'type' => 'cafe',
                'sort_order' => 4
            ],
            [
                'name' => 'Tea Selection', 
                'slug' => 'tea', 
                'type' => 'cafe',
                'sort_order' => 5
            ],
            [
                'name' => 'Signature Kombucha', 
                'slug' => 'kombucha', 
                'type' => 'cafe',
                'sort_order' => 6
            ],
            [
                'name' => 'Blended Juice', 
                'slug' => 'juice', 
                'type' => 'cafe',
                'sort_order' => 7
            ],
            [
                'name' => 'Food', 
                'slug' => 'food', 
                'type' => 'cafe',
                'sort_order' => 8
            ],
            [
                'name' => 'Snacks', 
                'slug' => 'snack', 
                'type' => 'cafe',
                'sort_order' => 9
            ],
            
            // Roastery Menu
            [
                'name' => 'House Blend', 
                'slug' => 'house_blend', 
                'type' => 'roastery',
                'sort_order' => 1
            ],
            [
                'name' => 'Single Origin', 
                'slug' => 'single_origin', 
                'type' => 'roastery',
                'sort_order' => 2
            ],
            [
                'name' => 'Fine Robusta', 
                'slug' => 'fine_robusta', 
                'type' => 'roastery',
                'sort_order' => 3
            ],
            [
                'name' => 'Roast Beans (General)', 
                'slug' => 'roast_bean', 
                'type' => 'roastery',
                'sort_order' => 99
            ],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Migrate existing products
        $products = \App\Models\Product::all();
        foreach ($products as $product) {
            $slug = $product->category; // Legacy string column
            
            // Map legacy 'roast_bean' to 'single_origin' (or general)
            // Or better, let's map based on name if possible, otherwise default to Single Origin for now
            if ($slug == 'roast_bean') {
                if (stripos($product->name, 'blend') !== false) {
                     $slug = 'house_blend';
                } elseif (stripos($product->name, 'robusta') !== false) {
                     $slug = 'fine_robusta';
                } else {
                     $slug = 'single_origin';
                }
            }
            // Handle legacy cafe mapping
            if ($slug == 'coffee') $slug = 'classic';
            
            $category = \App\Models\Category::where('slug', $slug)->first();
            
            if ($category) {
                // Only update if not already linked (or force update to fix legacy mapping)
                // For now, let's update if category_id is null or if we want to enforce new mapping
                // Assuming this seeder is run to fix things:
                $product->category_id = $category->id;
                $product->save();
            }
        }
    }
}
