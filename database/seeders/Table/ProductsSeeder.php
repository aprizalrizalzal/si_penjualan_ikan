<?php

namespace Database\Seeders\Table;

use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Ikan Tuna',
                'description' => 'Ikan Tuna segar langsung dari laut.',
                'price' => 75000,
                'stock' => 100,
                'weight' => 2.5,
                'category_id' => 1,
            ],
            [
                'name' => 'Ikan Lele',
                'description' => 'Ikan Lele segar dari tambak.',
                'price' => 30000,
                'stock' => 200,
                'weight' => 1.2,
                'category_id' => 2,
            ],
            [
                'name' => 'Udang Lobster',
                'description' => 'Lobster laut dengan ukuran besar.',
                'price' => 150000,
                'stock' => 50,
                'weight' => 1.8,
                'category_id' => 3,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
