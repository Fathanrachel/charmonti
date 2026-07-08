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
                                    <div class="mt-2 text-[10px] text-gray-400 font-light flex flex-wrap gap-1">
                                        @foreach($item['charms_details'] as $charm)
                                            <span class="bg-rose-50 px-2 py-0.5 rounded border border-rose-100">{{ $charm['name'] }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Update Qty Form --}}
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2 mt-3">
                                    @csrf
                                    <label class="text-[11px] font-medium text-gray-400 uppercase">Qty:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border border-gray-200 rounded-lg px-2 py-1 text-sm text-center font-bold focus:border-rose-400 focus:outline-none" onchange="this.form.submit()">
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
                                <span class="font-bold text-gray-800 text-base mt-auto">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Checkout Card --}}
                <div class="bg-white border border-gray-100/50 rounded-3xl p-6 shadow-sm h-fit space-y-6">
                    <h3 class="font-bold text-gray-800 text-lg border-b border-gray-50 pb-4">Ringkasan Belanja</h3>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-light text-gray-500">Subtotal Barang</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
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

</body>
</html>
