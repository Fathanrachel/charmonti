<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Sukses — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-linear-to-br from-[#FCFBF9] to-rose-50/50 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white/90 backdrop-blur-md border border-white rounded-[2rem] shadow-sm p-10 md:p-14 max-w-lg w-full text-center relative overflow-hidden">
        {{-- Background decorative elements --}}
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-rose-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-pink-200/30 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            {{-- Success Icon Animation --}}
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-50 rounded-full text-green-500 text-5xl mb-8 shadow-sm border border-green-100 animate-bounce">
                ✓
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-3 tracking-tight">Pembayaran Berhasil! 🌸</h2>
            <p class="text-gray-500 text-base mb-8 leading-relaxed font-light">
                Terima kasih! Pembayaran untuk pesanan <span class="font-semibold text-rose-500">#{{ $order->id }}</span> telah kami terima. Pesanan Anda kini sedang kami proses dengan penuh cinta.
            </p>

            <div class="bg-rose-50/40 backdrop-blur-sm border border-rose-100/50 rounded-3xl p-7 text-left mb-10 space-y-4">
                <div class="flex justify-between text-sm text-gray-500">
                    <span class="font-light">Order ID</span>
                    <span class="font-semibold text-gray-800">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span class="font-light">Tanggal Pembayaran</span>
                    <span class="font-medium text-gray-800">{{ $order->payment?->payment_date ? \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d F Y H:i') : now()->translatedFormat('d F Y H:i') }} WIB</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span class="font-light">Metode Pembayaran</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ $order->payment?->payment_type ?? 'Midtrans' }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 pt-4 border-t border-dashed border-rose-200/60 mt-2">
                    <span class="font-semibold text-gray-800">Total Pembayaran</span>
                    <span class="font-bold text-rose-500 text-xl tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('customer.orders') }}"
                   class="block w-full bg-rose-400 hover:bg-rose-500 text-white font-medium py-4 rounded-full transition duration-300 shadow-sm hover:shadow-md text-center transform hover:-translate-y-0.5 text-base">
                    Lacak Pengiriman 🚚
                </a>

                <a href="/"
                   class="block w-full bg-transparent hover:bg-gray-50 text-gray-500 font-medium py-4 rounded-full transition duration-300 text-center text-sm">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</body>
</html>
