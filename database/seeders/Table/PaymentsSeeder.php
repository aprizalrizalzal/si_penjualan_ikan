<?php

namespace Database\Seeders\Table;

use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ambil semua order yang ada
        $orders = Order::all();

        // Loop melalui setiap order dan buat payment
        foreach ($orders as $order) {
            Payment::create([
                'payment_code' => Str::upper(Str::random(8)),
                'order_id' => $order->id,
                'payment_method' => $this->getRandomPaymentMethod(),
                'amount' => $order->total_amount,
                'status' => 'check', // Sesuaikan status sesuai kebutuhan
            ]);
        }
    }

    /**
     * Dapatkan metode pembayaran acak.
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['Credit Card', 'Bank Transfer', 'PayPal', 'Cash on Delivery', 'E-Wallet'];
        return $methods[array_rand($methods)];
    }
}
