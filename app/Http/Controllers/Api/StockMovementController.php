<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        return response()->json($product->stockMovements()->orderBy('created_at')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'value' => 'required|numeric',
            'note' => 'nullable|string|max:255',
        ]);
        $data['product_id'] = $product->id;
        $movement = StockMovement::create($data);
        return response()->json($movement, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, StockMovement $stockMovement)
    {
        if ($stockMovement->product_id !== $product->id) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $stockMovement->delete();
        return response()->json(null, 204);
    }
}
