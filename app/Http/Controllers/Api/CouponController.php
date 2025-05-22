<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Coupon::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:30|unique:coupons,code,' . ($request->id ?? 'NULL') . ',id',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);
        $coupon = Coupon::create($data);
        return response()->json($coupon, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $cupon)
    {
        return response()->json($cupon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $cupon)
    {
        $data = $request->validate([
            'code' => 'required|string|max:30|unique:coupons,code,' . $cupon->id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);
        $cupon->update($data);
        return response()->json($cupon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $cupon)
    {
        $cupon->delete();
        return response()->json(null, 204);
    }
}
