<?php

namespace Database\Seeders\Table;

use App\Models\Product\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ikan Laut', 'description' => 'Kategori untuk ikan yang hidup di laut.'],
            ['name' => 'Ikan Air Tawar', 'description' => 'Kategori untuk ikan yang hidup di air tawar.'],
            ['name' => 'Seafood', 'description' => 'Kategori untuk produk makanan laut lainnya.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
