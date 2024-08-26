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
                'seller_id' => 1,
                'name' => 'Ikan Tuna',
                'description' => 'Ikan Tuna segar langsung dari laut. Berat rata-rata sekitar 40-60 kg, tetapi bisa mencapai hingga lebih dari 200 kg.',
                'price' => 225000,
                'stock' => 100,
                'weight' => 40.5,
                'category_id' => 1,
            ],
            [
                'seller_id' => 2,
                'name' => 'Ikan Lele',
                'description' => 'Ikan Lele segar dari tambak. Berat rata-rata sekitar 1-5 kg.',
                'price' => 30000,
                'stock' => 200,
                'weight' => 1.2,
                'category_id' => 2,
            ],
            [
                'seller_id' => 3,
                'name' => 'Udang Lobster',
                'description' => 'Lobster laut dengan ukuran besar. Berat rata-rata sekitar 0.5-2 kg.',
                'price' => 300000,
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
