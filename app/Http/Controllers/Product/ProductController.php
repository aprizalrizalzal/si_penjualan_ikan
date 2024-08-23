<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function show()
    {
        // Mengambil semua produk dengan kategori terkait
        $products = Product::with('category', 'productImages')->get();
        $categories = Category::all();

        return Inertia::render('Products/Products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255|unique:' . Product::class,
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Membuat produk baru
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'category_id' => $request->category_id,
        ]);

        // Tambahkan 4 gambar kosong
        for ($i = 1; $i <= 4; $i++) {
            ProductImage::create([
                'image' => 'storage/images/products/image.jpg',
                'product_id' => $product->id,
                'alt' => 'image' . $i,
            ]);
        }

        return redirect()->route('show.products');
    }


    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:products,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($request->id),
            ],
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::findOrFail($request->id);

        // Memperbarui produk
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('show.products');
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->id);
        // Menghapus produk
        $product->delete();

        return redirect()->route('show.products');
    }

    /**
     * Melakukan store carts dari product dan membuat keranjang.
     */
    public function store_cart(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
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
                'user_id' => $userId, // Use Authenticated user ID
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('welcome');
    }
}
