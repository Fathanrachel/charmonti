<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="/" class="text-sm font-medium text-gray-500 hover:text-rose-500 transition">← Kembali</a>
    </nav>

    <div class="max-w-2xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 tracking-tight">Checkout</h2>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-6 mb-8 flex gap-5 items-center">
            <div class="bg-rose-50/50 rounded-2xl h-20 w-20 flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        alt="{{ $product->product_name }}"
                        class="w-full h-full object-cover rounded-2xl">
                @else
                    <span class="text-3xl text-rose-300">📿</span>
                @endif
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-800">{{ $product->product_name }}</h3>
                <p class="text-rose-500 font-bold mt-1 text-base">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Form Checkout --}}
        <form method="POST" action="{{ route('order.store', $product) }}" class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-6">
            @csrf

            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-2">Jumlah</label>
                <input type="number" name="quantity" value="1" min="1"
                    class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition">
                @error('quantity')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex flex-wrap justify-between items-center gap-2 mb-3">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                        <span>📍</span> Alamat Pengiriman
                    </label>
                    <a href="{{ route('customer.profile') }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-400 border border-rose-200/80 px-3.5 py-1.5 rounded-full transition duration-300 shadow-2xs group">
                        <span>✏️</span>
                        <span>Ubah Alamat di Profil</span>
                        <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                    </a>
                </div>
                <textarea name="shipping_address" rows="3" readonly required
                    class="w-full px-5 py-3 bg-gray-100/70 border border-gray-200 rounded-2xl text-sm text-gray-600 focus:outline-none cursor-not-allowed select-none leading-relaxed resize-none opacity-90"
                    placeholder="Alamat belum diisi. Silakan atur di menu Profil Saya.">{{ old('shipping_address', (Auth::user()->profile?->address_line ? Auth::user()->profile->address_line . ', ' . (Auth::user()->profile->city?->city ?? '') . ', ' . (Auth::user()->profile->city?->province?->province ?? '') . ' ' . (Auth::user()->profile->postal_code ?? '') : '')) }}</textarea>
                <p class="text-[11px] text-gray-400 mt-2 font-light flex items-center gap-1">
                    <span>🔒</span>
                    <span>Alamat pengiriman terkunci (*read-only*). Untuk mengubah alamat atau kota tujuan, silakan klik <a href="{{ route('customer.profile') }}" class="text-rose-500 font-semibold hover:underline">Ubah Alamat di Profil</a>.</span>
                </p>
                @error('shipping_address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilihan Kurir Pengiriman --}}
            <div>
                <label class="text-sm font-semibold text-gray-700 block mb-3">Pilih Kurir & Layanan Pengiriman</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($expeditions as $index => $exp)
                        <label class="cursor-pointer group">
                            <input type="radio" name="courier" value="{{ $exp->name_expedition }}" data-cost="{{ $exp->shipping_cost }}" class="hidden peer" required {{ $index === 0 ? 'checked' : '' }}>
                            <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-4 transition duration-300 group-hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">{{ $exp->name_expedition }}</span>
                                    <span class="text-xs text-gray-400 block mt-1 font-light">Estimasi: {{ $exp->estimated_days }} Hari</span>
                                </div>
                                <span class="font-extrabold text-rose-500 text-sm mt-4 block">Rp {{ number_format($exp->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('courier')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ringkasan --}}
            <div class="bg-rose-50/30 border border-rose-100/50 rounded-2xl p-6 text-sm space-y-3 mt-4">
                <div class="flex justify-between text-gray-500">
                    <span>Harga Barang</span>
                    <span id="subtotal" class="font-medium text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Ongkos Kirim</span>
                    <span id="shipping-cost-display" class="font-medium text-gray-700">Rp {{ number_format($expeditions->first()->shipping_cost ?? 10000, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 pt-4 border-t border-dashed border-rose-200/60 text-base">
                    <span>Total Bayar</span>
                    <span id="total" class="text-rose-500 text-lg">Rp {{ number_format($product->price + ($expeditions->first()->shipping_cost ?? 10000), 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit" id="checkout-submit-btn"
                class="w-full mt-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-4 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 text-base">
                Buat Pesanan 🛒
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

        function calculateTotal() {
            const qty = parseInt(qtyInput.value) || 1;
            const subtotal = price * qty;
            
            let shippingCost = 10000;
            courierRadios.forEach(radio => {
                if (radio.checked) {
                    shippingCost = parseInt(radio.getAttribute('data-cost')) || 0;
                }
            });
            
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

        // Anti-double click submit
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('checkout-submit-btn');
        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Memproses Pesanan... ⌛';
                submitBtn.classList.remove('bg-rose-400', 'hover:bg-rose-500');
                submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed', 'text-gray-500');
            });
        }
    </script>

</body>
</html>