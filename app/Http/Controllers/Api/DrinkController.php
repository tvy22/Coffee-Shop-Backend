<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DrinkResource;
use App\Models\Drink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DrinkController extends Controller
{
    public function index()
    {
        $drinks = Drink::with('category')->get();
        return DrinkResource::collection($drinks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'unit_price'  => 'required|numeric|min:0',
            'in_stock'    => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink = Drink::create($validated);
        return new DrinkResource($drink->load('category'));
    }

    public function show(Drink $drink)
    {
        return new DrinkResource($drink->load('category'));
    }

    public function update(Request $request, Drink $drink)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name'        => 'sometimes|required|string|max:255',
            'unit_price'  => 'sometimes|required|numeric|min:0',
            'in_stock'    => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048|url',
        ]);

        if ($request->hasFile('image')) {
            if ($drink->image && ! filter_var($drink->image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($drink->image);
            }
            $validated['image'] = $request->file('image')->store('drinks', 'public');
        }

        $drink->update($validated);
        return response()->json([
            'message' => "Drink updated successfully!",
            'data' => new DrinkResource($drink->load('category'))
        ], 200);

    }

    public function destroy(Drink $drink)
    {
        if ($drink->image && ! filter_var($drink->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($drink->image);
        }

        $drink->delete();
        return response()->json(['message' => 'Drink deleted successfully']);
    }
}
