<?php

namespace App\Http\Controllers\Banner;

use App\Http\Controllers\Controller;
use App\Models\Banner\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'alt' => 'required|string|max:255',
        ]);

        $originalName = $request->file('image')->getClientOriginalName();
        $uniqueName = time() . '_' . $originalName;
        $path = $request->file('image')->storeAs('images/banners', $uniqueName, 'public');

        Banner::create([
            'alt' => $request->alt,
            'image' => 'storage/' . $path,
        ]);

        return redirect()->route('welcome');
    }

    public function update_image(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        $banner = Banner::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $oldPath = str_replace('storage/', '', $banner->image);
            Storage::disk('public')->delete($oldPath);

            $originalName = $request->file('image')->getClientOriginalName();
            $uniqueName = time() . '_' . $originalName;
            $path = $request->file('image')->storeAs('images/banners', $uniqueName, 'public');

            $banner->image = 'storage/' . $path;
            $banner->save();
        }

        return redirect()->route('welcome');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'alt' => 'required|string|max:255',
        ]);

        $banner = Banner::findOrFail($request->id);

        $banner->update([
            'alt' => $request->alt,
        ]);

        return redirect()->route('welcome');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
        ]);

        $banner = Banner::findOrFail($request->id);

        $path = str_replace('storage/', '', $banner->image);
        Storage::disk('public')->delete($path);

        $banner->delete();

        return redirect()->route('welcome');
    }
}
