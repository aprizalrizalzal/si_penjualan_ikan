<?php

namespace Database\Seeders\Table;

use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrdersSeeder extends Seeder
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
        foreach ($users->random(min($userCount, 3)) as $user) { // Menggunakan 5 sebagai batasan maksimum atau total pengguna jika kurang dari 5
            $order = Order::create([
                'order_code' => Str::upper(Str::random(8)),
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0, // Nilai sementara, akan diperbarui setelah item ditambahkan
            ]);

            $totalAmount = 0;

            // Ambil produk secara acak
            $products = Product::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($products as $product) {
                $quantity = rand(1, 10);

                $orderItemTotal = $product->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                $totalAmount += $orderItemTotal;
            }

            // Update total amount di pesanan
            $order->update(['total_amount' => $totalAmount]);
        }
    }
}
