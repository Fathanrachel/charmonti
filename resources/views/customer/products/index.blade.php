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
            <div class="text-center py-16 bg-white border border-gray-100 rounded-[2rem] shadow-sm">
                <div class="text-6xl mb-4 opacity-50">📿</div>
                <p class="text-gray-500 font-light">Belum ada produk tersedia saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                <a href="{{ route('produk.show', $product) }}"
                   class="bg-white rounded-[2rem] shadow-sm hover:shadow-md transition duration-300 p-5 block border border-gray-100/50 group transform hover:-translate-y-1">
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
                    @if($product->is_custom)
                        <span class="text-xs bg-rose-100/50 text-rose-600 font-medium px-3 py-1 rounded-full mt-2 inline-block border border-rose-200/50">
                            Custom 💖
                        </span>
                    @endif
                </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tombol Custom Order --}}
    <div class="max-w-4xl mx-auto px-6 pb-16">
        <div class="bg-rose-50/80 border border-rose-100 rounded-[2rem] p-10 md:p-12 text-center shadow-sm relative overflow-hidden">
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

</body>
</html>