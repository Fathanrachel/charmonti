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

// Keranjang Belanja (Shopping Cart)
use App\Http\Controllers\CartController;
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/add-custom', [CartController::class, 'addCustom'])->name('cart.add-custom');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/checkout-gabungan', [CartController::class, 'checkout'])->name('checkout.gabungan');
    Route::post('/checkout-gabungan', [CartController::class, 'storeCheckout'])->name('checkout.store');
    Route::get('/checkout/{product}', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{product}', [CustomerController::class, 'store'])->name('order.store');
    Route::get('/order/success/{order}', [CustomerController::class, 'orderSuccess'])->name('order.success');

    // Custom Order
    Route::get('/custom-order', [CustomerController::class, 'customOrder'])->name('custom.order');
    Route::post('/custom-order', [CustomerController::class, 'storeCustomOrder'])->name('custom.order.store');

    // Riwayat Pesanan
    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::post('/orders/{order}/cancel', [CustomerController::class, 'cancel'])->name('customer.order.cancel');
    Route::post('/orders/{order}/confirm-received', [CustomerController::class, 'confirmReceived'])->name('customer.order.confirm-received');

    // Ulasan (Review)
    Route::get('/orders/{order}/review', [CustomerController::class, 'createReview'])->name('customer.order.review');
    Route::post('/orders/{order}/review', [CustomerController::class, 'storeReview'])->name('customer.order.review.store');

    // Komplain (Complaint)
    Route::get('/orders/{order}/complaint', [CustomerController::class, 'createComplaint'])->name('customer.order.complaint');
    Route::post('/orders/{order}/complaint', [CustomerController::class, 'storeComplaint'])->name('customer.order.complaint.store');
    Route::post('/complaints/{complaint}/reply', [CustomerController::class, 'replyComplaint'])->name('customer.complaint.reply');

    // Profile
    Route::get('/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::post('/profile/ajax-update', [CustomerController::class, 'ajaxUpdateProfile'])->name('customer.profile.ajax-update');
    Route::get('/api/cities/{province_id}', [CustomerController::class, 'getCities'])->name('api.cities');
    Route::get('/api/expedition-costs/{city_id}', [CustomerController::class, 'getExpeditionCosts'])->name('api.expedition.costs');

    // Payment routes
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('/payment/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{order}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
});

// PDF Download Routes (Khusus Owner)
Route::middleware('auth:owner')->group(function () {
    Route::get('/owner/reports/sales/pdf', [\App\Http\Controllers\ReportPdfController::class, 'downloadSalesReport'])->name('owner.reports.sales.pdf');
    Route::get('/owner/reports/financial/pdf', [\App\Http\Controllers\ReportPdfController::class, 'downloadFinancialReport'])->name('owner.reports.financial.pdf');
});

Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');