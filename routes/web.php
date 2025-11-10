<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store']);
Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'edit', 'update']);
