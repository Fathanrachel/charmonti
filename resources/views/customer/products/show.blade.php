<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-95 transition">
                <img src="{{ asset('logo.jpg') }}" alt="CharmOnTi Logo" class="h-9 w-9 rounded-full object-cover border border-amber-200/50 shadow-sm">
                <span class="bg-linear-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <div class="flex gap-4 text-sm items-center">
            <a href="/" class="text-gray-600 hover:text-amber-500">← Kembali</a>

            @auth
                <a href="{{ route('customer.orders') }}" class="text-gray-600 hover:text-amber-500">Pesanan Saya</a>
                <span class="text-gray-600">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-amber-500">Login</a>
                <a href="{{ route('register') }}"
                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-1.5 rounded-lg transition">
                Daftar
                </a>
            @endauth
        </div>
    </nav>

    {{-- Detail Produk --}}
    <div class="max-w-3xl mx-auto px-6 py-10">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col md:flex-row gap-8">

            {{-- Gambar placeholder --}}
            <div class="bg-amber-100 rounded-xl h-60 w-full md:w-64 flex items-center justify-center shrink-0 overflow-hidden">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover rounded-xl">
                @else
                    <span class="text-6xl">📿</span>
                @endif
            </div>

            {{-- Info Produk --}}
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h2>

                @php
                    $avgRating = $product->reviews->avg('rating');
                    $reviewCount = $product->reviews->count();
                @endphp
                @if($reviewCount > 0)
                    <div class="flex items-center gap-1 mt-1.5">
                        <span class="text-amber-400 text-lg">★</span>
                        <span class="text-sm font-bold text-gray-700">{{ number_format($avgRating, 1) }}/5.0</span>
                        <span class="text-xs text-gray-400">({{ $reviewCount }} Ulasan)</span>
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-1.5">Belum ada ulasan.</p>
                @endif

                @if($product->is_custom)
                    <span class="text-xs bg-amber-100 text-amber-600 px-2 py-1 rounded-full inline-block mt-2">
                        Produk Custom
                    </span>
                @endif

                <p class="text-3xl font-bold text-amber-500 mt-4">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <p class="text-gray-500 mt-4 text-sm leading-relaxed">
                    {{ $product->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <a href="{{ route('checkout', $product) }}"
                    class="mt-6 block w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition text-center">
                        Pesan Sekarang
                </a>
            </div>
        </div>
    </div>

    {{-- Ulasan Pelanggan --}}
    <div class="max-w-3xl mx-auto px-6 pb-12">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b border-amber-100 pb-3 mb-4 flex items-center justify-between">
                <span>Ulasan Pelanggan ⭐</span>
                <span class="text-xs text-gray-400 font-normal">Menampilkan {{ $reviewCount }} ulasan terbaru</span>
            </h3>

            @if($product->reviews->isNotEmpty())
                <div class="space-y-4">
                    @foreach($product->reviews->sortByDesc('created_at') as $review)
                        <div class="border-b border-gray-100 last:border-b-0 pb-4 last:pb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-semibold text-sm text-gray-800">{{ $review->user->name }}</span>
                                    <div class="flex items-center gap-0.5 mt-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-sm {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-600 text-sm mt-2 bg-amber-50/30 p-3 rounded-lg border border-amber-100/30 leading-relaxed italic">
                                    "{{ $review->comment }}"
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400 text-sm">
                    <span class="text-3xl block mb-2">💬</span>
                    <p>Belum ada komentar ulasan dari pembeli.</p>
                </div>
            @endif
        </div>
    </div>

</body>
</html>