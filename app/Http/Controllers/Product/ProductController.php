<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function show()
    {
        // Mengambil semua produk dengan kategori terkait
        $products = Product::with('category')->get();

        return Inertia::render('Products/Products', [
            'products' => $products
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
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'category_id' => $request->category_id,
        ]);

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
}
