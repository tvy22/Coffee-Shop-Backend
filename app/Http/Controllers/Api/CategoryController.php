<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('drinks')->get();
        return CategoryResource::collection($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    // Check if it's an uploaded file
                    if ($request->hasFile('image')) {
                        return;
                    }
                    // Check if it's a valid URL string
                    if (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                        return;
                    }
                    $fail('The image must be a valid uploaded file or a valid image URL.');
                },
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create($validated);
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category->load('drinks'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|url',
        ]);

        // Handle image replacement
        if ($request->hasFile('image')) {
            // Delete old file if stored locally
            if ($category->image && ! filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);
        return new CategoryResource($category->load('drinks'));
    }

    public function destroy(Category $category)
    {
        if ($category->image && ! filter_var($category->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
