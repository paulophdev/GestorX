<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariationController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->variations);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['product_id'] = $product->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('variations', 'public');
            $data['image'] = $path;
        }

        $variation = Variation::create($data);
        return response()->json($variation, 201);
    }

    public function show(Product $product, Variation $variation)
    {
        return response()->json($variation);
    }

    public function update(Request $request, Product $product, Variation $variation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($variation->image) {
                Storage::disk('public')->delete($variation->image);
            }
            $path = $request->file('image')->store('variations', 'public');
            $data['image'] = $path;
        }

        $variation->update($data);
        return response()->json($variation);
    }

    public function destroy(Product $product, Variation $variation)
    {
        if ($variation->image) {
            Storage::disk('public')->delete($variation->image);
        }
        $variation->delete();
        return response()->json(null, 204);
    }
}
