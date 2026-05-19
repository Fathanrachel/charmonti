<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Sukses — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-linear-to-br from-amber-50 to-orange-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white/80 backdrop-blur-md border border-white/40 rounded-3xl shadow-xl p-8 md:p-12 max-w-lg w-full text-center relative overflow-hidden">
        {{-- Background decorative elements --}}
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-200/40 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-200/40 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            {{-- Success Icon Animation --}}
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full text-green-500 text-5xl mb-6 shadow-inner animate-bounce">
                ✓
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h2>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                Terima kasih! Pembayaran untuk pesanan <span class="font-semibold text-amber-600">#{{ $order->id }}</span> telah kami terima. Pesanan Anda kini sedang kami proses.
            </p>

            <div class="bg-amber-50/50 backdrop-blur-sm border border-amber-200/30 rounded-2xl p-6 text-left mb-8 space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Order ID</span>
                    <span class="font-semibold text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Tanggal Pembayaran</span>
                    <span class="font-semibold text-gray-800">{{ $order->payment?->payment_date ? \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d F Y H:i') : now()->translatedFormat('d F Y H:i') }} WIB</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Metode Pembayaran</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ $order->payment?->payment_type ?? 'Midtrans' }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600 pt-3 border-t border-dashed border-amber-200">
                    <span class="font-semibold text-gray-800">Total Pembayaran</span>
                    <span class="font-bold text-amber-600 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('customer.orders') }}"
                   class="block w-full bg-linear-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3.5 rounded-xl transition duration-300 shadow-md hover:shadow-lg text-center transform hover:-translate-y-0.5">
                    Lacak Pengiriman 🚚
                </a>

                <a href="/"
                   class="block w-full bg-white/60 hover:bg-white text-gray-700 font-semibold py-3.5 rounded-xl border border-gray-200 transition duration-300 text-center transform hover:-translate-y-0.5">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
