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

        // Validasi stok produk reguler
        if ($product->product_name !== 'Gelang Custom') {
            $currentQtyInCart = isset($cart[$cartId]) ? $cart[$cartId]['quantity'] : 0;
            $requestedQty = $currentQtyInCart + $qty;
            if ($requestedQty > $product->dynamic_stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Tersedia: ' . $product->dynamic_stock . ' pcs.');
            }
        }

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
            'warna'        => 'required|in:silver,gold',
            'charms'       => 'nullable|array',
            'charm_notes'  => 'nullable|array',
            'charm_notes.*'=> 'nullable|string|max:200',
            'request_note' => 'nullable|string|max:500',
        ]);

        $charmsInput = $request->charms ?? [];
        $charmNotes  = $request->charm_notes ?? [];

        // Filter out items with 0 quantity
        $selectedCharmsInput = array_filter($charmsInput, fn($qty) => $qty > 0);

        // Validasi stok tali gelang
        $strapBahan = Bahan::where('nama_bahan', 'Tali Gelang ' . ucfirst($request->warna))->first();
        if ($strapBahan && $strapBahan->dynamic_stock <= 0) {
            return redirect()->back()->withErrors(['warna' => 'Stok tali gelang warna ' . $request->warna . ' sedang habis.'])->withInput();
        }

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

            // Validasi stok manik-manik (charms)
            foreach ($selectedCharmsInput as $id => $qty) {
                $model = $charmsModels[$id];
                if ($qty > $model->dynamic_stock) {
                    return redirect()->back()->withErrors(['charms' => 'Stok untuk manik "' . $model->nama_bahan . '" tidak mencukupi. Tersisa: ' . $model->dynamic_stock . ' pcs.'])->withInput();
                }
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
                'id'       => $model->id,
                'name'     => $model->nama_bahan,
                'price'    => $model->price,
                'quantity' => $qty,
                'image'    => $model->image,
                'note'     => trim($charmNotes[$id] ?? ''), // notes spesifik per charm
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
            // Validasi stok saat update kuantitas di keranjang
            if ($cart[$id]['type'] === 'regular') {
                $product = Product::find($cart[$id]['id']);
                if ($product && $product->product_name !== 'Gelang Custom') {
                    if ($quantity > $product->dynamic_stock) {
                        return redirect()->back()->with('error', 'Stok tidak mencukupi untuk "' . $product->product_name . '". Tersedia: ' . $product->dynamic_stock . ' pcs.');
                    }
                }
            } elseif ($cart[$id]['type'] === 'custom') {
                // Validasi stok tali gelang
                $warna = $cart[$id]['warna'];
                $strapBahan = Bahan::where('nama_bahan', 'Tali Gelang ' . ucfirst($warna))->first();
                if ($strapBahan) {
                    if ($quantity > $strapBahan->dynamic_stock) {
                        return redirect()->back()->with('error', 'Stok tali gelang warna ' . $warna . ' tidak mencukupi. Tersisa: ' . $strapBahan->dynamic_stock . ' pcs.');
                    }
                }

                $charmsQuantities = $cart[$id]['charms_quantities'] ?? [];
                foreach ($charmsQuantities as $bahanId => $qtyPerBracelet) {
                    $bahan = Bahan::find($bahanId);
                    if ($bahan) {
                        $totalRequired = $qtyPerBracelet * $quantity;
                        if ($totalRequired > $bahan->dynamic_stock) {
                            return redirect()->back()->with('error', 'Stok manik "' . $bahan->nama_bahan . '" tidak mencukupi untuk jumlah gelang tersebut. Tersisa: ' . $bahan->dynamic_stock . ' pcs.');
                        }
                    }
                }
            }

            $cart[$id]['quantity'] = $quantity;
            Session::put('cart', $cart);

            if ($request->wantsJson() || $request->ajax()) {
                $itemSubtotal = $cart[$id]['price'] * $cart[$id]['quantity'];
                $grandTotal = 0;
                foreach ($cart as $item) {
                    $grandTotal += $item['price'] * $item['quantity'];
                }

                return response()->json([
                    'success' => true,
                    'quantity' => $cart[$id]['quantity'],
                    'itemSubtotal' => 'Rp ' . number_format($itemSubtotal, 0, ',', '.'),
                    'grandTotal' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
                    'message' => 'Kuantitas berhasil diperbarui.'
                ]);
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui kuantitas.'], 400);
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

        $expeditions = Expedition::all();
        return view('customer.checkout_gabungan', compact('cart', 'expeditions'));
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
            'courier' => 'required|string',
        ]);

        // 1. Validasi stok akhir produk reguler
        foreach ($cart as $item) {
            if ($item['type'] === 'regular') {
                $product = Product::find($item['id']);
                if ($product && $product->product_name !== 'Gelang Custom') {
                    if ($item['quantity'] > $product->dynamic_stock) {
                        return redirect()->back()->with('error', 'Stok produk "' . $product->product_name . '" tidak mencukupi. Tersedia: ' . $product->dynamic_stock . ' pcs.');
                    }
                }
            }
        }

        // 2. Validasi stok akhir manik-manik (charms) secara akumulatif
        $requiredCharms = [];
        $requiredStraps = [
            'silver' => 0,
            'gold' => 0,
        ];
        foreach ($cart as $item) {
            if ($item['type'] === 'custom') {
                // Akumulasi tali gelang
                $warna = strtolower($item['warna']);
                if (isset($requiredStraps[$warna])) {
                    $requiredStraps[$warna] += $item['quantity'];
                }

                $charmsQuantities = $item['charms_quantities'] ?? [];
                foreach ($charmsQuantities as $bahanId => $qtyPerBracelet) {
                    if (!isset($requiredCharms[$bahanId])) {
                        $requiredCharms[$bahanId] = 0;
                    }
                    $requiredCharms[$bahanId] += $qtyPerBracelet * $item['quantity'];
                }
            }
        }

        // Verifikasi stok manik-manik
        foreach ($requiredCharms as $bahanId => $totalQty) {
            $bahan = Bahan::find($bahanId);
            if ($bahan) {
                if ($totalQty > $bahan->dynamic_stock) {
                    return redirect()->back()->with('error', 'Stok manik-manik "' . $bahan->nama_bahan . '" tidak mencukupi untuk pesanan gelang custom Anda. Tersisa: ' . $bahan->dynamic_stock . ' pcs.');
                }
            }
        }

        // Verifikasi stok tali gelang
        foreach ($requiredStraps as $warna => $totalQty) {
            if ($totalQty > 0) {
                $strapBahan = Bahan::where('nama_bahan', 'Tali Gelang ' . ucfirst($warna))->first();
                if ($strapBahan && $totalQty > $strapBahan->dynamic_stock) {
                    return redirect()->back()->with('error', 'Stok tali gelang warna ' . $warna . ' tidak mencukupi. Tersisa: ' . $strapBahan->dynamic_stock . ' pcs.');
                }
            }
        }

        // Calculate item total
        $itemTotal = 0;
        foreach ($cart as $item) {
            $itemTotal += $item['price'] * $item['quantity'];
        }

        // Ambil data ekspedisi dari database
        $expedition = Expedition::where('name_expedition', $request->courier)->first();
        if (!$expedition) {
            return redirect()->back()->with('error', 'Kurir pengiriman tidak valid.');
        }

        $shippingCost = $expedition->shipping_cost;
        $estimatedDays = $expedition->estimated_days;
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
                $customProduct = \App\Models\Product::where('product_name', 'Gelang Custom')->first();
                $customProductId = $customProduct ? $customProduct->id : 5;

                // Catat di tabel induk barang pesanan (order_product_items)
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $customProductId,
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                for ($i = 0; $i < $item['quantity']; $i++) {
                    // Rangkai rincian catatan per-charm agar tersimpan di request_note tanpa ubah DB
                    $combinedNotes = [];
                    if (!empty($item['charms_details'])) {
                        $charmNoteLines = [];
                        foreach ($item['charms_details'] as $cd) {
                            if (!empty($cd['note'])) {
                                $charmNoteLines[] = '• ' . $cd['name'] . ' (x' . $cd['quantity'] . '): "' . $cd['note'] . '"';
                            }
                        }
                        if (!empty($charmNoteLines)) {
                            $combinedNotes[] = "📌 [Rincian Charm]:\n" . implode("\n", $charmNoteLines);
                        }
                    }

                    if (!empty($item['request_note'])) {
                        $combinedNotes[] = "📝 [Catatan Desain]: " . $item['request_note'];
                    }

                    $finalRequestNote = !empty($combinedNotes) ? implode("\n\n", $combinedNotes) : null;

                    $customBahanOrder = CustomBahanOrder::create([
                        'order_id' => $order->id,
                        'warna' => $item['warna'],
                        'request_note' => $finalRequestNote,
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
        Shipping::create([
            'order_id' => $order->id,
            'expedition_id' => $expedition->id,
            'shipping_cost' => $shippingCost,
            'estimated_arrival' => now()->addDays($estimatedDays),
            'status' => 'pending',
        ]);

        // Clear cart
        Session::forget('cart');

        return redirect()->route('order.success', $order->id);
    }
}
