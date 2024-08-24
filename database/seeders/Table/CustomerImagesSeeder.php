<?php

namespace Database\Seeders\Table;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $customerImages = [
            [
                'customer_id' => 1,
                'image' => 'storage/images/customers/customer_image_1.jpg',
                'alt' => '1',
            ],
            [
                'customer_id' => 2,
                'image' => 'storage/images/customers/customer_image_2.jpg',
                'alt' => '2',
            ],
            [
                'customer_id' => 3,
                'image' => 'storage/images/customers/customer_image_3.jpg',
                'alt' => '3',
            ],
            [
                'customer_id' => 4,
                'image' => 'storage/images/customers/customer_image_4.jpg',
                'alt' => '4',
            ]
        ];

        foreach ($customerImages as $customerImage) {
            CustomerImage::create($customerImage);
        }
    }
}
