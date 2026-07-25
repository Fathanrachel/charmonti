<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — Charm.onti</title>
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
        <div class="flex items-center gap-4">
            <a href="/" class="text-sm font-medium text-gray-500 hover:text-rose-400 transition">Katalog</a>
            @auth
                <a href="{{ route('customer.orders') }}" class="text-sm font-medium text-gray-500 hover:text-rose-400 transition">Pesanan Saya</a>
            @endauth
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Keranjang Belanja 🛒</h2>
                <p class="text-sm text-gray-500 mt-2 font-light">Tinjau item-item pilihan terbaikmu sebelum melakukan checkout</p>
            </div>
        </div>

        @if(empty($cart))
            <div class="bg-white border border-gray-100/50 rounded-3xl p-16 text-center shadow-sm">
                <div class="text-6xl mb-4 opacity-75">🛒</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang belanjamu kosong</h3>
                <p class="text-gray-500 text-sm mb-8 font-light max-w-sm mx-auto">Yuk, cari produk cantik atau rakit gelang custom impianmu dulu!</p>
                <a href="/" class="inline-block bg-rose-400 hover:bg-rose-500 text-white font-medium px-8 py-3.5 rounded-full transition shadow-sm hover:shadow-md">
                    Cari Produk ✨
                </a>
            </div>
        @else
            @php $totalPrice = 0; @endphp
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items Column --}}
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cart as $id => $item)
                        @php $totalPrice += $item['price'] * $item['quantity']; @endphp
                        <div class="bg-white border border-gray-100/50 rounded-2xl p-6 shadow-sm flex gap-5 items-center relative hover:shadow-md transition">
                            {{-- Item Image --}}
                            <div class="bg-rose-50/50 rounded-xl h-20 w-20 flex items-center justify-center shrink-0 border border-rose-50">
                                @if(isset($item['image']) && $item['image'])
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <span class="text-3xl">📿</span>
                                @endif
                            </div>

                            {{-- Item Info --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 text-base truncate">{{ $item['name'] }}</h4>
                                <p class="text-xs text-rose-400 font-semibold mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>

                                @if($item['type'] === 'custom' && isset($item['charms_details']))
                                    <div class="mt-2 text-[10px] text-gray-400 font-light flex flex-col gap-1">
                                        @foreach($item['charms_details'] as $charm)
                                            <div class="flex items-start gap-1.5">
                                                <span class="bg-rose-50 px-2 py-0.5 rounded border border-rose-100 shrink-0 charm-qty-badge-{{ $id }}"
                                                      data-charm-name="{{ $charm['name'] }}"
                                                      data-base-qty="{{ $charm['quantity'] }}">
                                                    {{ $charm['name'] }} ×{{ $charm['quantity'] * $item['quantity'] }}
                                                </span>
                                                @if(!empty($charm['note']))
                                                    <span class="text-rose-400 italic">→ "{{ $charm['note'] }}"</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Update Qty Form --}}
                                <form action="{{ route('cart.update', $id) }}" method="POST" id="qty-form-{{ $id }}" class="flex items-center gap-3 mt-3">
                                    @csrf
                                    <span class="text-xs font-semibold text-gray-400 uppercase">Qty:</span>
                                    <div class="flex items-center gap-2 border border-gray-200 rounded-full px-2 py-1 bg-gray-50/50">
                                        <button type="button" 
                                            onclick="updateCartQty('{{ $id }}', -1)"
                                            class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-xs shadow-2xs select-none">
                                            −
                                        </button>
                                        <span id="qty-disp-{{ $id }}" class="font-bold text-gray-800 text-sm px-1.5 select-none">{{ $item['quantity'] }}</span>
                                        <input type="hidden" name="quantity" id="qty-val-{{ $id }}" value="{{ $item['quantity'] }}">
                                        <button type="button" 
                                            onclick="updateCartQty('{{ $id }}', 1)"
                                            class="w-6 h-6 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-xs shadow-2xs select-none">
                                            +
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Delete action --}}
                            <div class="flex flex-col items-end justify-between h-full shrink-0">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition p-1" title="Hapus dari Keranjang">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                <span id="item-subtotal-{{ $id }}" class="font-bold text-gray-800 text-base mt-auto">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Checkout Card --}}
                @php
                    $totalQty = 0;
                    foreach($cart as $item) {
                        $totalQty += $item['quantity'];
                    }
                @endphp
                <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm h-fit space-y-6">
                    <h3 class="font-bold text-gray-800 text-lg border-b border-gray-50 pb-4">Ringkasan Belanja</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center text-gray-500 font-light">
                            <span>Total Barang</span>
                            <span class="font-bold text-gray-800"><span id="cart-total-qty">{{ $totalQty }}</span> pcs</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500 font-light">
                            <span>Subtotal Barang</span>
                            <span id="cart-grand-total" class="font-bold text-rose-500 text-base">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-rose-50/30 border border-rose-100/50 rounded-2xl p-4 text-xs text-rose-700 leading-relaxed font-light">
                        🌸 Tambahkan alamat pengiriman dan pilih kurir ekspedisi terbaikmu di halaman selanjutnya.
                    </div>

                    <a href="{{ route('checkout.gabungan') }}" class="block w-full bg-rose-400 hover:bg-rose-500 text-white text-center font-semibold py-4 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        Lanjut ke Checkout 🚀
                    </a>
                </div>
            </div>
        @endif
    </div>

    {{-- Custom Alert Modal --}}
    <div id="cart-alert-modal" 
         onclick="closeCartAlertModal(event)"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300 ease-out">
        <div id="cart-alert-modal-card" 
             class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl transform scale-90 translate-y-4 opacity-0 transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] border border-rose-100">
            <div class="h-16 w-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border border-rose-100/50 shadow-sm animate-pulse">
                🌸
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-2">Informasi Stok</h4>
            <p id="cart-alert-modal-msg" class="text-sm text-gray-600 font-light mb-6 leading-relaxed">Pesan modal</p>
            <button type="button" onclick="closeCartAlertModal()" class="w-full bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-bold py-3.5 rounded-full transition duration-300 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                Mengerti ✨
            </button>
        </div>
    </div>

    <script>
        function showCartAlert(msg) {
            const modal = document.getElementById('cart-alert-modal');
            const card  = document.getElementById('cart-alert-modal-card');
            const msgEl = document.getElementById('cart-alert-modal-msg');
            if (!modal || !card || !msgEl) return;

            msgEl.textContent = msg;

            // Ensure initial hidden state is applied
            modal.classList.add('opacity-0');
            card.classList.add('scale-90', 'translate-y-4', 'opacity-0');
            card.classList.remove('scale-100', 'translate-y-0', 'opacity-100');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Force DOM reflow to ensure initial state is rendered before animation starts
            void modal.offsetHeight;

            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-90', 'translate-y-4', 'opacity-0');
                card.classList.add('scale-100', 'translate-y-0', 'opacity-100');
            });
        }

        function closeCartAlertModal(e) {
            if (e && e.target && e.target.closest('#cart-alert-modal-card')) return;
            const modal = document.getElementById('cart-alert-modal');
            const card  = document.getElementById('cart-alert-modal-card');
            if (!card || !modal) return;

            modal.classList.add('opacity-0');
            card.classList.add('scale-95', 'translate-y-2', 'opacity-0');
            card.classList.remove('scale-100', 'translate-y-0', 'opacity-100');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 320);
        }

        function updateCartQty(id, delta) {
            const input = document.getElementById('qty-val-' + id);
            const disp = document.getElementById('qty-disp-' + id);
            let current = parseInt(input.value) || 1;
            let next = current + delta;
            if (next < 1) return;

            const form = document.getElementById('qty-form-' + id);
            const token = form.querySelector('input[name="_token"]').value;

            // Update UI temp
            disp.textContent = next;
            input.value = next;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: next })
            })
            .then(async res => {
                const data = await res.json().catch(() => null);
                if (!res.ok || !data || data.success === false) {
                    throw new Error((data && data.message) ? data.message : 'Gagal mengubah kuantitas.');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    disp.textContent = data.quantity;
                    input.value = data.quantity;
                    document.getElementById('item-subtotal-' + id).textContent = data.itemSubtotal;
                    document.getElementById('cart-grand-total').textContent = data.grandTotal;
                    if (document.getElementById('cart-total-qty')) {
                        document.getElementById('cart-total-qty').textContent = data.totalQty;
                    }
                    // Update charm badges dynamically if custom item
                    document.querySelectorAll('.charm-qty-badge-' + id).forEach(badge => {
                        const name = badge.getAttribute('data-charm-name');
                        const baseQty = parseInt(badge.getAttribute('data-base-qty'), 10) || 1;
                        badge.textContent = name + ' ×' + (baseQty * data.quantity);
                    });
                } else {
                    disp.textContent = current;
                    input.value = current;
                    showCartAlert(data.message || 'Gagal mengubah kuantitas.');
                }
            })
            .catch(err => {
                disp.textContent = current;
                input.value = current;
                showCartAlert(err.message || 'Stok tidak mencukupi.');
            });
        }
    </script>
</body>
</html>
