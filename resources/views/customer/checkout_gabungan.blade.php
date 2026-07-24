<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Gabungan — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center shadow-sm">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="{{ route('cart.index') }}" class="text-sm font-medium text-rose-500 hover:text-rose-600 transition">Kembali ke Keranjang</a>
    </nav>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight mb-8">Checkout Pesanan 🌸</h2>

        <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            {{-- Checkout Form Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Alamat --}}
                <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-lg mb-2">📍 Alamat Pengiriman</h3>
                    @if(Auth::user()->profile?->city)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-100 rounded-full text-xs font-semibold text-rose-600 mb-3">
                            <span>Kota Tujuan:</span>
                            <span class="font-bold">{{ Auth::user()->profile->city->city }}, {{ Auth::user()->profile->city->province?->province }}</span>
                        </div>
                    @endif
                    <textarea name="shipping_address" rows="3" required class="w-full border border-gray-200 rounded-2xl p-4 focus:border-rose-400 focus:outline-none" placeholder="Masukkan alamat lengkap pengiriman paket">{{ Auth::user()->profile?->address_line }}</textarea>
                </div>

                {{-- Pilih Kurir --}}
                <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-lg mb-4">🚚 Pilihan Ekspedisi</h3>
                    
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($expeditions as $index => $exp)
                            <label class="cursor-pointer">
                                <input type="radio" name="courier" value="{{ $exp->name_expedition }}" data-cost="{{ $exp->shipping_cost }}" class="hidden peer" required {{ $index === 0 ? 'checked' : '' }}>
                                <div class="border border-gray-200 peer-checked:border-rose-400 peer-checked:bg-rose-50/20 rounded-2xl p-4 text-center hover:border-gray-300 transition h-full flex flex-col justify-center items-center">
                                    <span class="block font-bold text-sm text-gray-800">{{ $exp->name_expedition }}</span>
                                    <span class="block text-xs text-gray-400 mt-1">Rp {{ number_format($exp->shipping_cost, 0, ',', '.') }} ({{ $exp->estimated_days }} Hari)</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Summary List --}}
                <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg mb-2 flex items-center gap-2">
                        <span>🛍️</span> Rincian Pembelian
                    </h3>
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4 last:border-0 last:pb-0 gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="bg-rose-50/50 rounded-xl h-14 w-14 flex items-center justify-center shrink-0 border border-rose-50 overflow-hidden">
                                    @if(isset($item['image']) && $item['image'])
                                        <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        <span class="text-2xl">📿</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-sm text-gray-800 block truncate">{{ $item['name'] }}</span>
                                    <span class="text-xs text-rose-500 font-medium mt-0.5 block">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-gray-400 font-normal">× {{ $item['quantity'] }}</span></span>
                                    @if($item['type'] === 'custom' && isset($item['charms_details']))
                                        <div class="mt-1 text-[10px] text-gray-400 font-light flex flex-col gap-0.5">
                                            @foreach($item['charms_details'] as $charm)
                                                <span class="text-gray-500">• {{ $charm['name'] }} (×{{ $charm['quantity'] }}) @if(!empty($charm['note'])) <span class="text-rose-400 italic">"{{ $charm['note'] }}"</span> @endif</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <span class="font-bold text-gray-800 text-sm shrink-0">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Checkout Summary Card Column --}}
            <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm h-fit space-y-6">
                <h3 class="font-bold text-gray-800 text-lg border-b border-gray-50 pb-4">Total Pembayaran</h3>
                
                @php 
                    $itemTotal = 0;
                    foreach($cart as $item) {
                        $itemTotal += $item['price'] * $item['quantity'];
                    }
                @endphp

                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span class="font-light">Subtotal Barang</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-light">Ongkos Kirim</span>
                        <span class="font-bold text-gray-800" id="shipping-cost-label">Rp {{ number_format($expeditions->first()->shipping_cost ?? 10000, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-gray-50">
                    <div class="flex justify-between text-base">
                        <span class="font-semibold text-gray-800">Total Harga</span>
                        <span class="font-bold text-rose-500" id="total-price-label">Rp {{ number_format($itemTotal + ($expeditions->first()->shipping_cost ?? 10000), 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" id="checkout-submit-btn" class="w-full bg-rose-400 hover:bg-rose-500 text-white text-center font-semibold py-4 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    Buat Pesanan & Bayar 💳
                </button>
            </div>
        </form>
    </div>

    {{-- Script anti-double click submit --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>

    {{-- Script hitung ongkir dinamis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courierRadios = document.querySelectorAll('input[name="courier"]');
            const shippingCostLabel = document.getElementById('shipping-cost-label');
            const totalPriceLabel = document.getElementById('total-price-label');

            const itemTotal = {{ $itemTotal }};

            function updatePricing() {
                let shippingCost = 10000;
                courierRadios.forEach(radio => {
                    if (radio.checked) {
                        shippingCost = parseInt(radio.getAttribute('data-cost')) || 0;
                    }
                });

                const total = itemTotal + shippingCost;

                shippingCostLabel.innerHTML = 'Rp ' + shippingCost.toLocaleString('id-ID');
                totalPriceLabel.innerHTML = 'Rp ' + total.toLocaleString('id-ID');
            }

            courierRadios.forEach(radio => {
                radio.addEventListener('change', updatePricing);
            });
        });
    </script>
</body>
</html>
