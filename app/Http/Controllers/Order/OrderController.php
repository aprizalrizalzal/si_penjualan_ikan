<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function show()
    {
        if (Auth::user()->hasRole('admin')) {
            // Admin melihat semua pesanan
            $orders = Order::with('user', 'user.customer', 'orderItems.product', 'orderItems.product.seller')->get();
        } else {
            // User melihat pesanan mereka sendiri
            $orders = Order::where('user_id', Auth::id())->with('user', 'user.customer', 'orderItems.product', 'orderItems.product.seller')->get();
        }

        return Inertia::render('Orders/Orders', [
            'orders' => $orders
        ]);
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:orders,id',
        ]);

        $userId = Auth::id();

        if (Auth::user()->hasRole('admin')) {
            $order = Order::findOrFail($request->id);
        } else {
            // User hanya dapat menghapus pesanan mereka sendiri
            $order = Order::where('user_id', $userId)->where('id', $request->id)->firstOrFail();
        }

        $order = Order::findOrFail($request->id);

        // Periksa jika pengguna adalah user dan tidak memiliki hak atas pesanan ini
        if (!Auth::user()->hasRole('admin') && $order->user_id !== Auth::id()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Menghapus item pesanan terkait
        $order->orderItems()->delete();
        // Menghapus pesanan
        $order->delete();

        return redirect()->route('show.orders');
    }
}
