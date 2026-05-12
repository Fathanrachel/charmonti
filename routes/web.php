<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'));
});

// Owner routes
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', fn() => view('owner.dashboard'));
});

// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', fn() => view('customer.dashboard'));
});
