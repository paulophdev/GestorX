<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
