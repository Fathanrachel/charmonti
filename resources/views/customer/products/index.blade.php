<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charm.onti — Gelang Custom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-amber-500">Charm.onti</h1>
        <div class="flex gap-4 text-sm items-center">
            <a href="/" class="text-gray-600 hover:text-amber-500">Produk</a>

            @auth
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

    {{-- Hero --}}
    <div class="bg-amber-50 py-12 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Gelang Custom Handmade</h2>
        <p class="text-gray-500 mt-2">Buat gelang sesuai keinginanmu</p>
    </div>

    {{-- Daftar Produk --}}
    <div class="max-w-6xl mx-auto px-6 py-10">
        <h3 class="text-xl font-semibold text-gray-700 mb-6">Semua Produk</h3>

        @if($products->isEmpty())
            <p class="text-gray-400 text-center py-10">Belum ada produk tersedia.</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <a href="{{ route('produk.show', $product) }}"
                   class="bg-white rounded-xl shadow hover:shadow-md transition p-4 block">
                    <div class="bg-amber-100 rounded-lg h-40 flex items-center justify-center mb-3 overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover rounded-lg">
                        @else
                            <span class="text-4xl">📿</span>
                        @endif
                    </div>
                    <h4 class="font-semibold text-gray-800 text-sm">{{ $product->name }}</h4>
                    <p class="text-amber-500 font-bold text-sm mt-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    @if($product->is_custom)
                        <span class="text-xs bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full mt-1 inline-block">
                            Custom
                        </span>
                    @endif
                </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Tombol Custom Order --}}
    <div class="mt-10 bg-amber-100 rounded-2xl p-8 text-center">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Mau Gelang Custom?</h3>
        <p class="text-gray-500 text-sm mb-4">Pilih charm favoritmu dan buat gelang unikmu sendiri!</p>
        <a href="{{ route('custom.order') }}"
        class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-semibold px-8 py-3 rounded-xl transition">
            Buat Gelang Custom ✨
        </a>
    </div>

</body>
</html>