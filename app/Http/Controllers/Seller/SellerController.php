<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller\Seller;
use App\Models\Seller\SellerImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SellerController extends Controller
{
    public function show()
    {

        // Cek apakah user memiliki peran admin
        if (!Auth::user()->hasRole('admin')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Mengambil semua penjual dengan sellerImage terkait
        $sellers = Seller::with('sellerImage')->get();

        return Inertia::render('Users/Sellers/Sellers', [
            'sellers' => $sellers
        ]);
    }

    public function store(Request $request)
    {
        // Cek apakah user memiliki peran admin
        if (!Auth::user()->hasRole('admin')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . Seller::class,
            'phone' => 'required|string|max:255|unique:' . Seller::class,
            'address' => 'required|string|max:255',
        ]);

        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'alt' => 'required|string',
        ]);

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/sellers', $uniqueName, 'public');

        SellerImage::create([
            'seller_id' => $seller->id,
            'image' => 'storage/' . $path,
            'alt' => $request->alt,
        ]);

        return redirect()->route('show.sellers');
    }

    /**
     * Memperbarui penjual.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sellers,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('sellers')->ignore($request->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sellers')->ignore($request->id),
            ],
            'address' => 'required|string|max:255',
        ]);

        // Cek apakah user memiliki peran admin
        if (!Auth::user()->hasRole('admin')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Admin dapat update semua penjual
        $seller = Seller::where('id', $request->id)->firstOrFail();

        // Memperbarui penjual
        $seller->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('show.sellers');
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:sellers,id',
        ]);

        // Cari penjual dan gambar terkait
        $seller = Seller::findOrFail($request->id);

        // Hapus penjual dari database
        $seller->delete();

        return redirect()->route('show.sellers');
    }
}
