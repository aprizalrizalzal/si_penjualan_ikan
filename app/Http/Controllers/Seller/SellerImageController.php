<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller\SellerImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerImageController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sellerImages,id',
            'image' => 'nullable|image|mimes:png|max:1024',
        ]);

        $sellerImage = SellerImage::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $oldPath = str_replace('storage/', '', $sellerImage->image_path);
            Storage::disk('public')->delete($oldPath);

            $originalName = $request->file('image')->getClientOriginalName();
            $uniqueName = time() . '_' . $originalName;
            $path = $request->file('image')->storeAs('images/sellerImage', $uniqueName, 'public');

            $sellerImage->image_path = 'storage/' . $path;
            $sellerImage->save();
        }

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
