<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function show()
    {
        $categories = Category::withCount('products')->get();

        return Inertia::render('Products/Categories', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:' . Category::class,
            'description' => 'required|string',
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('show.categories');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($request->id),
            ],
            'description' => 'required|string',
        ]);

        $category = Category::findOrFail($request->id);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('show.categories');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
        ]);

        $category = Category::findOrFail($request->id);
        $category->delete();

        return redirect()->route('show.categories');
    }
}
