<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomBahanOrder;
use App\Models\CustomBahanOrderItem;
use App\Models\Bahan;
use App\Models\Product;
use App\Models\Review;
use App\Models\Complaint;
use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $products = Product::where('product_name', '!=', 'Gelang Custom')->get();
        $reviews = Review::with([
            'user.profile', 
            'product', 
            'order.orderItems.product', 
            'order.customBahanOrder'
        ])->latest()->take(6)->get();
        return view('customer.products.index', compact('products', 'reviews'));
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

        $user = Auth::user();
        $userCityId = $user->profile?->city_id;

        $expeditions = Expedition::all()->map(function ($exp) use ($userCityId) {
            $cityExp = null;
            if ($userCityId) {
                $cityExp = \App\Models\CityExpedition::where('city_id', $userCityId)
                    ->where('expedition_id', $exp->id)
                    ->first();
            }
            $exp->shipping_cost = $cityExp ? $cityExp->shipping_cost : $exp->shipping_cost;
            $exp->estimated_days = $cityExp ? $cityExp->estimated_days : $exp->estimated_days;
            return $exp;
        });

        return view('customer.checkout', compact('product', 'expeditions'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity'         => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'courier'          => 'required|string',
        ]);

        // Validasi stok produk reguler
        if ($product->product_name !== 'Gelang Custom') {
            if ($request->quantity > $product->dynamic_stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Tersedia: ' . $product->dynamic_stock . ' pcs.')->withInput();
            }
        }

        $itemTotal = $product->price * $request->quantity;

        $profile = Auth::user()->profile;
        $userCityId = $profile?->city_id;

        $expedition = Expedition::where('name_expedition', $request->courier)->first();
        if (!$expedition) {
            $expedition = Expedition::firstOrCreate(['name_expedition' => $request->courier]);
        }

        $cityExp = null;
        if ($userCityId) {
            $cityExp = \App\Models\CityExpedition::where('city_id', $userCityId)
                ->where('expedition_id', $expedition->id)
                ->first();
        }

        $shippingCost = $cityExp ? $cityExp->shipping_cost : $expedition->shipping_cost;
        $estimatedDays = $cityExp ? $cityExp->estimated_days : $expedition->estimated_days;
        $totalPrice = $itemTotal + $shippingCost;

        $profile = Auth::user()->profile;
        if ($profile) {
            $profile->update([
                'address_line' => $request->shipping_address,
            ]);
        }

        $order = Order::create([
            'profile_id'       => $profile?->id,
            'order_date'       => now(),
            'status'           => 'pending',
            'total_price'      => $totalPrice,
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'qty'        => $request->quantity,
            'price'      => $product->price,
        ]);

        \App\Models\Shipping::create([
            'order_id' => $order->id,
            'expedition_id' => $expedition->id,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => now()->addDays($estimatedDays),
            'status' => 'pending',
        ]);

        return redirect()->route('order.success', $order->id);
    }

    public function customOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Login dulu untuk memesan.');
        }

        $charms = Bahan::where('nama_bahan', 'not like', 'Tali Gelang%')->get();
        $strapSilver = Bahan::where('nama_bahan', 'Tali Gelang Silver')->first();
        $strapGold = Bahan::where('nama_bahan', 'Tali Gelang Gold')->first();

        return view('customer.custom-order', compact('charms', 'strapSilver', 'strapGold'));
    }

    public function storeCustomOrder(Request $request)
    {
        $request->validate([
            'warna'              => 'required|in:silver,gold',
            'charms'             => 'required|array|min:1|max:15',
            'charms.*'           => 'exists:bahan,id',
            'request_note'       => 'nullable|string|max:500',
            'shipping_address'   => 'required|string',
            'courier'            => 'required|string',
        ]);

        // Validasi stok manik-manik kustom
        $charmsInput = $request->charms ?? [];
        $charmsCounts = array_count_values($charmsInput);
        foreach ($charmsCounts as $charmId => $qtyRequired) {
            $charm = Bahan::find($charmId);
            if ($charm) {
                if ($qtyRequired > $charm->dynamic_stock) {
                    return redirect()->back()->with('error', 'Stok manik-manik "' . $charm->nama_bahan . '" tidak mencukupi. Tersisa: ' . $charm->dynamic_stock . ' pcs.')->withInput();
                }
            }
        }

        // Validasi stok tali gelang
        $strapBahan = Bahan::where('nama_bahan', 'Tali Gelang ' . ucfirst($request->warna))->first();
        if ($strapBahan && $strapBahan->dynamic_stock <= 0) {
            return redirect()->back()->with('error', 'Stok tali gelang warna ' . $request->warna . ' sedang habis.')->withInput();
        }

        $selectedCharms = Bahan::whereIn('id', $request->charms)->get();
        $itemTotal = $selectedCharms->sum('price');

        $profile = Auth::user()->profile;
        $userCityId = $profile?->city_id;

        $expedition = Expedition::where('name_expedition', $request->courier)->first();
        if (!$expedition) {
            $expedition = Expedition::firstOrCreate(['name_expedition' => $request->courier]);
        }

        $cityExp = null;
        if ($userCityId) {
            $cityExp = \App\Models\CityExpedition::where('city_id', $userCityId)
                ->where('expedition_id', $expedition->id)
                ->first();
        }

        $shippingCost = $cityExp ? $cityExp->shipping_cost : $expedition->shipping_cost;
        $estimatedDays = $cityExp ? $cityExp->estimated_days : $expedition->estimated_days;
        $totalPrice = $itemTotal + $shippingCost;

        $profile = Auth::user()->profile;
        if ($profile) {
            $profile->update([
                'address_line' => $request->shipping_address,
            ]);
        }

        $order = Order::create([
            'profile_id'       => $profile?->id,
            'order_date'       => now(),
            'status'           => 'pending',
            'total_price'      => $totalPrice,
        ]);

        $customProduct = \App\Models\Product::firstOrCreate(
            ['product_name' => 'Gelang Custom'],
            [
                'description' => 'Gelang Custom Rangkaian Pelanggan',
                'price'       => 20000,
                'category'    => 'gelang_jadi',
            ]
        );

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $customProduct->id,
            'qty'        => 1,
            'price'      => $itemTotal,
        ]);

        // Buat CustomBahanOrder
        $customBahanOrder = CustomBahanOrder::create([
            'order_id'           => $order->id,
            'warna'              => $request->warna,
            'request_note'       => $request->request_note,
            'status'             => 'pending',
        ]);

        // Buat CustomBahanOrderItem untuk setiap charm
        foreach ($selectedCharms as $charm) {
            CustomBahanOrderItem::create([
                'custom_order_id' => $customBahanOrder->id,
                'bahan_id'        => $charm->id,
                'qty'             => 1,
            ]);
        }

        // Automate creating the shipping record!
        \App\Models\Shipping::create([
            'order_id' => $order->id,
            'expedition_id' => $expedition->id,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => now()->addDays($estimatedDays),
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

        // 2. Ambil semua pesanan milik profile user saat ini
        $profile = Auth::user()->profile;
        $orders = Order::where('profile_id', $profile?->id)
            ->with(['payment', 'shipping', 'orderItems.product'])
            ->orderBy('order_date', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    public function cancel(Order $order)
    {
        // Pastikan hanya pemilik pesanan yang sah yang bisa membatalkan
        abort_if($order->profile?->users_id !== Auth::id(), 403);

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

    public function confirmReceived(Order $order)
    {
        abort_if($order->profile?->users_id !== Auth::id(), 403);

        // Hanya bisa dikonfirmasi jika status pengiriman sudah 'dikirim' atau status pesanan masih diproses/pending
        if ($order->shipping?->status === 'dikirim' || in_array($order->status, ['diproses', 'pending'])) {
            $order->update(['status' => 'selesai']);

            if ($order->shipping) {
                $order->shipping->update(['status' => 'sampai']);
            }

            if ($order->customBahanOrder && $order->customBahanOrder->status === 'pending') {
                $order->customBahanOrder->update(['status' => 'disetujui']);
            }

            return redirect()->back()->with('success', 'Pesanan telah berhasil dikonfirmasi diterima. Terima kasih sudah berbelanja di CharmOnTi! 💖');
        }

        return redirect()->back()->with('error', 'Pesanan belum dapat dikonfirmasi diterima.');
    }

    public function createReview(Order $order)
    {
        abort_if($order->profile?->users_id !== Auth::id(), 403);
        abort_if($order->status !== 'selesai', 403);

        // Ensure the dummy "Gelang Custom" product exists in catalog for review anchor
        $customProductDummy = Product::firstOrCreate(
            ['product_name' => 'Gelang Custom'],
            [
                'description' => 'Produk ulasan gelang custom rancangan pengguna.',
                'price' => 0.00,
                'category' => 'gelang_jadi',
            ]
        );

        // Gather all products to review (regular orderItems products + custom items)
        $itemsToReview = [];
        foreach ($order->orderItems as $item) {
            if ($item->product?->product_name === 'Gelang Custom') {
                continue;
            }
            $itemsToReview[] = [
                'id' => $item->product->id,
                'name' => $item->product->product_name,
                'category' => $item->product->category,
                'image' => $item->product->image,
            ];
        }

        // If this order has custom bracelets, add a single review slot for "Gelang Custom"
        if ($order->customBahanOrder()->exists()) {
            $strapColor = ucfirst(trim($order->customBahanOrder->warna ?? ''));
            $strapBahan = \App\Models\Bahan::where('nama_bahan', 'Tali Gelang ' . $strapColor)->first();

            $itemsToReview[] = [
                'id' => $customProductDummy->id,
                'name' => 'Gelang Custom (' . $strapColor . ')',
                'category' => 'Gelang Custom',
                'image' => $strapBahan?->image,
            ];
        }

        return view('customer.review', compact('order', 'itemsToReview'));
    }

    public function storeReview(Request $request, Order $order)
    {
        abort_if($order->profile?->users_id !== Auth::id(), 403);
        abort_if($order->status !== 'selesai', 403);

        $request->validate([
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:1000',
        ]);

        $customProductDummy = Product::where('product_name', 'Gelang Custom')->first();

        foreach ($request->ratings as $productId => $rating) {
            // Validate that the product was indeed in the order (or it's the dummy custom product and the order has custom bracelet)
            $isRegularItem = $order->orderItems()->where('product_id', $productId)->exists();
            $isCustomItem = ($customProductDummy && $productId == $customProductDummy->id && $order->customBahanOrder()->exists());

            if ($isRegularItem || $isCustomItem) {
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
        abort_if($order->profile?->users_id !== Auth::id(), 403);
        
        // Komplain hanya boleh untuk pesanan yang sudah dibayar/selesai
        $payStatus = $order->payment?->payment_status ?? 'pending';
        abort_if($payStatus !== 'paid' && $order->status !== 'selesai', 403);

        return view('customer.complaint', compact('order'));
    }

    public function storeComplaint(Request $request, Order $order)
    {
        abort_if($order->profile?->users_id !== Auth::id(), 403);
        
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

    public function profile()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $provinces = \App\Models\Province::orderBy('province')->get();
        
        // Dapatkan kota-kota sesuai provinsi yang saat ini dipilih (jika ada)
        $cities = collect();
        if ($profile?->city_id) {
            $currentCity = \App\Models\City::find($profile->city_id);
            if ($currentCity) {
                $cities = \App\Models\City::where('province_id', $currentCity->province_id)->orderBy('city')->get();
            }
        }

        $totalOrdersCount = \App\Models\Order::where('profile_id', $profile->id)->count();
        $recentOrders = \App\Models\Order::where('profile_id', $profile->id)
            ->with(['orderItems.product', 'payment', 'shipping'])
            ->latest('order_date')
            ->take(3)
            ->get();

        $passwordChangedAtString = \Illuminate\Support\Facades\Cache::get('user_password_changed_' . $user->id);
        $passwordChangedAt = $passwordChangedAtString ? \Carbon\Carbon::parse($passwordChangedAtString) : null;

        return view('customer.profile', compact('user', 'profile', 'provinces', 'cities', 'totalOrdersCount', 'recentOrders', 'passwordChangedAt'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $rules = [
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'province_id'  => 'required|exists:provinces,id',
            'city_id'      => 'required|exists:cities,id',
            'address_line' => 'required|string|max:500',
            'postal_code'  => 'required|string|max:10',
        ];

        if ($request->filled('password')) {
            $lastChangedString = \Illuminate\Support\Facades\Cache::get('user_password_changed_' . $user->id);
            if ($lastChangedString) {
                $lastChangedCarbon = \Carbon\Carbon::parse($lastChangedString);
                $nextAvailableDate = $lastChangedCarbon->copy()->addDays(7)->translatedFormat('d F Y, H:i');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['password' => "Perubahan kata sandi hanya dapat dilakukan 1 kali dalam 7 hari. Anda dapat mengubah kata sandi kembali pada {$nextAvailableDate} WIB."]);
            }

            $rules['current_password'] = 'required|current_password';
            $rules['password']         = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update profile
        $profile->update([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'city_id'      => $request->city_id,
            'address_line' => $request->address_line,
            'postal_code'  => $request->postal_code,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);
            \Illuminate\Support\Facades\Cache::put('user_password_changed_' . $user->id, now()->toDateTimeString(), now()->addDays(7));
        }

        return redirect()->route('customer.profile')->with('success', 'Profil Anda berhasil diperbarui! ✨');
    }

    public function getCities($provinceId)
    {
        $cities = \App\Models\City::where('province_id', $provinceId)->orderBy('city')->get(['id', 'city']);
        return response()->json($cities);
    }

    public function getExpeditionCosts($cityId)
    {
        $expeditions = Expedition::all()->map(function ($exp) use ($cityId) {
            $cityExp = \App\Models\CityExpedition::where('city_id', $cityId)
                ->where('expedition_id', $exp->id)
                ->first();

            return [
                'id'              => $exp->id,
                'name_expedition' => $exp->name_expedition,
                'shipping_cost'   => $cityExp ? (int)$cityExp->shipping_cost : (int)$exp->shipping_cost,
                'estimated_days'  => $cityExp ? (int)$cityExp->estimated_days : (int)$exp->estimated_days,
            ];
        });

        return response()->json($expeditions);
    }

    public function replyComplaint(Request $request, Complaint $complaint)
    {
        abort_if($complaint->user_id !== Auth::id(), 403);
        abort_if($complaint->status === 'selesai', 403);

        $request->validate([
            'reply_message' => 'required|string|max:1000',
        ]);

        $formattedTime = now()->translatedFormat('d M H:i');
        $senderName = Auth::user()->profile?->name ?? 'Pelanggan';

        // Append customer reply to the main conversation thread text
        $updatedMessage = $complaint->message . "\n\n[" . $formattedTime . " - " . $senderName . "]: " . $request->reply_message;

        $complaint->update([
            'message' => $updatedMessage
        ]);

        return redirect()->back()->with('success', 'Balasan Anda berhasil dikirim! 💬');
    }
}
