<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('customer.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('customer.products.show', compact('product'));
    }

    public function checkout(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Login dulu untuk memesan.');
        }
        return view('customer.checkout', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity'         => 'required|integer|min:1',
            'shipping_address' => 'required|string',
        ]);

        $total = $product->price * $request->quantity;

        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_date'       => now(),
            'status'           => 'pending',
            'total_price'      => $total,
            'shipping_address' => $request->shipping_address,
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'price'      => $product->price,
        ]);

        return redirect()->route('order.success', $order->id);
    }

    public function customOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Login dulu untuk memesan.');
        }

        // Ambil semua charm yang tersedia (kategori gelang_custom)
        $charms = Product::where('category', 'gelang_custom')->get();

        return view('customer.custom-order', compact('charms'));
    }

    public function storeCustomOrder(Request $request)
    {
        $request->validate([
            'warna'              => 'required|in:silver,gold',
            'charms'             => 'required|array|min:1|max:15',
            'charms.*'           => 'exists:products,id',
            'request_note'       => 'nullable|string|max:500',
        ]);

        // Hitung total harga dari charm yang dipilih
        $selectedCharms = Product::whereIn('id', $request->charms)->get();
        $totalPrice = $selectedCharms->sum('price');

        // Buat Order
        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_date'       => now(),
            'status'           => 'pending',
            'total_price'      => $totalPrice,
            'shipping_address' => $request->shipping_address,
        ]);

        // Buat OrderItem untuk setiap charm
        foreach ($selectedCharms as $charm) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $charm->id,
                'quantity'   => 1,
                'price'      => $charm->price,
            ]);
        }

        // Buat CustomOrder
        \App\Models\CustomOrder::create([
            'order_id'           => $order->id,
            'warna'              => $request->warna,
            'request_note'       => $request->request_note,
            'tambahan_aksesoris' => implode(', ', $selectedCharms->pluck('name')->toArray()),
            'status'             => 'pending',
        ]);

        return redirect()->route('order.success', $order->id);
    }

    public function orderSuccess(Order $order)
    {
        return view('customer.order-success', compact('order'));
    }
}