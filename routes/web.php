<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

// Halaman utama - bisa diakses semua orang
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/produk/{product}', [CustomerController::class, 'show'])->name('produk.show');