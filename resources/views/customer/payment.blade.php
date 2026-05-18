<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 min-h-screen">

    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-amber-500">Charm.onti</h1>
        <a href="/" class="text-sm text-gray-600 hover:text-amber-500">← Kembali</a>
    </nav>

    <div class="max-w-2xl mx-auto px-6 py-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Pembayaran</h2>

        {{-- Info Order --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-500 text-sm">Order #{{ $order->id }}</span>
                <span class="text-amber-500 font-bold text-lg">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </span>
            </div>

            <div class="border-t pt-3 space-y-2">
                @foreach($order->orderItems as $item)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                    <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tombol Bayar --}}
        <button id="pay-button"
            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-4 rounded-xl transition text-lg">
            Bayar Sekarang
        </button>

        <p class="text-center text-xs text-gray-400 mt-4">
            Pembayaran diproses secara aman oleh Midtrans
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