<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-linear-to-br from-[#FCFBF9] to-rose-50/50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white/90 backdrop-blur-md border border-white rounded-[2rem] shadow-sm p-10 max-w-md w-full text-center relative overflow-hidden">
        {{-- Background decorative elements --}}
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-rose-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-pink-200/30 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="text-6xl mb-6 animate-bounce">🎉</div>
            <h2 class="text-3xl font-bold text-gray-800 mb-3 tracking-tight">Pesanan Berhasil!</h2>
            <p class="text-gray-500 text-base mb-8 leading-relaxed font-light">
                Pesanan <span class="font-semibold text-rose-500">#{{ $order->id }}</span> kamu sudah kami terima.<br>
                Segera selesaikan pembayaran untuk memproses pesananmu.
            </p>

            <div class="bg-rose-50/40 backdrop-blur-sm border border-rose-100/50 rounded-3xl p-6 text-left mb-8">
                <div class="flex justify-between text-sm text-gray-500 mb-3">
                    <span class="font-light">Order ID</span>
                    <span class="font-semibold text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mb-3">
                    <span class="font-light">Status</span>
                    <span class="text-rose-500 font-medium bg-rose-100/50 px-2.5 py-0.5 rounded-full border border-rose-200/50 text-xs">Menunggu Pembayaran</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 pt-3 border-t border-dashed border-rose-200/60 mt-1">
                    <span class="font-semibold text-gray-800">Total</span>
                    <span class="font-bold text-rose-500 text-lg tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-3">
                <a href="{{ route('payment.show', $order->id) }}"
                    class="block w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-4 rounded-full transition duration-300 shadow-sm hover:shadow-md text-center transform hover:-translate-y-0.5 text-base flex justify-center items-center gap-2">
                        Bayar Sekarang 💳
                </a>

                <a href="/"
                   class="block w-full bg-transparent hover:bg-gray-50 text-gray-500 font-medium py-4 rounded-full transition duration-300 text-center text-sm">
                    Belanja Lagi
                </a>
            </div>
        </div>
    </div>

</body>
</html>