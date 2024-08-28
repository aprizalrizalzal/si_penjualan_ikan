<?php

namespace App\Http\Controllers\Fonnte;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Service\FonnteService;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        $to = $user->customer->phone;
        $order = Order::where('order_code', $order_code)->first();

        if ($order) {
            $message = "*SIPI-Desa Soro*\n"
                . "Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, Nusa Tenggara Barat.\n\n"
                . "*Kode Pesanan " . $order->order_code . "* a/n *" . $user->name . "*\n\n"
                . "Untuk informasi lebih detail, Anda bisa mengunjungi website kami di "
                . "*" . url('/orders') . "*\n\n"
                . "*_Terima kasih atas pesanan Anda. Salam hangat dari kami, Kampung Nelayan Desa Soro._*\n";

            try {
                // Kirim pesan
                $response = $this->fonnte->sendMessage($to, $message);
                // Mencatat log respon
                Log::info('Fonnte Response for Order Message:', ['response' => $response]);
            } catch (\Exception $e) {
                // Log pesan error atau lakukan tindakan lainnya
                Log::error('Failed to send message: ' . $e->getMessage());

                // Anda juga bisa menambahkan flash message atau indikasi lainnya untuk pengguna
                session()->flash('error', 'Gagal mengirim pesan, silakan coba lagi nanti.');
            }
        } else {
            Log::warning('Order not found for order code: ' . $order_code);
        }

        return redirect()->route('show.orders')->with('status', 'Message sent!');
    }

    public function send_payment_message($payment_code)
    {
        // Mengambil semua pengguna dengan role 'admin'
        $user = Auth::user();

        $to = $user->customer->phone;
        $payment = Payment::where('payment_code', $payment_code)->first();

        if ($payment) {
            $message = "*SIPI-Desa Soro*\n"
                . "Kampung Nelayan Desa Soro, Kecamatan Kempo, Dompu, Nusa Tenggara Barat.\n\n"
                . "*Kode Pembayaran " . $payment->payment_code . "* a/n " . $user->name . "\n\n"
                . "Untuk informasi lebih detail, Anda bisa mengunjungi website kami di "
                . "" . url('/payments') . "\n\n"
                . "*Terima kasih atas pembayaran Anda. Salam hangat dari kami, Kampung Nelayan Desa Soro.*\n";

            $response = $this->fonnte->sendMessage($to, $message);

            // Mencatat log respon
            Log::info('Fonnte Response for Payment Message:', ['response' => $response]);
        } else {
            Log::warning('Payment not found for payment code: ' . $payment_code);
        }
        return redirect()->route('show.payments')->with('status', 'Messages sent!');
    }
}
