<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'alt' => 'required|string',
        ]);

        $existingImagesCount = PaymentImage::where('payment_id', $request->payment_id)->count();
        if ($existingImagesCount >= 2) {
            return redirect()->route('show.payments')->withErrors(['image' => 'Maksimal 2 gambar per pembayaran.']);
        }

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/paymentImages', $uniqueName, 'public');

        PaymentImage::create([
            'image' => 'storage/' . $path,
            'payment_id' => $request->payment_id,
            'alt' => $request->alt,
        ]);

        return redirect()->route('show.payments');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:payment_images,id',
        ]);

        $paymentImage = PaymentImage::findOrFail($request->id);

        $path = str_replace('storage/', '', $paymentImage->image);
        Storage::disk('public')->delete($path);

        $paymentImage->delete();

        return redirect()->route('show.payments');
    }
}
