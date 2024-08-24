<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Table\BannersSeeder;
use Database\Seeders\Table\CartsSeeder;
use Database\Seeders\Table\CategoriesSeeder;
use Database\Seeders\Table\CustomersSeeder;
use Database\Seeders\Table\OrderItemsSeeder;
use Database\Seeders\Table\OrdersSeeder;
use Database\Seeders\Table\PaymentsSeeder;
use Database\Seeders\Table\ProductImagesSeeder;
use Database\Seeders\Table\ProductsSeeder;
use Database\Seeders\Table\RolesSeeder;
use Database\Seeders\Table\RoleUserSeeder;
use Database\Seeders\Table\UsersSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            BannersSeeder::class,
            UsersSeeder::class,
            CustomersSeeder::class,
            RolesSeeder::class,
            RoleUserSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            ProductImagesSeeder::class,
            CartsSeeder::class,
            OrdersSeeder::class,
            PaymentsSeeder::class,
        ]);
    }
}
