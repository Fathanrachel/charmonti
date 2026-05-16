<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;

// Halaman publik
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/produk/{product}', [CustomerController::class, 'show'])->name('produk.show');

// Auth customer
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/checkout/{product}', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{product}', [CustomerController::class, 'store'])->name('order.store');
    Route::get('/order/success/{order}', [CustomerController::class, 'orderSuccess'])->name('order.success');

    // Custom Order
    Route::get('/custom-order', [CustomerController::class, 'customOrder'])->name('custom.order');
    Route::post('/custom-order', [CustomerController::class, 'storeCustomOrder'])->name('custom.order.store');
});