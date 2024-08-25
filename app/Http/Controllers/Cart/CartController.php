<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Menampilkan keranjang pengguna.
     */
    public function show()
    {
        $userId = Auth::id();

        $carts = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        return Inertia::render('Carts/Carts', [
            'carts' => $carts
        ]);
    }

    /**
     * Memperbarui item di keranjang.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
            ->where('id', $request->id)
            ->firstOrFail();

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('show.carts');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:carts,id',
        ]);

        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
            ->where('id', $request->id)
            ->firstOrFail();

        $cart->delete();

        return redirect()->route('show.carts');
    }

    /**
     * Melakukan store orders dari keranjang dan membuat pesanan.
     */
    public function store_order()
    {
        $userId = Auth::id();

        // Ambil semua item keranjang pengguna
        $carts = Cart::where('user_id', $userId)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('show.carts');
        }

        $totalAmount = 0;
        $errors = [];

        // Cek stok untuk setiap produk dalam keranjang
        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart->product_id);

            // Cek apakah kuantitas lebih besar dari stok
            if ($cart->quantity > $product->stock) {
                // Tambahkan pesan error untuk produk ini
                $errors[] = "Stok untuk produk {$product->name} hanya tersisa {$product->stock}. Anda mencoba memesan {$cart->quantity}.";
            }
        }

        // Jika ada error, kembalikan dengan pesan error yang dikumpulkan
        if (count($errors) > 0) {
            return back()->withErrors(['messages' => $errors]);
        }

        // Jika tidak ada error, buat pesanan baru
        $order = Order::create([
            'order_code' => Str::upper(Str::random(8)),
            'user_id' => $userId,
            'status' => 'check', // Set default status
            'total_amount' => 0, // Akan diperbarui setelah item ditambahkan
        ]);

        // Pindahkan item dari keranjang ke order
        foreach ($carts as $cart) {
            $product = Product::findOrFail($cart->product_id);

            $orderItemTotal = $product->price * $cart->quantity;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $product->price,
            ]);

            // Kurangi stok produk
            $product->update(['stock' => $product->stock - $cart->quantity]);

            $totalAmount += $orderItemTotal;
        }

        // Perbarui total amount pada order
        $order->update(['total_amount' => $totalAmount]);

        // Hapus semua item keranjang setelah store order
        Cart::where('user_id', $userId)->delete();

        return redirect()->route('show.orders');
    }
}
