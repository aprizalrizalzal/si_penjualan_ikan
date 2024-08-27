<?php

namespace Database\Seeders\Table;

use App\Models\Payment\PaymentImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentImages = [
            [
                'payment_id' => 1,
                'image' => 'storage/images/payments/payment_image_1.jpg',
                'alt' => '1',
            ],
            [
                'payment_id' => 2,
                'image' => 'storage/images/payments/payment_image_2.png',
                'alt' => '2',
            ],
            [
                'payment_id' => 3,
                'image' => 'storage/images/payments/payment_image_3.jpg',
                'alt' => '3',
            ],
        ];

        foreach ($paymentImages as $paymentImage) {
            PaymentImage::create($paymentImage);
        }
    }
}
