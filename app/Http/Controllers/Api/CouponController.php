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
            'min_subtotal' => 'nullable|numeric|min:0',
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
            'min_subtotal' => 'nullable|numeric|min:0',
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

    public function validar($codigo)
    {
        $cupom = Coupon::where('code', strtoupper($codigo))
            ->where(function($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->first();

        if (!$cupom) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Cupom inválido ou expirado'
            ], 400);
        }

        // Verifica se o cupom já foi usado o máximo de vezes
        if ($cupom->usage_limit && $cupom->used_count >= $cupom->usage_limit) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Cupom atingiu o limite máximo de usos'
            ], 400);
        }

        // Verifica valor mínimo do pedido
        $subtotal = request()->subtotal ?? 0;
        if ($cupom->min_subtotal && $subtotal < $cupom->min_subtotal) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Este cupom só pode ser aplicado em pedidos a partir de R$ ' . number_format($cupom->min_subtotal, 2, ',', '.')
            ], 400);
        }

        // Calcula o desconto baseado no tipo do cupom
        $desconto = 0;
        if ($cupom->discount_type === 'percent') {
            $desconto = ($cupom->discount_value / 100) * $subtotal;
        } else {
            $desconto = $cupom->discount_value;
        }

        return response()->json([
            'valido' => true,
            'id' => $cupom->id,
            'desconto' => $desconto,
            'mensagem' => 'Cupom aplicado com sucesso!'
        ]);
    }
}
