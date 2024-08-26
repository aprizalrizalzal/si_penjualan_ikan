<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller\SellerImage;
use Illuminate\Http\Request;

class SellerImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'alt' => 'required|string',
        ]);

        $existingImagesCount = SellerImage::where('seller_id', $request->seller_id)->count();
        if ($existingImagesCount >= 4) {
            return redirect()->route('show.sellers')->withErrors(['image' => 'Maksimal 4 gambar per produk.']);
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/sellers', $uniqueName, 'public');

        SellerImage::create([
            'image' => 'storage/' . $path,
            'seller_id' => $request->seller_id,
            'alt' => $request->alt,
        ]);

        return redirect()->route('show.sellers');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:seller_images,id',
        ]);

        $sellerImage = SellerImage::findOrFail($request->id);

        $path = str_replace('storage/', '', $sellerImage->image);
        Storage::disk('public')->delete($path);

        $sellerImage->delete();

        return redirect()->route('show.sellers');
    }
}
