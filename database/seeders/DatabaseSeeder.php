<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Table\CategoriesSeeder;
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
            UsersSeeder::class,
            RolesSeeder::class,
            RoleUserSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
        ]);
    }
}
