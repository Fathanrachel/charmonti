<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->product_name }} — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <div class="flex gap-5 text-sm font-medium items-center">
            <a href="/" class="text-gray-500 hover:text-rose-500 transition">← Kembali</a>
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
                class="bg-rose-400 hover:bg-rose-500 text-white px-5 py-2 rounded-full shadow-sm transition">
                Daftar
                </a>
            @endauth
        </div>
    </nav>

    {{-- Detail Produk --}}
    <div class="max-w-4xl mx-auto px-6 py-12">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 flex flex-col md:flex-row gap-10">

            {{-- Gambar placeholder --}}
            <div class="bg-rose-50/50 rounded-2xl h-80 w-full md:w-80 flex items-center justify-center shrink-0 overflow-hidden shadow-sm">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        alt="{{ $product->product_name }}"
                        class="w-full h-full object-cover rounded-2xl hover:scale-105 transition duration-500">
                @else
                    <span class="text-6xl text-rose-300">📿</span>
                @endif
            </div>

            {{-- Info Produk --}}
            <div class="flex-1 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">{{ $product->product_name }}</h2>

                @php
                    $avgRating = $product->reviews->avg('rating');
                    $reviewCount = $product->reviews->count();
                @endphp
                @if($reviewCount > 0)
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="text-rose-400 text-xl">★</span>
                        <span class="text-sm font-bold text-gray-700">{{ number_format($avgRating, 1) }} / 5.0</span>
                        <span class="text-sm text-gray-400 font-light">({{ $reviewCount }} Ulasan)</span>
                    </div>
                @else
                    <p class="text-sm text-gray-400 mt-2 font-light italic">Belum ada ulasan.</p>
                @endif

                <div class="mt-3">
                    @if($product->is_custom)
                        <span class="text-xs bg-rose-50 text-rose-500 font-medium px-3 py-1 rounded-full inline-block border border-rose-100">
                            ✨ Customizable
                        </span>
                    @endif
                </div>

                <p class="text-4xl font-bold text-rose-500 mt-6 tracking-tight">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi Produk</h4>
                    <p class="text-gray-500 text-sm leading-relaxed font-light">
                        {{ $product->description ?? 'Sebuah karya perhiasan indah yang dirangkai dengan penuh cinta. Cocok untuk menyempurnakan penampilan harian Anda.' }}
                    </p>
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-8 space-y-4">
                    @csrf
                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jumlah:</label>
                        <input type="number" name="quantity" value="1" min="1" class="w-20 border border-gray-200 rounded-xl px-3 py-2 text-center text-sm font-bold focus:border-rose-400 focus:outline-none">
                    </div>
                    <button type="submit"
                        class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3.5 rounded-full shadow-sm hover:shadow-md transition text-center hover:-translate-y-0.5">
                        Masukkan ke Keranjang 🛒
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Ulasan Pelanggan --}}
    <div class="max-w-4xl mx-auto px-6 pb-16">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
            <h3 class="text-xl font-bold text-gray-800 border-b border-rose-100/50 pb-4 mb-6 flex items-center justify-between">
                <span>Apa Kata Mereka? 💌</span>
                <span class="text-sm text-gray-400 font-light">{{ $reviewCount }} ulasan terbaru</span>
            </h3>

            @if($product->reviews->isNotEmpty())
                <div class="space-y-6">
                    @foreach($product->reviews->sortByDesc('created_at') as $review)
                        <div class="border-b border-gray-50 last:border-b-0 pb-6 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-semibold text-base text-gray-800">{{ $review->user->profile?->name }}</span>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-sm {{ $i <= $review->rating ? 'text-rose-400' : 'text-gray-200' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 font-light">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <div class="mt-3 bg-gradient-to-br from-rose-50/50 to-pink-50/50 p-4 rounded-2xl border border-rose-100/30 relative">
                                    <span class="absolute top-2 left-3 text-2xl text-rose-200 opacity-50">"</span>
                                    <p class="text-gray-600 text-sm leading-relaxed italic pl-4 font-light relative z-10">
                                        {{ $review->comment }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-gray-400 text-sm flex flex-col items-center justify-center">
                    <span class="text-4xl block mb-3 opacity-50">💭</span>
                    <p class="font-light">Belum ada komentar ulasan dari pembeli.</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>