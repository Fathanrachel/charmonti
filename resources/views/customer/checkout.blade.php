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
                <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">CharmOnTi</span>
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
                <textarea name="shipping_address" rows="3"
                    class="w-full mt-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                    placeholder="Masukkan alamat lengkap kamu...">{{ old('shipping_address') }}</textarea>
                @error('shipping_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ringkasan --}}
            <div class="bg-amber-50 rounded-lg p-4 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Harga satuan</span>
                    <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 mt-2 pt-2 border-t">
                    <span>Total</span>
                    <span id="total">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
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
        const totalEl = document.getElementById('total');

        qtyInput.addEventListener('input', function() {
            const qty = parseInt(this.value) || 1;
            const total = price * qty;
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
        });
    </script>

</body>
</html>