<?php

namespace Database\Seeders\Table;

use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->get();

        $userCount = $users->count();
        if ($userCount == 0) {
            return; // Jika tidak ada pengguna, hentikan seeder
        }

        // Buat pesanan untuk pengguna secara acak
        foreach ($users->random(min($userCount, 3)) as $user) {
            // Ambil produk secara acak untuk setiap pengguna
            $products = Product::inRandomOrder()->take(rand(1, 10))->get();

            foreach ($products as $product) {
                $quantity = rand(1, 10); // Jumlah acak untuk setiap produk

                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
