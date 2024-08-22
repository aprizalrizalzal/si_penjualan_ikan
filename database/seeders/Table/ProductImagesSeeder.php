<?php

namespace Database\Seeders\Table;

use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ambil semua produk yang ada
        $products = Product::all();

        // Loop melalui setiap produk dan buat gambar terkait
        foreach ($products as $product) {
            // Tentukan jumlah gambar yang ingin dibuat per produk
            $imageCount = rand(1, 4);

            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $this->getRandomImageName(),
                    'alt' => 'Image for ' . $product->name,
                ]);
            }
        }
    }

    /**
     * Dapatkan nama gambar acak untuk keperluan seeder.
     * Fungsi ini dapat disesuaikan dengan logika Anda untuk menentukan gambar.
     */
    private function getRandomImageName()
    {
        $images = [
            'storage/images/products/product_image_1.jpg',
            'storage/images/products/product_image_2.jpg',
            'storage/images/products/product_image_3.jpg',
            'storage/images/products/product_image_4.jpg',
        ];

        return $images[array_rand($images)];
    }
}
