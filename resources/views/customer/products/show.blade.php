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

            @auth
                <a href="{{ route('customer.orders') }}" class="text-gray-500 hover:text-rose-500 transition">Pesanan Saya</a>
                <span class="text-rose-400 font-semibold">Halo, {{ Auth::user()->profile?->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">Logout</button>
                </form>
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

                <a href="{{ route('checkout', $product) }}"
                    class="mt-8 block w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-3.5 rounded-full shadow-sm hover:shadow-md transition text-center hover:-translate-y-0.5">
                        Pesan Sekarang 🛍️
                </a>
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