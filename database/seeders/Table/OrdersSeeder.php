<?php

namespace Database\Seeders\Table;

use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua pengguna yang bukan admin
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'admin');
        })->get();

        foreach ($users as $user) {
            // Buat pesanan baru
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending', // Set status default menjadi 'pending'
                'total_amount' => 0, // Nilai sementara, akan diperbarui setelah item ditambahkan
            ]);

            $totalAmount = 0;

            // Ambil beberapa produk untuk item pesanan
            $products = Product::inRandomOrder()->take(rand(1, 5))->get();

            foreach ($products as $product) {
                $quantity = rand(1, 10); // Kuantitas item pesanan

                // Hitung total item pesanan
                $orderItemTotal = $product->price * $quantity;

                // Buat item pesanan
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                $totalAmount += $orderItemTotal;
            }

            // Perbarui total_amount pada pesanan
            $order->update(['total_amount' => $totalAmount]);
        }
    }
}
