<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="{{ route('customer.orders') }}" class="text-sm font-medium text-gray-500 hover:text-rose-500 transition">← Batal & Kembali</a>
    </nav>

    <div class="max-w-xl mx-auto px-6 py-16 flex-1 flex flex-col justify-center">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Selesaikan Pembayaran</h2>
            <p class="text-gray-500 mt-2 font-light">Satu langkah lagi untuk memiliki gelang cantikmu 🌸</p>
        </div>

        {{-- Info Order --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 mb-8">
            <div class="flex justify-between items-center mb-5">
                <span class="text-gray-400 font-medium">Order <span class="text-gray-600">#{{ $order->id }}</span></span>
                <span class="text-rose-500 font-bold text-xl tracking-tight">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>

            <div class="border-t border-rose-50 pt-5 space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-rose-50/50 rounded-xl h-12 w-12 flex items-center justify-center shrink-0 border border-rose-100/50">
                            @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->product_name }}" class="w-full h-full object-cover rounded-xl">
                            @else
                                <span class="text-xl text-rose-300">📿</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $item->product->product_name }}</p>
                            <p class="text-xs text-gray-400 font-light">{{ $item->qty }}x</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-600">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
            
            @if($order->shipping)
            <div class="mt-4 pt-4 border-t border-dashed border-rose-100 flex justify-between items-center">
                <span class="text-sm text-gray-500 font-light">Ongkos Kirim ({{ $order->shipping->courier }})</span>
                <span class="text-sm font-medium text-gray-600">Rp {{ number_format($order->shipping->cost, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        {{-- Tombol Bayar --}}
        <button id="pay-button"
            class="w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-4 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 text-lg flex justify-center items-center gap-2">
            Bayar Sekarang 💳
        </button>

        <p class="text-center text-xs text-gray-400 mt-6 font-light flex items-center justify-center gap-1.5">
            <span>🔒</span> Pembayaran diproses secara aman oleh Midtrans
        </p>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.getElementById('pay-button').onclick = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = '{{ route('payment.check-status', $order->id) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('payment.check-status', $order->id) }}';
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function() {
                    alert('Kamu menutup popup pembayaran.');
                }
            });
        };
    </script>

</body>
</html>