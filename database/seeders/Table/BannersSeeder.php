<?php

namespace Database\Seeders\Table;

use App\Models\Banner\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'image' => 'storage/images/banners/banner_image_1.jpg',
                'alt' => 'Ikan Tuna',
            ],
            [
                'image' => 'storage/images/banners/banner_image_2.jpg',
                'alt' => 'Ikan Lele',
            ],
            [
                'image' => 'storage/images/banners/banner_image_3.jpg',
                'alt' => 'Udang Lobster',
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
