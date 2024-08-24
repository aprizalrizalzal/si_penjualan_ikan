<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'alt' => 'required|string',
        ]);

        $existingImagesCount = ProductImage::where('product_id', $request->product_id)->count();
        if ($existingImagesCount >= 4) {
            return redirect()->route('show.products')->withErrors(['image' => 'Maksimal 4 gambar per produk.']);
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/products', $uniqueName, 'public');

        ProductImage::create([
            'image' => 'storage/' . $path,
            'product_id' => $request->product_id,
            'alt' => $request->alt,
        ]);

        return redirect()->route('show.products');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_images,id',
        ]);

        $productImage = ProductImage::findOrFail($request->id);

        $path = str_replace('storage/', '', $productImage->image);
        Storage::disk('public')->delete($path);

        $productImage->delete();

        return redirect()->route('show.products');
    }
}
