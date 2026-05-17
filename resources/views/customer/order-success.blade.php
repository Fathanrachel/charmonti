<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-amber-50 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-lg p-10 max-w-md w-full text-center">
        <div class="text-6xl mb-4">🎉</div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h2>
        <p class="text-gray-500 text-sm mb-4">
            Pesanan <strong>#{{ $order->id }}</strong> kamu sudah kami terima.<br>
            Kami akan segera memproses pesananmu.
        </p>

        <div class="bg-amber-50 rounded-xl p-4 text-sm text-left mb-6">
            <div class="flex justify-between text-gray-600 mb-1">
                <span>Order ID</span>
                <span class="font-semibold">#{{ $order->id }}</span>
            </div>
            <div class="flex justify-between text-gray-600 mb-1">
                <span>Status</span>
                <span class="text-amber-500 font-semibold">Pending</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Total</span>
                <span class="font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('payment.show', $order->id) }}"
            class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition mb-3 text-center">
                Bayar Sekarang 💳
        </a>

        <a href="/"
           class="block w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition">
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>