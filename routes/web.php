<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\PaymentController;
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

    // Riwayat Pesanan
    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::post('/orders/{order}/cancel', [CustomerController::class, 'cancel'])->name('customer.order.cancel');

    // Ulasan (Review)
    Route::get('/orders/{order}/review', [CustomerController::class, 'createReview'])->name('customer.order.review');
    Route::post('/orders/{order}/review', [CustomerController::class, 'storeReview'])->name('customer.order.review.store');

    // Komplain (Complaint)
    Route::get('/orders/{order}/complaint', [CustomerController::class, 'createComplaint'])->name('customer.order.complaint');
    Route::post('/orders/{order}/complaint', [CustomerController::class, 'storeComplaint'])->name('customer.order.complaint.store');

    // Payment routes
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{order}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
});

Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');