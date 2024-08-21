<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
     * Menambahkan item ke keranjang.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // Check if the product already exists in the cart
        $cart = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            // If product exists, update the quantity
            $cart->update([
                'quantity' => $cart->quantity + $request->quantity,
            ]);
        } else {
            // If product doesn't exist, create a new cart entry
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('show.carts');
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
}
