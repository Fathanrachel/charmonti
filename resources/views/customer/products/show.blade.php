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
        <h1 class="text-xl font-bold text-amber-500">Charm.onti</h1>
        <a href="/" class="text-sm text-gray-600 hover:text-amber-500">← Kembali</a>
    </nav>

    {{-- Detail Produk --}}
    <div class="max-w-3xl mx-auto px-6 py-10">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col md:flex-row gap-8">

            {{-- Gambar placeholder --}}
            <div class="bg-amber-100 rounded-xl h-60 w-full md:w-64 flex items-center justify-center shrink-0">
                <span class="text-6xl">📿</span>
            </div>

            {{-- Info Produk --}}
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h2>

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

                <button class="mt-6 w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    </div>

</body>
</html>