<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'show']);
Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
