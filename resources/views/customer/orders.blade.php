<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan Kamu — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50/60 to-orange-100/30 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center shadow-sm">
        <a href="/" class="flex items-center gap-2.5 hover:opacity-95 transition">
            <img src="{{ asset('logo.jpg') }}" alt="CharmOnTi Logo" class="h-9 w-9 rounded-full object-cover border border-amber-200/50 shadow-sm">
            <span class="text-2xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent font-bold">CharmOnTi</span>
        </a>
        <div class="flex gap-6 text-sm font-medium items-center">
            <a href="/" class="text-gray-600 hover:text-amber-500 transition">Produk</a>
            <a href="{{ route('customer.orders') }}" class="text-amber-500 border-b-2 border-amber-500 pb-1">Pesanan Saya</a>
            @auth
                <span class="text-gray-500 font-normal">Halo, <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span></span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-400 hover:text-red-600 font-semibold transition">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto px-4 py-10">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6 flex items-center justify-between shadow-sm">
                <span class="text-sm font-medium">✨ {{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6 flex items-center justify-between shadow-sm">
                <span class="text-sm font-medium">⚠️ {{ session('error') }}</span>
            </div>
        @endif
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Pesanan Saya</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau pembayaran dan status pengiriman paketmu di sini</p>
            </div>
            <a href="/" class="text-sm text-amber-600 hover:text-amber-700 font-semibold flex items-center gap-1.5 transition">
                <span>←</span> Belanja Lagi
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center shadow-sm">
                <div class="text-6xl mb-4">🛍️</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada pesanan</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Kamu belum melakukan pemesanan apa pun. Yuk, buat gelang impianmu sekarang!</p>
                <a href="/" class="inline-block bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow-md">
                    Cari Produk ✨
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                        {{-- Card Header --}}
                        <div class="bg-amber-50/40 px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-gray-600">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                            
                            {{-- Order Status Badge --}}
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'selesai' => 'bg-green-100 text-green-700 border-green-200',
                                    'batal' => 'bg-red-100 text-red-700 border-red-200',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'batal' => 'Dibatalkan',
                                ];
                                $orderStatus = $order->status;
                            @endphp
                            <span class="text-xs font-semibold px-3 py-1 rounded-full border {{ $statusColors[$orderStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$orderStatus] ?? $orderStatus }}
                            </span>
                        </div>

                        @if($orderStatus === 'pending' && ($order->payment?->payment_status ?? 'pending') === 'pending')
                            <div class="bg-amber-50/60 px-6 py-2.5 border-b border-amber-100/50 flex flex-col sm:flex-row gap-2 justify-between items-start sm:items-center text-xs text-amber-700 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                    <span>Selesaikan pembayaran Anda segera sebelum batas waktu berakhir:</span>
                                </div>
                                <div class="bg-amber-100/70 border border-amber-200/50 px-3 py-1 rounded-xl flex items-center gap-1.5 font-bold font-mono text-amber-800 self-end sm:self-auto shadow-sm">
                                    <span>⏳</span>
                                    <span class="countdown-timer" data-expiry="{{ \Carbon\Carbon::parse($order->order_date)->addDay()->toIso8601String() }}">--:--:--</span>
                                </div>
                            </div>
                        @endif

                        {{-- Card Body --}}
                        <div class="p-6">
                            {{-- Items --}}
                            <div class="space-y-4 mb-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="bg-amber-50 rounded-xl h-14 w-14 flex items-center justify-center shrink-0 border border-amber-100">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-xl">
                                            @else
                                                <span class="text-2xl">📿</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-gray-400 mt-0.5">Jumlah: {{ $item->quantity }}x • Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-semibold text-gray-700 text-sm">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="border-gray-100 my-4">

                            {{-- Payment and Shipping Details Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                {{-- Payment Column --}}
                                <div class="bg-amber-50/20 border border-amber-200/20 rounded-2xl p-4">
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pembayaran</h5>
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex justify-between">
                                            <span>Metode:</span>
                                            <span class="font-semibold text-gray-800 capitalize">{{ $order->payment_method ?? 'Midtrans' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span>Status:</span>
                                            @php
                                                $payStatusColors = [
                                                    'pending' => 'text-amber-500 bg-amber-50 border-amber-200',
                                                    'paid' => 'text-green-600 bg-green-50 border-green-200',
                                                    'failed' => 'text-red-500 bg-red-50 border-red-200',
                                                ];
                                                $payStatusLabels = [
                                                    'pending' => 'Belum Bayar',
                                                    'paid' => 'Lunas',
                                                    'failed' => 'Gagal',
                                                ];
                                                $payStatus = $order->payment?->payment_status ?? 'pending';
                                            @endphp
                                            <span class="text-xs font-bold px-2 py-0.5 rounded border {{ $payStatusColors[$payStatus] }}">
                                                {{ $payStatusLabels[$payStatus] }}
                                            </span>
                                        </div>
                                        @if($order->payment?->payment_date)
                                        <div class="flex justify-between">
                                            <span>Tanggal Bayar:</span>
                                            <span class="font-semibold text-gray-800 text-xs">{{ \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d M Y H:i') }} WIB</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Shipping Column --}}
                                <div class="bg-orange-50/15 border border-orange-200/20 rounded-2xl p-4">
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Informasi Pengiriman</h5>
                                    @if($order->shipping)
                                        <div class="space-y-2 text-sm text-gray-600">
                                            <div class="flex justify-between">
                                                <span>Kurir:</span>
                                                <span class="font-semibold text-gray-800 capitalize">{{ $order->shipping->courier ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>No. Resi:</span>
                                                <span class="font-semibold text-amber-600 font-mono">{{ $order->shipping->tracking_number ?: 'Belum diisi oleh Kurir' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span>Status:</span>
                                                @php
                                                    $shipColors = [
                                                        'pending' => 'bg-amber-100 text-amber-700',
                                                        'dikirim' => 'bg-blue-100 text-blue-700',
                                                        'sampai' => 'bg-green-100 text-green-700',
                                                    ];
                                                    $shipLabels = [
                                                        'pending' => 'Diproses',
                                                        'dikirim' => 'Dalam Pengiriman',
                                                        'sampai' => 'Diterima',
                                                    ];
                                                    $shipStatus = $order->shipping->status;
                                                @endphp
                                                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $shipColors[$shipStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ $shipLabels[$shipStatus] ?? $shipStatus }}
                                                </span>
                                            </div>
                                            @if($order->shipping->estimated_arrival)
                                            <div class="flex justify-between">
                                                <span>Estimasi Tiba:</span>
                                                <span class="font-semibold text-gray-800 text-xs">{{ \Carbon\Carbon::parse($order->shipping->estimated_arrival)->translatedFormat('d M Y') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic py-2">
                                            @if($payStatus === 'paid')
                                                Menunggu kurir ditunjuk...
                                            @else
                                                Selesaikan pembayaran terlebih dahulu.
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Summary / Action --}}
                            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                <div>
                                    <span class="text-xs text-gray-400 block">Total Transaksi</span>
                                    <span class="text-xl font-bold text-amber-500">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>

                                @if($orderStatus === 'pending' && $payStatus === 'pending')
                                    <div class="flex items-center gap-3">
                                        <form method="POST" action="{{ route('customer.order.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="border border-red-200 hover:bg-red-50 text-red-500 font-semibold px-5 py-2.5 rounded-xl text-center transition text-sm flex items-center justify-center gap-1.5">
                                                Batalkan Pesanan ❌
                                            </button>
                                        </form>
                                        <a href="{{ route('payment.show', $order->id) }}"
                                           class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl text-center transition shadow-sm hover:shadow transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-1.5">
                                            Bayar Sekarang 💳
                                        </a>
                                    </div>
                                @elseif($orderStatus === 'pending' && $payStatus === 'paid')
                                    <span class="text-xs font-medium text-green-600 bg-green-50 border border-green-100 px-3 py-1.5 rounded-xl self-start sm:self-center">
                                        Pembayaran Lunas, Menunggu Konfirmasi Toko
                                    </span>
                                @elseif($orderStatus === 'diproses')
                                    <span class="text-xs font-medium text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1.5 rounded-xl self-start sm:self-center">
                                        Penjual sedang mempersiapkan gelang unikmu 💖
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timers = document.querySelectorAll('.countdown-timer');
            
            function updateTimers() {
                timers.forEach(timer => {
                    const expiryDate = new Date(timer.dataset.expiry).getTime();
                    const now = new Date().getTime();
                    const distance = expiryDate - now;

                    if (distance < 0) {
                        timer.innerHTML = "Kadaluarsa";
                        timer.closest('.bg-white').style.opacity = '0.7';
                        // Refresh halaman secara otomatis setelah waktu habis untuk memicu pembatalan otomatis di backend
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const formattedHours = String(hours).padStart(2, '0');
                    const formattedMinutes = String(minutes).padStart(2, '0');
                    const formattedSeconds = String(seconds).padStart(2, '0');

                    timer.innerHTML = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;
                });
            }

            updateTimers();
            setInterval(updateTimers, 1000);
        });
    </script>

</body>
</html>
