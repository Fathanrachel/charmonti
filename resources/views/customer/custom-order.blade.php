<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelang Custom — Charm.onti</title>
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

    <div class="max-w-3xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 tracking-tight">Buat Gelang Custom 🌸</h2>
        <p class="text-gray-500 text-base font-light mb-8">Pilih warna strap dan charm favoritmu untuk merangkai kisahmu sendiri (maks. 15 charm)</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 text-sm rounded-2xl px-5 py-4 mb-6 border border-red-100 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('custom.order.store') }}" class="space-y-8">
            @csrf

            {{-- Pilih Warna Strap --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
                <h3 class="font-bold text-lg text-gray-800 mb-5">1. Pilih Warna Strap</h3>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="warna" value="silver" class="hidden peer" required>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 group-hover:bg-gray-50 shadow-sm">
                            <div class="text-4xl mb-3">⚪</div>
                            <span class="font-semibold text-gray-700">Silver</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer group">
                        <input type="radio" name="warna" value="gold" class="hidden peer">
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 group-hover:bg-gray-50 shadow-sm">
                            <div class="text-4xl mb-3">🟡</div>
                            <span class="font-semibold text-gray-700">Gold</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Pilih Charm --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-800">2. Pilih Charm</h3>
                    <span class="text-sm font-medium bg-rose-50 text-rose-500 px-4 py-1.5 rounded-full border border-rose-100">Dipilih: <span id="charm-count" class="font-bold">0</span>/15</span>
                </div>

                @if($charms->isEmpty())
                    <p class="text-gray-400 text-center py-8 font-light">Belum ada charm tersedia.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($charms as $charm)
                        <label class="cursor-pointer group">
                            <input type="checkbox" name="charms[]" value="{{ $charm->id }}"
                                class="hidden charm-checkbox">
                            <div class="charm-card border-2 border-gray-100 rounded-2xl p-4 text-center transition duration-300 group-hover:border-rose-200">
                                <div class="bg-rose-50/50 rounded-xl h-20 flex items-center justify-center mb-3 overflow-hidden">
                                    @if($charm->image)
                                        <img src="{{ Storage::url($charm->image) }}"
                                            alt="{{ $charm->nama_bahan }}"
                                            class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-500">
                                    @else
                                        <span class="text-3xl text-rose-300">📿</span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-800 leading-tight mb-1">{{ $charm->nama_bahan }}</p>
                                <p class="text-xs text-rose-500 font-bold">
                                    Rp {{ number_format($charm->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Alamat & Catatan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-6">
                <h3 class="font-bold text-lg text-gray-800 mb-2">3. Detail Pengiriman</h3>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Alamat Pengiriman</label>
                    <textarea name="shipping_address" rows="3" required
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition leading-relaxed"
                        placeholder="Masukkan alamat lengkap kamu...">{{ old('shipping_address') }}</textarea>
                </div>

                {{-- Pilihan Kurir Pengiriman --}}
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-3">Pilih Kurir & Layanan Pengiriman</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="courier" value="J&T" class="hidden peer" required checked>
                            <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-4 transition duration-300 group-hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">J&T Express</span>
                                    <span class="text-xs text-gray-400 block mt-1 font-light">Estimasi: 2 - 3 Hari</span>
                                </div>
                                <span class="font-extrabold text-rose-500 text-sm mt-4 block">Rp 10.000</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="courier" value="JNE" class="hidden peer">
                            <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-4 transition duration-300 group-hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">JNE Reguler</span>
                                    <span class="text-xs text-gray-400 block mt-1 font-light">Estimasi: 1 - 2 Hari</span>
                                </div>
                                <span class="font-extrabold text-rose-500 text-sm mt-4 block">Rp 12.000</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="courier" value="SiCepat" class="hidden peer">
                            <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-4 transition duration-300 group-hover:bg-gray-50 flex flex-col justify-between h-full shadow-sm">
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">SiCepat Halu</span>
                                    <span class="text-xs text-gray-400 block mt-1 font-light">Estimasi: 3 - 5 Hari</span>
                                </div>
                                <span class="font-extrabold text-rose-500 text-sm mt-4 block">Rp 8.000</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="request_note" rows="2"
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition leading-relaxed"
                        placeholder="Contoh: tolong dibungkus cantik ya...">{{ old('request_note') }}</textarea>
                </div>
            </div>

            {{-- Total & Submit --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-5">
                <div class="bg-rose-50/30 border border-rose-100/50 rounded-2xl p-6 text-sm space-y-3">
                    <div class="flex justify-between text-gray-500">
                        <span>Harga Gelang Custom</span>
                        <span id="subtotal-price" class="font-medium text-gray-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost-display" class="font-medium text-gray-700">Rp 10.000</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 pt-4 border-t border-dashed border-rose-200/60 text-base">
                        <span>Total Bayar</span>
                        <span id="total-price" class="text-rose-500 text-lg">Rp 10.000</span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full mt-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-4 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 text-base">
                    Pesan Gelang Custom ✨
                </button>
            </div>

        </form>
    </div>

    <script>
        const prices = {
            @foreach($charms as $charm)
                {{ $charm->id }}: {{ $charm->price }},
            @endforeach
        };

        const checkboxes = document.querySelectorAll('.charm-checkbox');
        const countEl = document.getElementById('charm-count');
        const subtotalEl = document.getElementById('subtotal-price');
        const shippingEl = document.getElementById('shipping-cost-display');
        const totalEl = document.getElementById('total-price');
        const courierRadios = document.querySelectorAll('input[name="courier"]');

        const shippingCosts = {
            'J&T': 10000,
            'JNE': 12000,
            'SiCepat': 8000
        };

        function calculateTotal() {
            const checked = document.querySelectorAll('.charm-checkbox:checked');
            
            // Update count
            countEl.textContent = checked.length;

            // Calculate items subtotal
            let subtotal = 0;
            checked.forEach(c => subtotal += prices[c.value] || 0);

            // Get selected courier cost
            let selectedCourier = 'J&T';
            courierRadios.forEach(radio => {
                if (radio.checked) selectedCourier = radio.value;
            });
            const shippingCost = shippingCosts[selectedCourier];

            // Update DOM
            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            shippingEl.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
            const grandTotal = subtotal + shippingCost;
            totalEl.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const checked = document.querySelectorAll('.charm-checkbox:checked');

                // Max 15
                if (checked.length > 15) {
                    this.checked = false;
                    return;
                }

                // Update style
                this.closest('label').querySelector('.charm-card').classList.toggle('border-rose-400', this.checked);
                this.closest('label').querySelector('.charm-card').classList.toggle('bg-rose-50/30', this.checked);

                calculateTotal();
            });
        });

        courierRadios.forEach(radio => {
            radio.addEventListener('change', calculateTotal);
        });

        // Run initially
        calculateTotal();
    </script>

</body>
</html>