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

        <form method="POST" action="{{ route('cart.add-custom') }}" class="space-y-8">
            @csrf

            {{-- Pilih Warna Strap --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
                <h3 class="font-bold text-lg text-gray-800 mb-5">1. Pilih Warna Strap</h3>
                <div class="flex gap-4">
                    @php
                        $isSilverOut = ($strapSilver && $strapSilver->dynamic_stock <= 0);
                        $isGoldOut = ($strapGold && $strapGold->dynamic_stock <= 0);
                    @endphp
                    
                    {{-- Silver Strap --}}
                    <label class="flex-1 cursor-pointer group {{ $isSilverOut ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="radio" name="warna" value="silver" class="hidden peer" required {{ $isSilverOut ? 'disabled' : '' }}>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 {{ $isSilverOut ? 'bg-gray-100' : 'group-hover:bg-gray-50' }} shadow-sm">
                            <div class="text-4xl mb-3">⚪</div>
                            <span class="font-semibold text-gray-700 block">Silver</span>
                            <span class="text-xs text-gray-400 block mt-1">
                                @if($isSilverOut)
                                    <strong class="text-red-500">Stok Habis</strong>
                                @else
                                    Stok: <strong class="text-gray-600">{{ $strapSilver->dynamic_stock }}</strong>
                                @endif
                            </span>
                        </div>
                    </label>
                    
                    {{-- Gold Strap --}}
                    <label class="flex-1 cursor-pointer group {{ $isGoldOut ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="radio" name="warna" value="gold" class="hidden peer" {{ $isGoldOut ? 'disabled' : '' }}>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 {{ $isGoldOut ? 'bg-gray-100' : 'group-hover:bg-gray-50' }} shadow-sm">
                            <div class="text-4xl mb-3">🟡</div>
                            <span class="font-semibold text-gray-700 block">Gold</span>
                            <span class="text-xs text-gray-400 block mt-1">
                                @if($isGoldOut)
                                    <strong class="text-red-500">Stok Habis</strong>
                                @else
                                    Stok: <strong class="text-gray-600">{{ $strapGold->dynamic_stock }}</strong>
                                @endif
                            </span>
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
                        @php
                            $charmStock = $charm->dynamic_stock;
                            $isCharmOutOfStock = $charmStock <= 0;
                        @endphp
                        <div class="charm-card-container group {{ $isCharmOutOfStock ? 'opacity-65' : '' }}">
                            <div class="charm-card border-2 border-gray-100 rounded-2xl p-4 text-center transition duration-300 {{ $isCharmOutOfStock ? 'bg-gray-50/50' : 'group-hover:border-rose-200' }}">
                                <div class="bg-rose-50/50 rounded-xl h-20 flex items-center justify-center mb-3 overflow-hidden relative cursor-pointer"
                                     onclick="openCharmModal({{ $charm->id }}, '{{ addslashes($charm->nama_bahan) }}', {{ $charm->price }}, {{ $charmStock }}, {{ $isCharmOutOfStock ? 'true' : 'false' }}, '{{ $charm->image ? Storage::url($charm->image) : '' }}')"
                                     title="Lihat detail charm">
                                    @if($isCharmOutOfStock)
                                        <div class="absolute inset-0 bg-black/5 flex items-center justify-center z-10">
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Habis</span>
                                        </div>
                                    @endif
                                    @if($charm->image)
                                        <img src="{{ Storage::url($charm->image) }}"
                                            alt="{{ $charm->nama_bahan }}"
                                            class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-500">
                                    @else
                                        <span class="text-3xl text-rose-300">📿</span>
                                    @endif
                                    {{-- Zoom hint overlay --}}
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 rounded-xl flex items-center justify-center">
                                        <span class="opacity-0 group-hover:opacity-100 transition duration-300 bg-white/90 rounded-full p-1 shadow text-gray-600 text-xs">
                                            🔍
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-gray-800 leading-tight mb-1">{{ $charm->nama_bahan }}</p>
                                <div class="flex flex-col gap-0.5 mb-3">
                                    <span class="text-xs text-rose-500 font-bold">
                                        Rp {{ number_format($charm->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        @if($isCharmOutOfStock)
                                            <strong class="text-red-500">Stok Habis</strong>
                                        @else
                                            Stok: <strong class="text-gray-600">{{ $charmStock }}</strong>
                                        @endif
                                    </span>
                                </div>

                                {{-- Counter Buttons --}}
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" 
                                        onclick="adjustQty({{ $charm->id }}, -1)"
                                        class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition font-bold select-none disabled:opacity-40"
                                        {{ $isCharmOutOfStock ? 'disabled' : '' }}>-</button>
                                    
                                    <span id="qty-display-{{ $charm->id }}" class="font-bold text-gray-700 w-6 text-center text-sm">0</span>
                                    
                                    <input type="hidden" name="charms[{{ $charm->id }}]" id="qty-input-{{ $charm->id }}" value="0" class="charm-qty-input">

                                    <button type="button" 
                                        onclick="adjustQty({{ $charm->id }}, 1)"
                                        class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition font-bold select-none disabled:opacity-40"
                                        {{ $isCharmOutOfStock ? 'disabled' : '' }}>+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Catatan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-6">
                <h3 class="font-bold text-lg text-gray-800 mb-2">3. Catatan Desain</h3>
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea name="request_note" rows="2"
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition leading-relaxed"
                        placeholder="Contoh: tolong diurutkan bintang, bulat, bintang ya..."></textarea>
                </div>
            </div>

            {{-- Total & Submit --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-5">
                <div class="bg-rose-50/30 border border-rose-100/50 rounded-2xl p-6 text-sm space-y-3">
                    <div class="flex justify-between text-gray-500">
                        <span>Strap Gelang</span>
                        <span class="font-medium text-gray-700">Rp 20.000</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Harga Charm Pilihan</span>
                        <span id="subtotal-price" class="font-medium text-gray-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 pt-4 border-t border-dashed border-rose-200/60 text-base">
                        <span>Total Desain</span>
                        <span id="total-price" class="text-rose-500 text-lg">Rp 20.000</span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full mt-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-4 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 text-base">
                    Masukkan Desain ke Keranjang 🛒
                </button>
            </div>

        </form>
    </div>

    {{-- ✨ Charm Detail Modal --}}
    <div id="charm-modal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 hidden" onclick="closeCharmModal(event)">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        {{-- Modal Card --}}
        <div id="charm-modal-card" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-90 opacity-0 transition-all duration-300">
            {{-- Close Button --}}
            <button onclick="document.getElementById('charm-modal').classList.add('hidden')" 
                class="absolute top-4 right-4 z-10 bg-white/90 hover:bg-gray-100 rounded-full w-10 h-10 flex items-center justify-center text-gray-500 shadow transition text-base">
                ✕
            </button>

            {{-- Image --}}
            <div class="bg-rose-50/60 h-80 flex items-center justify-center overflow-hidden relative" id="modal-img-wrap">
                <img id="modal-img" src="" alt="" class="w-full h-full object-contain p-6">
                <span id="modal-img-placeholder" class="text-8xl hidden">📿</span>
                <div id="modal-stock-badge" class="hidden absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full">Stok Habis</div>
            </div>

            {{-- Detail --}}
            <div class="p-8">
                <h3 id="modal-name" class="text-2xl font-bold text-gray-800 mb-2"></h3>
                <div class="flex items-center justify-between mb-6">
                    <span id="modal-price" class="text-rose-500 font-bold text-xl"></span>
                    <span id="modal-stock" class="text-sm text-gray-400 bg-gray-50 border border-gray-100 px-4 py-1.5 rounded-full"></span>
                </div>

                {{-- Counter inside modal --}}
                <div id="modal-actions" class="flex items-center justify-center gap-6 mt-2">
                    <button type="button" id="modal-btn-minus"
                        class="w-12 h-12 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-2xl select-none">
                        −
                    </button>
                    <span id="modal-qty" class="font-bold text-gray-700 text-2xl w-10 text-center">0</span>
                    <button type="button" id="modal-btn-plus"
                        class="w-12 h-12 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-2xl select-none">
                        +
                    </button>
                </div>
                <div id="modal-outofstock-msg" class="hidden text-center text-red-400 text-base font-medium mt-4">Stok manik-manik ini sedang habis</div>
            </div>
        </div>
    </div>

    <script>
        const prices = {
            @foreach($charms as $charm)
                {{ $charm->id }}: {{ $charm->price }},
            @endforeach
        };

        const stocks = {
            @foreach($charms as $charm)
                {{ $charm->id }}: {{ $charm->dynamic_stock }},
            @endforeach
        };

        const countEl = document.getElementById('charm-count');
        const subtotalEl = document.getElementById('subtotal-price');
        const totalEl = document.getElementById('total-price');

        function adjustQty(charmId, delta) {
            const input = document.getElementById('qty-input-' + charmId);
            const display = document.getElementById('qty-display-' + charmId);
            const card = display.closest('.charm-card');

            let currentVal = parseInt(input.value) || 0;
            let currentTotal = getTotalQty();

            // Validate against stock limit and max charms limit
            if (delta > 0) {
                if (currentVal >= stocks[charmId]) {
                    alert('Stok untuk manik-manik ini tidak mencukupi (Tersisa: ' + stocks[charmId] + ')');
                    return;
                }
                if (currentTotal >= 15) {
                    alert('Maksimal manik/charm yang dapat dimasukkan adalah 15.');
                    return;
                }
            }

            let newVal = currentVal + delta;
            if (newVal < 0) newVal = 0;

            input.value = newVal;
            display.textContent = newVal;

            // Update Card Highlight Styles
            if (newVal > 0) {
                card.classList.add('border-rose-400', 'bg-rose-50/30');
            } else {
                card.classList.remove('border-rose-400', 'bg-rose-50/30');
            }

            calculateTotal();
        }

        function getTotalQty() {
            let total = 0;
            document.querySelectorAll('.charm-qty-input').forEach(input => {
                total += parseInt(input.value) || 0;
            });
            return total;
        }

        function calculateTotal() {
            let totalQty = getTotalQty();
            countEl.textContent = totalQty;

            let subtotal = 0;
            document.querySelectorAll('.charm-qty-input').forEach(input => {
                const id = input.id.replace('qty-input-', '');
                const qty = parseInt(input.value) || 0;
                subtotal += (prices[id] || 0) * qty;
            });

            let totalPrice = 20000 + subtotal;

            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            totalEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        // Run initially
        calculateTotal();

        // ── Charm Detail Modal ──────────────────────────────────────
        let _modalCharmId = null;

        function openCharmModal(id, name, price, stock, outOfStock, imgUrl) {
            _modalCharmId = id;

            // Populate image
            const img = document.getElementById('modal-img');
            const placeholder = document.getElementById('modal-img-placeholder');
            if (imgUrl) {
                img.src = imgUrl;
                img.alt = name;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Stock badge
            const badge = document.getElementById('modal-stock-badge');
            badge.classList.toggle('hidden', !outOfStock);

            // Info text
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-price').textContent =
                'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('modal-stock').textContent =
                outOfStock ? 'Stok Habis' : 'Stok: ' + stock;

            // Counter sync
            const currentQty = parseInt(document.getElementById('qty-input-' + id).value) || 0;
            document.getElementById('modal-qty').textContent = currentQty;

            // Out of stock state
            const actions = document.getElementById('modal-actions');
            const oos = document.getElementById('modal-outofstock-msg');
            if (outOfStock) {
                actions.classList.add('opacity-40', 'pointer-events-none');
                oos.classList.remove('hidden');
            } else {
                actions.classList.remove('opacity-40', 'pointer-events-none');
                oos.classList.add('hidden');
            }

            // Wire buttons
            document.getElementById('modal-btn-minus').onclick = () => {
                adjustQty(_modalCharmId, -1);
                document.getElementById('modal-qty').textContent =
                    parseInt(document.getElementById('qty-input-' + _modalCharmId).value) || 0;
            };
            document.getElementById('modal-btn-plus').onclick = () => {
                adjustQty(_modalCharmId, 1);
                document.getElementById('modal-qty').textContent =
                    parseInt(document.getElementById('qty-input-' + _modalCharmId).value) || 0;
            };

            // Show with animation
            const modal = document.getElementById('charm-modal');
            const card  = document.getElementById('charm-modal-card');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCharmModal(e) {
            // Only close if clicking the backdrop (not the card itself)
            if (e && e.target.closest('#charm-modal-card')) return;
            const modal = document.getElementById('charm-modal');
            const card  = document.getElementById('charm-modal-card');
            card.classList.add('scale-90', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => modal.classList.add('hidden'), 280);
        }

        // Close on Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeCharmModal();
        });
    </script>

</body>
</html>