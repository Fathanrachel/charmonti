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
                    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
                        <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                            <span>📍</span> Alamat Pengiriman
                        </h3>
                        <a href="{{ route('customer.profile') }}" 
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-400 border border-rose-200/80 px-3.5 py-1.5 rounded-full transition duration-300 shadow-2xs group">
                            <span>✏️</span>
                            <span>Ubah Alamat di Profil</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if(Auth::user()->profile?->name)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 border border-gray-200 rounded-full text-xs font-semibold text-gray-700">
                                👤 {{ Auth::user()->profile->name }} @if(Auth::user()->profile->phone) ({{ Auth::user()->profile->phone }}) @endif
                            </span>
                        @endif

                        @if(Auth::user()->profile?->city)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 border border-rose-100 rounded-full text-xs font-semibold text-rose-600">
                                🏙️ Kota Tujuan: <span class="font-bold">{{ Auth::user()->profile->city->city }}, {{ Auth::user()->profile->city->province?->province }}</span>
                            </span>
                        @else
                            <a href="{{ route('customer.profile') }}" class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 border border-amber-200 rounded-full text-xs font-semibold text-amber-600 hover:bg-amber-100 transition">
                                ⚠️ Kota tujuan belum diatur — Klik untuk atur di Profil
                            </a>
                        @endif
                    </div>

                    <textarea name="shipping_address" rows="3" readonly required 
                              class="w-full bg-gray-100/70 border border-gray-200 rounded-2xl p-4 text-sm text-gray-600 focus:outline-none cursor-not-allowed select-none leading-relaxed placeholder-gray-400 shadow-2xs resize-none opacity-90" 
                              placeholder="Alamat belum diisi. Silakan atur di menu Profil Saya.">{{ Auth::user()->profile?->address_line ? Auth::user()->profile->address_line . ', ' . (Auth::user()->profile->city?->city ?? '') . ', ' . (Auth::user()->profile->city?->province?->province ?? '') . ' ' . (Auth::user()->profile->postal_code ?? '') : '' }}</textarea>
                    
                    <p class="text-[11px] text-gray-400 mt-2 font-light flex items-center gap-1">
                        <span>🔒</span>
                        <span>Alamat pengiriman terkunci (*read-only*). Untuk mengubah alamat atau kota tujuan, silakan klik <a href="{{ route('customer.profile') }}" class="text-rose-500 font-semibold hover:underline">Ubah Alamat di Profil</a>.</span>
                    </p>
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
                        @php
                            $itemImage = null;
                            if ($item['type'] === 'custom') {
                                if (isset($item['warna']) && $item['warna'] !== 'tanpa_strap') {
                                    $color = strtolower(trim($item['warna']));
                                    $strap = \App\Models\Bahan::whereRaw('LOWER(nama_bahan) LIKE ?', ['%' . $color . '%'])->first();
                                    $itemImage = $strap?->image;
                                }
                                if (!$itemImage) {
                                    $itemImage = $item['image'] ?? ($item['charms_details'][0]['image'] ?? null);
                                }
                            } else {
                                $itemImage = $item['image'] ?? null;
                            }
                        @endphp
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4 last:border-0 last:pb-0 gap-4">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="bg-rose-50/50 rounded-xl h-14 w-14 flex items-center justify-center shrink-0 border border-rose-50 overflow-hidden">
                                    @if($itemImage)
                                        <img src="{{ Storage::url($itemImage) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        <span class="text-2xl">💎</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-sm text-gray-800 block truncate">{{ $item['name'] }}</span>
                                    <span class="text-xs text-rose-500 font-medium mt-0.5 block">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-gray-400 font-normal">× {{ $item['quantity'] }}</span></span>
                                    @if($item['type'] === 'custom' && isset($item['charms_details']))
                                        <div class="mt-1 flex flex-wrap gap-1.5">
                                            @foreach($item['charms_details'] as $charm)
                                                <div class="inline-flex items-center gap-1 bg-rose-50/80 px-2 py-0.5 rounded border border-rose-100/60 text-[10px]">
                                                    @if(!empty($charm['image']))
                                                        <img src="{{ Storage::url($charm['image']) }}" alt="{{ $charm['name'] }}" class="h-3.5 w-3.5 object-cover rounded shrink-0">
                                                    @endif
                                                    <span class="text-gray-600 font-medium">{{ $charm['name'] }} (×{{ $charm['quantity'] }})</span>
                                                </div>
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
