<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CustomerController extends Controller
{
    // Halaman utama / daftar produk
    public function index()
    {
        $products = Product::all();
        return view('customer.products.index', compact('products'));
    }

    // Halaman detail produk
    public function show(Product $product)
    {
        return view('customer.products.show', compact('product'));
    }
}