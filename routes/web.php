<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\LoyaltyPointController;
use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('order-items', OrderItemController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('addresses', AddressController::class);
    Route::resource('loyalty-points', LoyaltyPointController::class);
    Route::resource('discounts', DiscountController::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
