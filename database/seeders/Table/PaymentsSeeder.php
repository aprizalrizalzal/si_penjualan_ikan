<?php

namespace Database\Seeders\Table;

use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Carbon\Carbon;
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
                'status' => $this->getRandomStatus(), // Menggunakan metode acak untuk status
                'created_at' => $this->getRandomCreatedAt(), // Menggunakan metode acak untuk tanggal
            ]);
        }
    }

    /**
     * Dapatkan metode pembayaran acak.
     */
    private function getRandomPaymentMethod()
    {
        $methods = ['Bank Transfer', 'Cash on Delivery', 'E-Wallet'];
        return $methods[array_rand($methods)];
    }

    /**
     * Dapatkan status acak.
     */
    private function getRandomStatus()
    {
        $statuses = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * Dapatkan tanggal acak.
     */
    private function getRandomCreatedAt()
    {
        $startDate = Carbon::now()->subDays(6); // Mulai dari 6 hari yang lalu
        $endDate = Carbon::now(); // Hingga tanggal saat ini

        return Carbon::createFromTimestamp(mt_rand($startDate->timestamp, $endDate->timestamp));
    }
}
