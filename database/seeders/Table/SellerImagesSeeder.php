<?php

namespace Database\Seeders\Table;

use App\Models\Seller\SellerImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sellerImages = [
            [
                'seller_id' => 1,
                'image' => 'storage/images/sellers/seller_image_1.jpg',
                'alt' => '1',
            ],
            [
                'seller_id' => 2,
                'image' => 'storage/images/sellers/seller_image_2.jpg',
                'alt' => '2',
            ],
            [
                'seller_id' => 3,
                'image' => 'storage/images/sellers/seller_image_3.jpg',
                'alt' => '3',
            ],
        ];

        foreach ($sellerImages as $sellerImage) {
            SellerImage::create($sellerImage);
        }
    }
}
