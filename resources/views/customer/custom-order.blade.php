<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelang Custom — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 min-h-screen">

    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-amber-500">Charm.onti</h1>
        <a href="/" class="text-sm text-gray-600 hover:text-amber-500">← Kembali</a>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Gelang Custom</h2>
        <p class="text-gray-500 text-sm mb-8">Pilih warna strap dan charm favoritmu (maks. 15 charm)</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('custom.order.store') }}" class="space-y-6">
            @csrf

            {{-- Pilih Warna Strap --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-4">1. Pilih Warna Strap</h3>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="warna" value="silver" class="hidden peer" required>
                        <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 rounded-xl p-4 text-center transition">
                            <div class="text-3xl mb-2">⬜</div>
                            <span class="font-medium text-gray-700">Silver</span>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="warna" value="gold" class="hidden peer">
                        <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 rounded-xl p-4 text-center transition">
                            <div class="text-3xl mb-2">🟨</div>
                            <span class="font-medium text-gray-700">Gold</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Pilih Charm --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">2. Pilih Charm</h3>
                    <span class="text-sm text-gray-500">Dipilih: <span id="charm-count" class="font-bold text-amber-500">0</span>/15</span>
                </div>

                @if($charms->isEmpty())
                    <p class="text-gray-400 text-center py-6">Belum ada charm tersedia.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($charms as $charm)
                        <label class="cursor-pointer">
                            <input type="checkbox" name="charms[]" value="{{ $charm->id }}"
                                class="hidden charm-checkbox">
                            <div class="charm-card border-2 border-gray-200 rounded-xl p-3 text-center transition hover:border-amber-300">
                                <div class="bg-amber-50 rounded-lg h-16 flex items-center justify-center mb-2 overflow-hidden">
                                    @if($charm->image)
                                        <img src="{{ Storage::url($charm->image) }}"
                                            alt="{{ $charm->name }}"
                                            class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-2xl">📿</span>
                                    @endif
                                </div>
                                <p class="text-xs font-medium text-gray-800 leading-tight">{{ $charm->name }}</p>
                                <p class="text-xs text-amber-500 font-bold mt-1">
                                    Rp {{ number_format($charm->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Alamat & Catatan --}}
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <h3 class="font-semibold text-gray-800 mb-2">3. Detail Pengiriman</h3>

                <div>
                    <label class="text-sm font-medium text-gray-700">Alamat Pengiriman</label>
                    <textarea name="shipping_address" rows="3" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Masukkan alamat lengkap...">{{ old('shipping_address') }}</textarea>
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
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Catatan Tambahan <span class="text-gray-400">(opsional)</span></label>
                    <textarea name="request_note" rows="2"
                        class="w-full mt-1 px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400"
                        placeholder="Contoh: tolong dibungkus cantik ya...">{{ old('request_note') }}</textarea>
                </div>
            </div>

            {{-- Total & Submit --}}
            <div class="bg-white rounded-xl shadow p-6 space-y-4">
                <div class="bg-amber-50/50 border border-amber-200/20 rounded-xl p-4 text-sm space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Harga Gelang Custom</span>
                        <span id="subtotal-price">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-cost-display">Rp 10.000</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-dashed border-amber-200">
                        <span>Total Bayar</span>
                        <span id="total-price" class="text-amber-600 text-base">Rp 10.000</span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition">
                    Pesan Gelang Custom
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
                this.closest('label').querySelector('.charm-card').classList.toggle('border-amber-500', this.checked);
                this.closest('label').querySelector('.charm-card').classList.toggle('bg-amber-50', this.checked);

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