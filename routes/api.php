<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\VariationController;
use App\Http\Controllers\Api\CouponController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Rotas de Produtos
    Route::get('/produtos', [ProductController::class, 'index']);
    Route::post('/produtos', [ProductController::class, 'store']);
    Route::get('/produtos/{product}', [ProductController::class, 'show']);
    Route::put('/produtos/{product}', [ProductController::class, 'update']);
    Route::delete('/produtos/{product}', [ProductController::class, 'destroy']);

    // Rotas de Variações
    Route::get('/produtos/{product}/variacoes', [VariationController::class, 'index']);
    Route::post('/produtos/{product}/variacoes', [VariationController::class, 'store']);
    Route::get('/produtos/{product}/variacoes/{variation}', [VariationController::class, 'show']);
    Route::put('/produtos/{product}/variacoes/{variation}', [VariationController::class, 'update']);
    Route::delete('/produtos/{product}/variacoes/{variation}', [VariationController::class, 'destroy']);

    // Rotas de Cupons
    Route::apiResource('cupons', CouponController::class);
}); 