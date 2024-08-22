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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'alt' => 'required|string',
        ]);

        $existingImagesCount = ProductImage::where('product_id', $request->product_id)->count();
        if ($existingImagesCount >= 4) {
            return redirect()->route('show.spare.parts.images')->withErrors(['image' => 'Maksimal 4 gambar per spare part.']);
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/productImages', $uniqueName, 'public');

        ProductImage::create([
            'image' => 'storage/' . $path,
            'product_id' => $request->product_id,
            'alt' => $request->alt,
        ]);

        return redirect()->route('show.spare.parts.images');
    }

    public function update_image(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_images,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $productImage = ProductImage::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $oldPath = str_replace('storage/', '', $productImage->image);
            Storage::disk('public')->delete($oldPath);

            $originalName = $request->file('image')->getClientOriginalName();
            $uniqueName = time() . '_' . $originalName;
            $path = $request->file('image')->storeAs('images/productImages', $uniqueName, 'public');

            $productImage->image = 'storage/' . $path;
            $productImage->save();
        }

        return redirect()->route('welcome');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_images,id',
            'alt' => 'required|string|max:255',
        ]);

        $product = ProductImage::findOrFail($request->id);

        $product->update([
            'alt' => $request->alt,
        ]);

        return redirect()->route('welcome');
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

        return redirect()->route('show.spare.parts.images');
    }
}
