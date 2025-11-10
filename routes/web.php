<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'show']);
Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
Route::resource('products', ProductController::class)->except(['destroy']);

// Simple destroy route to avoid JS for deletes in the table (uses form with method DELETE)
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
