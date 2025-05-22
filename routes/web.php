<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('loja.index');
});

Route::get('/dashboard/produtos', function () {
    return view('dashboard.produtos.index');
});

Route::get('/dashboard/cupons', function () {
    return view('dashboard.cupons.index');
});

Route::get('/loja', function () {
    return view('loja.index');
});

Route::get('/carrinho', function () {
    return view('loja.carrinho');
});

Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Rotas de Pedidos
    Route::get('/', [App\Http\Controllers\Dashboard\OrderController::class, 'index'])->name('orders.dashboard');
    Route::get('/pedidos', [App\Http\Controllers\Dashboard\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [App\Http\Controllers\Dashboard\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pedidos/{order}/status', [App\Http\Controllers\Dashboard\OrderController::class, 'updateStatus'])->name('orders.update-status');
});
