<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charm.onti — Gelang Custom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-gray-100">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <div class="flex gap-5 text-sm font-medium items-center">
            <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-rose-500 transition p-2 rounded-full hover:bg-rose-50/50 flex items-center justify-center" title="Keranjang Belanja">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @php $cartCount = count(Session::get('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="absolute top-1 right-1 bg-rose-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center border border-white shadow-xs">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="/" class="text-rose-500 border-b-2 border-rose-400 pb-1">Produk</a>

            @auth
                <!-- Dropdown Profile -->
                <div class="relative inline-block text-left" id="profile-dropdown-container">
                    <button type="button" id="dropdown-btn" class="flex items-center gap-1.5 text-gray-700 hover:text-rose-500 font-semibold focus:outline-none transition py-1">
                        <span>Halo, <span class="text-rose-400 font-bold">{{ Auth::user()->profile?->name }}</span></span>
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="dropdown-menu" class="hidden absolute right-0 mt-2.5 w-48 rounded-2xl bg-white border border-gray-100 shadow-lg py-2 z-50 ring-1 ring-black/5 transition duration-300">
                        <a href="{{ route('customer.profile') }}" class="block px-5 py-2.5 text-sm text-gray-600 hover:bg-rose-50/50 hover:text-rose-500 font-medium transition">
                            👤 Profil Saya
                        </a>
                        <a href="{{ route('customer.orders') }}" class="block px-5 py-2.5 text-sm text-gray-600 hover:bg-rose-50/50 hover:text-rose-500 font-medium transition">
                            📦 Pesanan Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-5 py-2.5 text-sm text-red-500 hover:bg-red-50/50 font-semibold transition">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btn = document.getElementById('dropdown-btn');
                        const menu = document.getElementById('dropdown-menu');
                        
                        btn.addEventListener('click', function (e) {
                            e.stopPropagation();
                            menu.classList.toggle('hidden');
                        });

                        document.addEventListener('click', function () {
                            menu.classList.add('hidden');
                        });
                    });
                </script>
            @else
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-rose-500 transition">Login</a>
                <a href="{{ route('register') }}"
                   class="bg-rose-400 hover:bg-rose-500 text-white font-medium px-5 py-2 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5">
                   Daftar ✨
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <div class="bg-rose-50/50 py-16 text-center border-b border-rose-50">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-800 tracking-tight mb-4">Gelang Custom Handmade 🌸</h2>
        <p class="text-gray-500 text-lg font-light max-w-xl mx-auto">Buat gelang cantik sesuai dengan impian dan kepribadianmu</p>
    </div>

    {{-- Daftar Produk --}}
    <div class="max-w-6xl mx-auto px-6 py-12">
        <h3 class="text-2xl font-bold text-gray-800 tracking-tight mb-8">Semua Produk ✨</h3>

        @if($products->isEmpty())
            <div class="text-center py-16 bg-white border border-gray-100 rounded-4xl shadow-sm">
                <div class="text-6xl mb-4 opacity-50">📿</div>
                <p class="text-gray-500 font-light">Belum ada produk tersedia saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                <a href="{{ route('produk.show', $product) }}"
                   class="bg-white rounded-4xl shadow-sm hover:shadow-md transition duration-300 p-5 block border border-gray-100/50 group transform hover:-translate-y-1">
                    <div class="bg-rose-50/50 rounded-2xl h-48 flex items-center justify-center mb-4 overflow-hidden border border-rose-100/50">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                alt="{{ $product->product_name }}"
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                        @else
                            <span class="text-5xl text-rose-300 group-hover:scale-110 transition duration-300">📿</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-800 text-base mb-1">{{ $product->product_name }}</h4>
                    <p class="text-rose-500 font-bold text-sm tracking-wide">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center justify-between mt-2">
                        @if($product->is_custom)
                            <span class="text-[11px] bg-rose-50 text-rose-500 font-semibold px-2.5 py-0.5 rounded-full border border-rose-100 flex items-center gap-1">
                                ✨ Custom Order
                            </span>
                        @else
                            <span class="text-[11px] text-gray-400 font-light">
                                Ready Item
                            </span>
                        @endif

                        @php $avg = $product->reviews->avg('rating'); @endphp
                        @if($avg)
                            <span class="text-xs font-semibold text-amber-500 flex items-center gap-0.5">
                                ★ {{ number_format($avg, 1) }}
                            </span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tombol Custom Order --}}
    <div class="max-w-4xl mx-auto px-6 pb-16">
        <div class="bg-rose-50/80 border border-rose-100 rounded-4xl p-10 md:p-12 text-center shadow-sm relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-200/40 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-rose-200/40 rounded-full blur-2xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight mb-3">Punya ide unik untuk gelangmu? 💭</h3>
                <p class="text-gray-500 text-base font-light mb-8 max-w-md mx-auto">Pilih charm favoritmu dan kombinasikan menjadi gelang eksklusif hanya untukmu!</p>
                <a href="{{ route('custom.order') }}"
                   class="inline-block bg-rose-400 hover:bg-rose-500 text-white font-medium px-8 py-4 rounded-full transition shadow-sm hover:shadow-md transform hover:-translate-y-0.5 text-lg">
                    Buat Gelang Custom Sekarang ✨
                </a>
            </div>
        </div>
    </div>

    {{-- Ulasan Pelanggan --}}
    <div class="max-w-6xl mx-auto px-6 pb-24">
        <div class="text-center mb-12">
            <h3 class="text-3xl font-bold text-gray-800 tracking-tight">Ulasan Pelanggan Sayang CharmOnTi 💖</h3>
            <p class="text-gray-500 mt-2 font-light text-sm">Apa kata mereka yang sudah memiliki koleksi perhiasan cantik dari kami?</p>
        </div>

        @if($reviews->isEmpty())
            <div class="text-center py-12 bg-white border border-gray-100/50 rounded-4xl shadow-sm max-w-xl mx-auto">
                <div class="text-5xl mb-3 opacity-60">✨</div>
                <p class="text-gray-400 font-light text-sm">Belum ada ulasan masuk. Jadilah yang pertama memberikan ulasan cantikmu!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $rev)
                    <div class="bg-white rounded-4xl p-6 border border-gray-100/50 shadow-xs hover:shadow-sm transition duration-300 flex flex-col justify-between transform hover:-translate-y-0.5">
                        <div>
                            {{-- Rating Stars --}}
                            <div class="flex gap-0.5 text-amber-400 mb-3 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rev->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            
                            {{-- Comment --}}
                            <p class="text-gray-600 text-sm italic font-light mb-4 leading-relaxed">
                                "{{ $rev->comment }}"
                            </p>
                        </div>

                        <div class="border-t border-rose-50/50 pt-4 flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-rose-50 border border-rose-100/50 flex items-center justify-center font-bold text-rose-400 text-sm">
                                {{ strtoupper(substr($rev->user->profile->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-sm font-semibold text-gray-800 truncate">{{ $rev->user->profile->name ?? 'Pelanggan Cantik' }}</h5>
                                @php
                                    $purchasedNames = [];
                                    if ($rev->order) {
                                        foreach ($rev->order->orderItems as $item) {
                                            if ($item->product_id != 4 && $item->product) {
                                                $purchasedNames[] = $item->product->product_name;
                                            }
                                        }
                                        if ($rev->order->customBahanOrder) {
                                            $purchasedNames[] = 'Gelang Custom';
                                        }
                                    }
                                    $purchasedText = !empty($purchasedNames) ? implode(', ', $purchasedNames) : ($rev->product->product_name ?? 'Gelang Custom');
                                @endphp
                                <p class="text-[10px] text-gray-400">
                                    Membeli: <span class="font-medium text-rose-400">{{ $purchasedText }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-20 py-12">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            {{-- Brand info --}}
            <div class="text-center md:text-left">
                <a href="/" class="flex items-center justify-center md:justify-start gap-2.5 mb-3">
                    <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-8 w-8 rounded-full object-cover border border-rose-100 shadow-xs">
                    <span class="font-bold tracking-tight text-lg bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
                </a>
                <p class="text-gray-400 text-xs font-light max-w-xs leading-relaxed">
                    Gelang custom handmade berkualitas tinggi untuk melengkapi keindahan setiap momen berhargamu. 🌸
                </p>
            </div>

            {{-- Social Media Links --}}
            <div class="flex items-center gap-4">
                {{-- Instagram Link --}}
                <a href="https://www.instagram.com/charm.onti/?hl=en" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-[#E1306C] transition p-2.5 rounded-full hover:bg-rose-50/50 flex items-center justify-center border border-gray-100 hover:border-[#E1306C]/30 shadow-xs" title="Follow Instagram kami">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                
                {{-- TikTok Link --}}
                <a href="https://www.tiktok.com/@charm.onti" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-black transition p-2.5 rounded-full hover:bg-gray-50 flex items-center justify-center border border-gray-100 hover:border-black/30 shadow-xs" title="Follow TikTok kami">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.99-1.72-.08-.07-.15-.15-.24-.22v6.4c.05 1.94-.48 3.97-1.74 5.48-1.52 1.87-3.99 2.87-6.38 2.58-2.92-.37-5.59-2.61-6.19-5.49-.7-3.19.86-6.68 3.93-7.79 1.15-.43 2.39-.51 3.59-.28v4.09c-.87-.27-1.87-.25-2.67.28-1.12.75-1.57 2.21-1.07 3.47.47 1.25 1.83 2.05 3.14 1.84 1.43-.2 2.57-1.47 2.57-2.91V.02h-.83z"/>
                    </svg>
                </a>
            </div>

            {{-- Copyright info --}}
            <div class="text-center md:text-right">
                <p class="text-gray-400 text-xs font-light">
                    &copy; {{ date('Y') }} <span class="font-semibold text-gray-700">CharmOnTi</span>. All rights reserved.
                </p>
                <p class="text-[10px] text-rose-300 mt-1 font-light flex items-center justify-center md:justify-end gap-1">
                    Made with 💖 for your beauty
                </p>
            </div>
        </div>
    </footer>

</body>
</html>