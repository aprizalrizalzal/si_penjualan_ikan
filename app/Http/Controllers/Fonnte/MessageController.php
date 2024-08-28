<?php

namespace App\Http\Controllers\Fonnte;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Service\FonnteService;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }

    public function send_order_message($order_code)
    {
        // Mengambil semua pengguna dengan role 'admin'
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $to = $admin->customer->phone;
            $order = Order::where('order_code', $order_code)->first();

            if ($order) {
                $message = "*SIPI-Desa Soro*\n"
                    . "Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, Nusa Tenggara Barat.\n\n"
                    . "*Kode Pesanan: " . $order->order_code . "*\n\n"
                    . "*" . url('/orders') . "*\n\n"
                    . "*Terima kasih atas pesanan Anda. Salam hangat dari kami, Kampung Nelayan Desa Soro.*\n";

                $response = $this->fonnte->sendMessage($to, $message);

                // Mencatat log respon
                Log::info('Fonnte Response for Order Message:', ['response' => $response]);
            } else {
                Log::warning('Order not found for order code: ' . $order_code);
            }
        }
        return redirect()->route('show.orders')->with('status', 'Messages sent!');
    }

    public function send_payment_message($payment_code)
    {
        // Mengambil semua pengguna dengan role 'admin'
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $to = $admin->customer->phone;
            $payment = Payment::where('payment_code', $payment_code)->first();

            if ($payment) {
                $message = "*SIPI-Desa Soro*\n"
                    . "Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, Nusa Tenggara Barat.\n\n"
                    . "*Kode Pembayaran: " . $payment->payment_code . "*\n"
                    . "" . url('/payments') . "\n\n"
                    . "*Terima kasih atas pembayaran Anda. Salam hangat dari kami, Kampung Nelayan Desa Soro.*\n";

                $response = $this->fonnte->sendMessage($to, $message);

                // Mencatat log respon
                Log::info('Fonnte Response for Payment Message:', ['response' => $response]);
            } else {
                Log::warning('Payment not found for payment code: ' . $payment_code);
            }
        }
        return redirect()->route('show.payments')->with('status', 'Messages sent!');
    }
}
