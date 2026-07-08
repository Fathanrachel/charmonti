<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Bahan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomBahanOrder;
use App\Models\CustomBahanOrderItem;
use App\Models\Expedition;
use App\Models\Shipping;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private function checkProfileComplete()
    {
        if (!Auth::check()) {
            return true; // Biarkan middleware auth yang mengurus redirect login
        }

        $profile = Auth::user()->profile;

        if (!$profile || 
            empty($profile->name) || 
            empty($profile->phone) || 
            empty($profile->city_id) || 
            empty($profile->address_line) || 
            empty($profile->postal_code)
        ) {
            return false;
        }

        return true;
    }

    public function index()
    {
        if (!$this->checkProfileComplete()) {
            return redirect()->route('customer.profile')
                ->with('error', 'Silakan lengkapi data profil (Nama, No Telepon, Provinsi, Kota, Alamat Lengkap, & Kode Pos) Anda terlebih dahulu sebelum memesan barang. ⚠️');
        }

        $cart = Session::get('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);
        $qty = $request->input('quantity', 1);

        $cartId = 'regular_' . $product->id;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['quantity'] += $qty;
        } else {
            $cart[$cartId] = [
                'type' => 'regular',
                'id' => $product->id,
                'name' => $product->product_name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $qty,
            ];
        }

        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function addCustom(Request $request)
    {
        $request->validate([
            'warna' => 'required|in:silver,gold',
            'charms' => 'nullable|array',
            'request_note' => 'nullable|string|max:500',
        ]);

        $charmsInput = $request->charms ?? [];

        // Filter out items with 0 quantity
        $selectedCharmsInput = array_filter($charmsInput, fn($qty) => $qty > 0);

        // Validate aggregate quantity max 15
        $totalQty = array_sum($selectedCharmsInput);
        if ($totalQty > 15) {
            return redirect()->back()->withErrors(['charms' => 'Total manik/charm yang Anda pilih melebihi batas maksimal (15 manik).'])->withInput();
        }

        // Validate that all keys exist in bahan table if charms are selected
        $charmsModels = collect();
        if (!empty($selectedCharmsInput)) {
            $bahanIds = array_keys($selectedCharmsInput);
            $charmsModels = Bahan::whereIn('id', $bahanIds)->get()->keyBy('id');

            if (count($charmsModels) !== count($bahanIds)) {
                return redirect()->back()->withErrors(['charms' => 'Terdapat charm pilihan yang tidak valid.'])->withInput();
            }
        }

        // Base price of strap = Rp 20.000
        $charmsPrice = 20000;
        $charmsDetails = [];
        $charmsFlattenedIds = [];

        foreach ($selectedCharmsInput as $id => $qty) {
            $model = $charmsModels[$id];
            $charmsPrice += $model->price * $qty;
            $charmsDetails[] = [
                'id' => $model->id,
                'name' => $model->nama_bahan,
                'price' => $model->price,
                'quantity' => $qty,
                'image' => $model->image
            ];

            // Flatten charms array for compatibility
            for ($i = 0; $i < $qty; $i++) {
                $charmsFlattenedIds[] = $model->id;
            }
        }

        $cart = Session::get('cart', []);
        
        // Generate a unique ID for this custom design
        $cartId = 'custom_' . uniqid();

        $cart[$cartId] = [
            'type' => 'custom',
            'warna' => $request->warna,
            'charms' => $charmsFlattenedIds, // Keep flattened array of IDs for compatibility
            'charms_quantities' => $selectedCharmsInput, // Key-value array of [bahan_id => qty]
            'charms_details' => $charmsDetails,
            'name' => 'Gelang Custom (' . ucfirst($request->warna) . ')',
            'price' => $charmsPrice,
            'request_note' => $request->request_note,
            'quantity' => 1,
        ];

        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Gelang custom berhasil dimasukkan ke keranjang!');
    }

    public function update(Request $request, string $id)
    {
        $cart = Session::get('cart', []);
        $quantity = intval($request->input('quantity', 1));

        if (isset($cart[$id]) && $quantity > 0) {
            $cart[$id]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function remove(string $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Barang dihapus dari keranjang.');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk checkout.');
        }

        if (!$this->checkProfileComplete()) {
            return redirect()->route('customer.profile')
                ->with('error', 'Silakan lengkapi data profil (Nama, No Telepon, Provinsi, Kota, Alamat Lengkap, & Kode Pos) Anda terlebih dahulu sebelum memesan barang. ⚠️');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        return view('customer.checkout_gabungan', compact('cart'));
    }

    public function storeCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$this->checkProfileComplete()) {
            return redirect()->route('customer.profile')
                ->with('error', 'Silakan lengkapi data profil Anda terlebih dahulu sebelum memproses pesanan.');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'courier' => 'required|string|in:JNE,J&T,SiCepat',
        ]);

        // Calculate item total
        $itemTotal = 0;
        foreach ($cart as $item) {
            $itemTotal += $item['price'] * $item['quantity'];
        }

        // Map shipping cost
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

        $profile = Auth::user()->profile;
        if ($profile) {
            $profile->update([
                'address_line' => $request->shipping_address,
            ]);
        }

        // 1. Create single Order
        $order = Order::create([
            'profile_id' => $profile?->id,
            'order_date' => now(),
            'status' => 'pending',
            'total_price' => $totalPrice,
            'payment_method' => 'midtrans',
        ]);

        // 2. Populate items
        foreach ($cart as $item) {
            if ($item['type'] === 'regular') {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            } elseif ($item['type'] === 'custom') {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $customBahanOrder = CustomBahanOrder::create([
                        'order_id' => $order->id,
                        'warna' => $item['warna'],
                        'request_note' => $item['request_note'],
                        'status' => 'pending',
                    ]);

                    foreach ($item['charms'] as $charmId) {
                        CustomBahanOrderItem::create([
                            'custom_order_id' => $customBahanOrder->id,
                            'bahan_id' => $charmId,
                            'qty' => 1,
                        ]);
                    }
                }
            }
        }

        // 3. Create Shipping
        $expedition = Expedition::firstOrCreate(['name_expedition' => $request->courier]);
        Shipping::create([
            'order_id' => $order->id,
            'expedition_id' => $expedition->id,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => $shippingArrivals[$request->courier] ?? now()->addDays(3),
            'status' => 'pending',
        ]);

        // Clear cart
        Session::forget('cart');

        return redirect()->route('order.success', $order->id);
    }
}
