<?php

namespace Database\Seeders\Table;

use App\Models\Seller\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            [
                'name' => 'Penjual Satu',
                'email' => 'penjualsatu@email.com',
                'phone' => '08765765322',
                'address' => 'Alamat lengkap Penjual'
            ],
            [
                'name' => 'Penjual Dua',
                'email' => 'penjualdua@email.com',
                'phone' => '08765765323',
                'address' => 'Alamat lengkap Penjual'
            ],
            [
                'name' => 'Penjual Tiga',
                'email' => 'penjualtiga@email.com',
                'phone' => '08765765324',
                'address' => 'Alamat lengkap Penjual'
            ],
        ];

        foreach ($sellers as $seller) {
            Seller::create($seller);
        }
    }
}
