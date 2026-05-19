<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 min-h-screen">

    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-95 transition">
                <img src="{{ asset('logo.jpg') }}" alt="CharmOnTi Logo" class="h-9 w-9 rounded-full object-cover border border-amber-200/50 shadow-sm">
                <span class="bg-linear-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="/" class="text-sm text-gray-600 hover:text-amber-500">← Kembali</a>
    </nav>

    <div class="max-w-2xl mx-auto px-6 py-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h2>

        <div class="bg-white rounded-xl shadow p-5 mb-6 flex gap-4 items-center">
            <div class="bg-amber-100 rounded-lg h-16 w-16 flex items-center justify-center shrink-0 overflow-hidden border border-amber-200/40">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover rounded-lg">
                @else
                    <span class="text-2xl">📿</span>
                @endif
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                <p class="text-amber-500 font-bold">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Form Checkout --}}
        <form method="POST" action="{{ route('order.store', $product) }}" class="bg-white rounded-xl shadow p-6 space-y-4">
            @csrf

            <div>
                <label class="text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="quantity" value="1" min="1"
                    class="w-full mt-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                @error('quantity')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                <textarea name="shipping_address" rows="3" required
                    class="w-full mt-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                    placeholder="Masukkan alamat lengkap kamu...">{{ old('shipping_address') }}</textarea>
                @error('shipping_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilihan Kurir Pengiriman --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Pilih Kurir & Layanan Pengiriman</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="courier" value="J&T" class="hidden peer" required checked>
                        <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 rounded-xl p-3.5 transition hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                            <div>
                                <span class="font-bold text-gray-800 text-sm block">J&T Express</span>
                                <span class="text-xs text-gray-400 block mt-0.5">Estimasi: 2 - 3 Hari</span>
                            </div>
                            <span class="font-extrabold text-amber-600 text-sm mt-3 block">Rp 10.000</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="courier" value="JNE" class="hidden peer">
                        <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 rounded-xl p-3.5 transition hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                            <div>
                                <span class="font-bold text-gray-800 text-sm block">JNE Reguler</span>
                                <span class="text-xs text-gray-400 block mt-0.5">Estimasi: 1 - 2 Hari</span>
                            </div>
                            <span class="font-extrabold text-amber-600 text-sm mt-3 block">Rp 12.000</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="courier" value="SiCepat" class="hidden peer">
                        <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50/50 rounded-xl p-3.5 transition hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                            <div>
                                <span class="font-bold text-gray-800 text-sm block">SiCepat Halu</span>
                                <span class="text-xs text-gray-400 block mt-0.5">Estimasi: 3 - 5 Hari</span>
                            </div>
                            <span class="font-extrabold text-amber-600 text-sm mt-3 block">Rp 8.000</span>
                        </div>
                    </label>
                </div>
                @error('courier')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ringkasan --}}
            <div class="bg-amber-50/50 border border-amber-200/20 rounded-xl p-4 text-sm space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Harga Barang</span>
                    <span id="subtotal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span id="shipping-cost-display">Rp 10.000</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-dashed border-amber-200">
                    <span>Total Bayar</span>
                    <span id="total" class="text-amber-600 text-base">Rp {{ number_format($product->price + 10000, 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition">
                Buat Pesanan
            </button>
        </form>
    </div>

    <script>
        const price = {{ $product->price }};
        const qtyInput = document.querySelector('input[name="quantity"]');
        const subtotalEl = document.getElementById('subtotal');
        const shippingEl = document.getElementById('shipping-cost-display');
        const totalEl = document.getElementById('total');
        const courierRadios = document.querySelectorAll('input[name="courier"]');

        const shippingCosts = {
            'J&T': 10000,
            'JNE': 12000,
            'SiCepat': 8000
        };

        function calculateTotal() {
            const qty = parseInt(qtyInput.value) || 1;
            const subtotal = price * qty;
            
            let selectedCourier = 'J&T';
            courierRadios.forEach(radio => {
                if (radio.checked) selectedCourier = radio.value;
            });
            const shippingCost = shippingCosts[selectedCourier];
            
            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            shippingEl.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
            const grandTotal = subtotal + shippingCost;
            totalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        qtyInput.addEventListener('input', calculateTotal);
        courierRadios.forEach(radio => {
            radio.addEventListener('change', calculateTotal);
        });

        // Run initially
        calculateTotal();
    </script>

</body>
</html>