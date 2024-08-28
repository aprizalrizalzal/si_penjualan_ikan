<?php

namespace Database\Seeders\Table;

use App\Models\Customer\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'user_id' => 1,
                'phone' => '087765889202',
                'address' => 'Alamat lengkap admin'

            ],
            [
                'user_id' => 2,
                'phone' => '087857054203',
                'address' => 'Alamat lengkap pembeli satu'

            ],
            [
                'user_id' => 3,
                'phone' => '087765543322',
                'address' => 'Alamat lengkap pembeli dua'
            ],
            [
                'user_id' => 4,
                'phone' => '087765543323',
                'address' => 'Alamat lengkap pembeli tiga'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
