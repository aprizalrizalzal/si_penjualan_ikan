<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function show()
    {
        // Mengambil semua pesanan dengan item pesanan dan produk terkait
        $orders = Order::with('orderItems.product')->get();

        return Inertia::render('Orders/Orders', [
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => [
                'required',
                Rule::in(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
            ],
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        // Membuat pesanan baru
        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'total_amount' => 0, // Nilai sementara, akan diperbarui setelah item ditambahkan
        ]);

        $totalAmount = 0;

        // Menambahkan item pesanan dan menghitung total amount
        foreach ($request->order_items as $orderItem) {
            $product = Product::findOrFail($orderItem['product_id']);
            $orderItemTotal = $product->price * $orderItem['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $orderItem['product_id'],
                'quantity' => $orderItem['quantity'],
                'price' => $product->price,
            ]);

            $totalAmount += $orderItemTotal;
        }

        // Memperbarui total_amount pada pesanan
        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.index');
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => [
                'required',
                Rule::in(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
            ],
            'order_items' => 'required|array',
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($request->id);

        // Memperbarui status pesanan
        $order->update([
            'status' => $request->status,
        ]);

        $totalAmount = 0;
        // Menghapus item pesanan lama
        $order->orderItems()->delete();

        // Menambahkan item pesanan yang baru dan menghitung total amount
        foreach ($request->order_items as $orderItem) {
            $product = Product::findOrFail($orderItem['product_id']);
            $orderItemTotal = $product->price * $orderItem['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $orderItem['product_id'],
                'quantity' => $orderItem['quantity'],
                'price' => $product->price,
            ]);

            $totalAmount += $orderItemTotal;
        }

        // Memperbarui total_amount pada pesanan
        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('orders.index');
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->id);
        // Menghapus item pesanan terkait
        $order->orderItems()->delete();
        // Menghapus pesanan
        $order->delete();

        return redirect()->route('orders.index');
    }
}
