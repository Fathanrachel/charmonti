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

    private function isProfileComplete($user): bool
    {
        $profile = $user?->profile;
        if (!$profile) {
            return false;
        }

        $name = !empty($profile->name) ? $profile->name : $user->name;
        $phone = $profile->phone;
        $address = !empty($profile->address_line) ? $profile->address_line : $profile->address;
        $cityId = $profile->city_id;
        $postalCode = $profile->postal_code;

        return !empty($name) && !empty($phone) && !empty($address) && !empty($cityId) && !empty($postalCode);
    }

    public function checkout(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk memesan.');
        }

        $user = Auth::user();
        if (!$this->isProfileComplete($user)) {
            return redirect()->route('customer.profile')
                ->with('error', 'Silakan lengkapi data diri (Nama, No. Telepon, Provinsi, Kota, & Alamat Lengkap) Anda terlebih dahulu sebelum melakukan checkout pesanan. ⚠️');
        }

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

        // 1. Ambil Strap Silver & Gold secara fleksibel (mengabaikan huruf besar/kecil & variasi nama 'tali' / 'strap')
        $strapSilver = Bahan::where(function ($q) {
            $q->where('nama_bahan', 'like', '%silver%')
              ->orWhere('nama_bahan', 'like', '%Silver%');
        })->where(function ($q) {
            $q->where('nama_bahan', 'like', '%strap%')
              ->orWhere('nama_bahan', 'like', '%Strap%')
              ->orWhere('nama_bahan', 'like', '%tali%')
              ->orWhere('nama_bahan', 'like', '%Tali%');
        })->first();

        $strapGold = Bahan::where(function ($q) {
            $q->where('nama_bahan', 'like', '%gold%')
              ->orWhere('nama_bahan', 'like', '%Gold%');
        })->where(function ($q) {
            $q->where('nama_bahan', 'like', '%strap%')
              ->orWhere('nama_bahan', 'like', '%Strap%')
              ->orWhere('nama_bahan', 'like', '%tali%')
              ->orWhere('nama_bahan', 'like', '%Tali%');
        })->first();

        // 2. Ambil Charms: Semua bahan yang BUKAN mengandung kata 'strap' atau 'tali'
        $charms = Bahan::where('nama_bahan', 'not like', '%strap%')
            ->where('nama_bahan', 'not like', '%Strap%')
            ->where('nama_bahan', 'not like', '%tali%')
            ->where('nama_bahan', 'not like', '%Tali%')
            ->get();

        return view('customer.custom-order', compact('charms', 'strapSilver', 'strapGold'));
    }

    public function storeCustomOrder(Request $request)
    {
        $rules = [
            'warna'              => 'required|in:silver,gold,tanpa_strap',
            'charms'             => 'required|array|min:1',
            'charms.*'           => 'exists:bahan,id',
            'request_note'       => 'nullable|string|max:500',
            'shipping_address'   => 'required|string',
            'courier'            => 'required|string',
        ];
        if ($request->warna !== 'tanpa_strap') {
            $rules['charms'] = 'required|array|min:1|max:15';
        }
        $request->validate($rules);

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

        // Validasi stok tali gelang (fleksibel)
        $strapBahan = Bahan::where(function ($q) use ($request) {
            $q->where('nama_bahan', 'like', '%' . strtolower($request->warna) . '%')
              ->orWhere('nama_bahan', 'like', '%' . ucfirst($request->warna) . '%');
        })->where(function ($q) {
            $q->where('nama_bahan', 'like', '%strap%')
              ->orWhere('nama_bahan', 'like', '%Strap%')
              ->orWhere('nama_bahan', 'like', '%tali%')
              ->orWhere('nama_bahan', 'like', '%Tali%');
        })->first();

        if ($strapBahan && $strapBahan->dynamic_stock <= 0) {
            return redirect()->back()->with('error', 'Stok tali gelang warna ' . $request->warna . ' sedang habis.')->withInput();
        }

        $selectedCharms = Bahan::whereIn('id', $request->charms)->get();
        // Include base price for strap (Rp 20.000)
        $itemTotal = 20000 + $selectedCharms->sum('price');

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

        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($profile, $totalPrice, $itemTotal, $request, $selectedCharms, $expedition, $shippingCost, $estimatedDays) {
            $order = Order::create([
                'profile_id'       => $profile?->id,
                'order_date'       => now(),
                'status'           => 'pending',
                'total_price'      => $totalPrice,
                'payment_method'   => 'midtrans',
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

            return $order;
        });

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

        // Hanya bisa dikonfirmasi jika status pengiriman/pesanan sudah benar-benar 'dikirim'
        if ($order->shipping?->status === 'dikirim' || $order->status === 'dikirim') {
            $order->update(['status' => 'selesai']);

            if ($order->shipping) {
                $order->shipping->update(['status' => 'sampai']);
            }

            if ($order->customBahanOrder && $order->customBahanOrder->status === 'pending') {
                $order->customBahanOrder->update(['status' => 'disetujui']);
            }

            return redirect()->back()->with('success', 'Pesanan telah berhasil dikonfirmasi diterima. Terima kasih sudah berbelanja di CharmOnTi! 💖');
        }

        return redirect()->back()->with('error', 'Pesanan belum dikirim oleh toko, tidak dapat dikonfirmasi diterima.');
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

        // Gather all products to review (regular orderItems products + each custom bracelet)
        $itemsToReview = [];
        foreach ($order->orderItems as $item) {
            if ($item->product?->product_name === 'Gelang Custom') {
                continue;
            }
            $itemsToReview[] = [
                'key' => 'reg_' . $item->product->id,
                'id' => $item->product->id,
                'name' => $item->product->product_name,
                'category' => ucfirst($item->product->category ?? 'Produk'),
                'image' => $item->product->image,
            ];
        }

        // Loop through ALL custom bracelets for this order
        foreach ($order->customBahanOrders as $cIndex => $customOrder) {
            $isNoStrap = ($customOrder->warna === 'tanpa_strap');
            $strapColor = $isNoStrap ? 'Tanpa Strap' : ucfirst(trim($customOrder->warna));
            $color = strtolower(trim($customOrder->warna));
            $strapBahan = !$isNoStrap ? \App\Models\Bahan::whereRaw('LOWER(nama_bahan) LIKE ?', ['%' . $color . '%'])->first() : null;
            $firstCharm = $customOrder->customBahanOrderItems->first();
            $displayImage = $strapBahan?->image ?? $firstCharm?->bahan?->image;

            $customTitle = 'Gelang Custom';
            if ($order->customBahanOrders->count() > 1) {
                $customTitle .= ' #' . ($cIndex + 1);
            }
            $customTitle .= ' (' . $strapColor . ')';

            $itemsToReview[] = [
                'key' => 'custom_' . $customOrder->id,
                'id' => $customProductDummy->id,
                'name' => $customTitle,
                'category' => 'Gelang Custom',
                'image' => $displayImage,
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

        foreach ($request->ratings as $key => $rating) {
            $productId = null;
            if (str_starts_with($key, 'reg_')) {
                $productId = (int) str_replace('reg_', '', $key);
                $exists = $order->orderItems()->where('product_id', $productId)->exists();
                if (!$exists) continue;
            } elseif (str_starts_with($key, 'custom_')) {
                $customOrderId = (int) str_replace('custom_', '', $key);
                $exists = $order->customBahanOrders()->where('id', $customOrderId)->exists();
                if (!$exists || !$customProductDummy) continue;
                $productId = $customProductDummy->id;
            } else {
                $productId = (int) $key;
                $exists = $order->orderItems()->where('product_id', $productId)->exists() || ($customProductDummy && $productId == $customProductDummy->id);
                if (!$exists) continue;
            }

            if ($productId) {
                Review::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'product_id' => $productId,
                    ],
                    [
                        'rating' => $rating,
                        'comment' => $request->comments[$key] ?? null,
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

        $categories = \App\Models\ComplaintCategory::all();

        return view('customer.complaint', compact('order', 'categories'));
    }

    public function storeComplaint(Request $request, Order $order)
    {
        abort_if($order->profile?->users_id !== Auth::id(), 403);
        
        $payStatus = $order->payment?->payment_status ?? 'pending';
        abort_if($payStatus !== 'paid' && $order->status !== 'selesai', 403);

        $request->validate([
            'complaint_category_id' => 'nullable|exists:complaint_categories,id',
            'category' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $categoryName = $request->category ?? 'Komplain';
        if ($request->complaint_category_id) {
            $cat = \App\Models\ComplaintCategory::find($request->complaint_category_id);
            if ($cat) {
                $categoryName = $cat->name;
            }
        }

        Complaint::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'complaint_category_id' => $request->complaint_category_id,
            'category' => $categoryName,
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

    public function ajaxUpdateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'address'     => 'required|string|max:1000',
            'province_id' => 'required|exists:provinces,id',
            'city_id'     => 'required|exists:cities,id',
            'postal_code' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $profile = $user->profile ?? new \App\Models\Profile(['users_id' => $user->id]);
        $profile->name = $request->name;
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        $profile->address_line = $request->address;
        $profile->province_id = $request->province_id;
        $profile->city_id = $request->city_id;
        if ($request->filled('postal_code')) {
            $profile->postal_code = $request->postal_code;
        }
        $profile->save();

        // Juga perbarui name di tabel users agar konsisten
        $user->update(['name' => $request->name]);

        $city = \App\Models\City::find($request->city_id);
        $province = \App\Models\Province::find($request->province_id);
        $cityName = $city?->city ?? '';
        $provinceName = $province?->province ?? '';

        return response()->json([
            'success'       => true,
            'message'       => 'Data diri dan alamat pengiriman berhasil diperbarui! ✨',
            'name'          => $request->name,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'province_id'   => $request->province_id,
            'city_id'       => $request->city_id,
            'full_location' => $request->address . ', ' . $cityName . ', ' . $provinceName,
        ]);
    }
}
