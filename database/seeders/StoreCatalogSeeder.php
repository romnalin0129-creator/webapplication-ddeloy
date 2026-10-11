<?php

namespace Database\Seeders;

use App\Models\StoreProduct;
use Illuminate\Database\Seeder;

class StoreCatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('store_catalog.products') as $product) {
            StoreProduct::updateOrCreate(
                ['slug' => $product['id']],
                [
                    'name' => $product['name'],
                    'category' => $product['category'],
                    'price' => $product['price'],
                    'icon' => $product['icon'],
                    'tag' => $product['tag'],
                    'color' => $product['color'],
                    'description' => $product['description'],
                    'is_bestseller' => $product['bestseller'],
                    'is_active' => true,
                ]
            );
        }
    }
}
