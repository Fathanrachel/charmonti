<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomOrder;
use App\Models\Product;
use App\Models\Review;
use App\Models\Complaint;
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
            'courier'          => 'required|string|in:JNE,J&T,SiCepat',
        ]);

        $itemTotal = $product->price * $request->quantity;

        // Map shipping cost and estimated arrival
        $shippingCosts = [
            'J&T' => 10000,
            'JNE' => 12000,
            'SiCepat' => 8000,
        ];
        
        $shippingArrivals = [
            'J&T' => now()->addDays(3),
            'JNE' => now()->addDays(2),
            'SiCepat' => now()->addDays(5),
        ];

        $shippingCost = $shippingCosts[$request->courier] ?? 10000;
        $totalPrice = $itemTotal + $shippingCost;

        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_date'       => now(),
            'status'           => 'pending',
            'total_price'      => $totalPrice,
            'shipping_address' => $request->shipping_address,
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'price'      => $product->price,
        ]);

        // Automate creating the shipping record!
        \App\Models\Shipping::create([
            'order_id' => $order->id,
            'courier' => $request->courier,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => $shippingArrivals[$request->courier] ?? now()->addDays(3),
            'status' => 'pending',
        ]);

        return redirect()->route('order.success', $order->id);
    }

    public function customOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Login dulu untuk memesan.');
        }

        $charms = Product::where('category', 'charm')->get();

        return view('customer.custom-order', compact('charms'));
    }

    public function storeCustomOrder(Request $request)
    {
        $request->validate([
            'warna'              => 'required|in:silver,gold',
            'charms'             => 'required|array|min:1|max:15',
            'charms.*'           => 'exists:products,id',
            'request_note'       => 'nullable|string|max:500',
            'shipping_address'   => 'required|string',
            'courier'            => 'required|string|in:JNE,J&T,SiCepat',
        ]);

        $selectedCharms = Product::whereIn('id', $request->charms)->get();
        $itemTotal = $selectedCharms->sum('price');

        // Map shipping cost and estimated arrival
        $shippingCosts = [
            'J&T' => 10000,
            'JNE' => 12000,
            'SiCepat' => 8000,
        ];
        
        $shippingArrivals = [
            'J&T' => now()->addDays(3),
            'JNE' => now()->addDays(2),
            'SiCepat' => now()->addDays(5),
        ];

        $shippingCost = $shippingCosts[$request->courier] ?? 10000;
        $totalPrice = $itemTotal + $shippingCost;

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

        // Automate creating the shipping record!
        \App\Models\Shipping::create([
            'order_id' => $order->id,
            'courier' => $request->courier,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => $shippingArrivals[$request->courier] ?? now()->addDays(3),
            'status' => 'pending',
        ]);

        return redirect()->route('order.success', $order->id);
    }

    public function orderSuccess(Order $order)
    {
        return view('customer.order-success', compact('order'));
    }

    public function orders()
    {
        // 1. Otomatisasi 1x24 Jam: Batalkan pesanan pending yang berumur lebih dari 24 jam
        $expiredOrders = Order::where('status', 'pending')
            ->where('order_date', '<', now()->subDay())
            ->get();

        foreach ($expiredOrders as $expiredOrder) {
            $expiredOrder->update(['status' => 'batal']);
            if ($expiredOrder->payment) {
                $expiredOrder->payment->update(['payment_status' => 'failed']);
            }
        }

        // 2. Ambil semua pesanan milik user saat ini
        $orders = Order::where('user_id', Auth::id())
            ->with(['payment', 'shipping', 'orderItems.product'])
            ->orderBy('id', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
        // Pastikan hanya pemilik pesanan yang sah yang bisa membatalkan
        abort_if($order->user_id !== Auth::id(), 403);

        // Hanya boleh dibatalkan jika status pesanan pending dan status pembayaran belum lunas (pending)
        $payStatus = $order->payment?->payment_status ?? 'pending';
        if ($order->status === 'pending' && $payStatus === 'pending') {
            $order->update(['status' => 'batal']);
            if ($order->payment) {
                $order->payment->update(['payment_status' => 'failed']);
            }
            return redirect()->back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan ini sudah diproses atau dibayar, tidak dapat dibatalkan.');
    }

    public function createReview(Order $order)
    {
        // Pastikan kepemilikan pesanan
        abort_if($order->user_id !== Auth::id(), 403);
        // Hanya pesanan yang selesai yang bisa diulas
        abort_if($order->status !== 'selesai', 403);

        return view('customer.review', compact('order'));
    }

    public function storeReview(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'selesai', 403);

        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:1000',
        ]);

        foreach ($request->ratings as $productId => $rating) {
            // Pastikan produk tersebut ada di pesanan ini
            if ($order->orderItems()->where('product_id', $productId)->exists()) {
                Review::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                    ],
                    [
                        'rating' => $rating,
                        'comment' => $request->comments[$productId] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('customer.orders')->with('success', 'Terima kasih atas ulasan produk Anda! ❤️');
    }

    public function createComplaint(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        
        // Komplain hanya boleh untuk pesanan yang sudah dibayar/selesai
        $payStatus = $order->payment?->payment_status ?? 'pending';
        abort_if($payStatus !== 'paid' && $order->status !== 'selesai', 403);

        return view('customer.complaint', compact('order'));
    }

    public function storeComplaint(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        
        $payStatus = $order->payment?->payment_status ?? 'pending';
        abort_if($payStatus !== 'paid' && $order->status !== 'selesai', 403);

        $request->validate([
            'category' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Complaint::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('customer.orders')->with('success', 'Komplain Anda berhasil terkirim dan akan segera ditinjau oleh Admin. ⚠️');
    }
}
