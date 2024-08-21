<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Menampilkan semua pembayaran atau pembayaran pengguna berdasarkan peran.
     */
    public function show()
    {
        $userId = Auth::id();

        if (Auth::user()->hasRole('admin')) {
            // Admin dapat melihat semua pembayaran
            $payments = Payment::with('order')->get();
        } else {
            // User hanya dapat melihat pembayaran mereka sendiri
            $payments = Payment::whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->with('order')->get();
        }

        return Inertia::render('Payments/Payments', [
            'payments' => $payments
        ]);
    }

    /**
     * Menambahkan pembayaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        $userId = Auth::id();
        $order = Order::where('user_id', $userId)
            ->where('id', $request->order_id)
            ->firstOrFail();

        // Membuat pembayaran baru
        Payment::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return redirect()->route('show.payments');
    }

    /**
     * Memperbarui pembayaran.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:payments,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'status' => 'required|string|in:pending,completed,failed',
        ]);

        $userId = Auth::id();
        $payment = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', $request->id)->firstOrFail();

        // Memperbarui pembayaran
        $payment->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return redirect()->route('show.payments');
    }

    /**
     * Menghapus pembayaran.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:payments,id',
        ]);

        $userId = Auth::id();
        $payment = Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', $request->id)->firstOrFail();

        // Menghapus pembayaran
        $payment->delete();

        return redirect()->route('show.payments');
    }
}
