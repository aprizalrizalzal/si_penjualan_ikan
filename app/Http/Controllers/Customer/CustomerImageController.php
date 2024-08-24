<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\CustomerImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'alt' => 'required|string',
        ]);

        $existingImagesCount = CustomerImage::where('customer_id', $request->customer_id)->count();
        if ($existingImagesCount >= 1) {
            return redirect()->route('profile.update')->withErrors(['image' => 'Maksimal 1 gambar per pengguna.']);
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/customers', $uniqueName, 'public');

        CustomerImage::create([
            'image' => 'storage/' . $path,
            'customer_id' => $request->customer_id,
            'alt' => $request->alt,
        ]);

        return redirect()->route('profile.update');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customer_images,id',
        ]);

        $paymentImage = CustomerImage::findOrFail($request->id);

        $path = str_replace('storage/', '', $paymentImage->image);
        Storage::disk('public')->delete($path);

        $paymentImage->delete();

        return redirect()->route('profile.update');
    }
}
