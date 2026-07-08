<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan Kamu — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center shadow-sm">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <div class="flex gap-5 text-sm font-medium items-center">
            <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-rose-500 transition p-2 rounded-full hover:bg-rose-50/50 flex items-center justify-center" title="Keranjang Belanja">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @php $cartCount = count(Session::get('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="absolute top-1 right-1 bg-rose-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center border border-white shadow-xs">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="/" class="text-gray-500 hover:text-rose-500 transition">Produk</a>

            @auth
                <!-- Dropdown Profile -->
                <div class="relative inline-block text-left" id="profile-dropdown-container">
                    <button type="button" id="dropdown-btn" class="flex items-center gap-1.5 text-gray-700 hover:text-rose-500 font-semibold focus:outline-none transition py-1">
                        <span>Halo, <span class="text-rose-400 font-bold">{{ Auth::user()->profile?->name }}</span></span>
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu Box -->
                    <div id="dropdown-menu" class="hidden absolute right-0 mt-2.5 w-48 rounded-2xl bg-white border border-gray-100 shadow-lg py-2 z-50 ring-1 ring-black/5 transition duration-300">
                        <a href="{{ route('customer.profile') }}" class="block px-5 py-2.5 text-sm text-gray-600 hover:bg-rose-50/50 hover:text-rose-500 font-medium transition">
                            👤 Profil Saya
                        </a>
                        <a href="{{ route('customer.orders') }}" class="block px-5 py-2.5 text-sm text-gray-600 hover:bg-rose-50/50 hover:text-rose-500 font-medium transition">
                            📦 Pesanan Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-5 py-2.5 text-sm text-red-500 hover:bg-red-50/50 font-semibold transition">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btn = document.getElementById('dropdown-btn');
                        const menu = document.getElementById('dropdown-menu');
                        
                        btn.addEventListener('click', function (e) {
                            e.stopPropagation();
                            menu.classList.toggle('hidden');
                        });

                        document.addEventListener('click', function () {
                            menu.classList.add('hidden');
                        });
                    });
                </script>
            @endauth
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="max-w-4xl mx-auto px-6 py-12">
        @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm">
                <span class="text-sm font-medium">✨ {{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 text-red-700 px-5 py-4 rounded-2xl mb-8 flex items-center justify-between shadow-sm">
                <span class="text-sm font-medium">⚠️ {{ session('error') }}</span>
            </div>
        @endif
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Pesanan Saya 🌸</h2>
                <p class="text-sm text-gray-500 mt-2 font-light">Pantau pembayaran dan status pengiriman paket cantikmu di sini</p>
            </div>
            <a href="/" class="text-sm text-rose-500 hover:text-rose-600 font-medium flex items-center gap-1.5 transition">
                <span>←</span> Belanja Lagi
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white border border-gray-100/50 rounded-3xl p-16 text-center shadow-sm">
                <div class="text-6xl mb-4 opacity-75">🛍️</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada pesanan</h3>
                <p class="text-gray-500 text-sm mb-8 font-light max-w-sm mx-auto">Kamu belum melakukan pemesanan apa pun. Yuk, buat gelang impianmu sekarang!</p>
                <a href="/" class="inline-block bg-rose-400 hover:bg-rose-500 text-white font-medium px-8 py-3.5 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5">
                    Cari Produk ✨
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-100/50 rounded-3xl shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                        {{-- Card Header --}}
                        <div class="bg-rose-50/30 px-8 py-5 border-b border-rose-50 flex flex-wrap justify-between items-center gap-2">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-gray-600">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                            
                            {{-- Order Status Badge --}}
                            @php
                                $statusColors = [
                                    'pending' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'diproses' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'selesai' => 'bg-green-50 text-green-600 border-green-100',
                                    'batal' => 'bg-red-50 text-red-600 border-red-100',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'batal' => 'Dibatalkan',
                                ];
                                $orderStatus = $order->status;
                            @endphp
                            <span class="text-xs font-medium px-4 py-1.5 rounded-full border {{ $statusColors[$orderStatus] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $statusLabels[$orderStatus] ?? $orderStatus }}
                            </span>
                        </div>

                        @if($orderStatus === 'pending' && ($order->payment?->payment_status ?? 'pending') === 'pending')
                            <div class="bg-rose-50/60 px-8 py-3 border-b border-rose-100/50 flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center text-xs text-rose-700 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                                    </span>
                                    <span>Selesaikan pembayaran Anda segera sebelum batas waktu berakhir:</span>
                                </div>
                                <div class="bg-white border border-rose-200 px-4 py-1.5 rounded-full flex items-center gap-2 font-bold font-mono text-rose-600 self-end sm:self-auto shadow-sm">
                                    <span>⏳</span>
                                    <span class="countdown-timer" data-expiry="{{ \Carbon\Carbon::parse($order->order_date)->addDay()->toIso8601String() }}">--:--:--</span>
                                </div>
                            </div>
                        @endif

                        {{-- Card Body --}}
                        <div class="p-8">
                            {{-- Items --}}
                            <div class="space-y-5 mb-8">
                                {{-- 1. Regular Product Items --}}
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center gap-5">
                                        <div class="bg-rose-50/50 rounded-2xl h-16 w-16 flex items-center justify-center shrink-0 border border-rose-50">
                                            @if($item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->product_name }}" class="w-full h-full object-cover rounded-2xl">
                                            @else
                                                <span class="text-2xl text-rose-300">📿</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $item->product->product_name }}</h4>
                                            <p class="text-xs text-gray-500 mt-1 font-light">Jumlah: {{ $item->qty }}x &bull; Satuan: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-bold text-gray-800 text-sm">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- 2. Custom Gelang Order --}}
                                @if($order->customBahanOrder)
                                    <div class="bg-rose-50/20 border border-rose-100/50 rounded-2xl p-5">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="bg-rose-100 rounded-xl h-12 w-12 flex items-center justify-center text-2xl shrink-0">
                                                ✨
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm">Gelang Custom (Warna: {{ ucfirst($order->customBahanOrder->warna) }})</h4>
                                                @if($order->customBahanOrder->request_note)
                                                    <p class="text-xs text-gray-500 mt-0.5 italic">Catatan: "{{ $order->customBahanOrder->request_note }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- Daftar Bahan/Charm --}}
                                        <div class="border-t border-rose-100/40 pt-3">
                                            <p class="text-xs font-semibold text-gray-400 mb-2.5 uppercase tracking-wide">Bahan / Charm yang Digunakan:</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                                @foreach($order->customBahanOrder->customBahanOrderItems as $customItem)
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-lg bg-white border border-rose-50 flex items-center justify-center shrink-0">
                                                            @if($customItem->bahan->image)
                                                                <img src="{{ Storage::url($customItem->bahan->image) }}" alt="{{ $customItem->bahan->nama_bahan }}" class="h-full w-full object-cover rounded-lg">
                                                            @else
                                                                <span class="text-sm">💎</span>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-xs font-medium text-gray-700 truncate">{{ $customItem->bahan->nama_bahan }}</p>
                                                            <p class="text-[10px] text-gray-400 font-light">Rp {{ number_format($customItem->bahan->price, 0, ',', '.') }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr class="border-gray-100 my-6">

                            {{-- Payment and Shipping Details Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                {{-- Payment Column --}}
                                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5">
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pembayaran</h5>
                                    <div class="space-y-3 text-sm text-gray-600">
                                        <div class="flex justify-between">
                                            <span class="font-light">Metode:</span>
                                            <span class="font-semibold text-gray-800 capitalize">{{ $order->payment_method ?? 'Midtrans' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="font-light">Status:</span>
                                            @php
                                                $payStatusColors = [
                                                    'pending' => 'text-rose-500 bg-rose-50 border-rose-200',
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
                                            <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $payStatusColors[$payStatus] }}">
                                                {{ $payStatusLabels[$payStatus] }}
                                            </span>
                                        </div>
                                        @if($order->payment?->payment_date)
                                        <div class="flex justify-between">
                                            <span class="font-light">Tanggal Bayar:</span>
                                            <span class="font-medium text-gray-800 text-xs">{{ \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d M Y H:i') }} WIB</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Shipping Column --}}
                                <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-5">
                                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pengiriman</h5>
                                    @if($order->shipping)
                                        <div class="space-y-3 text-sm text-gray-600">
                                            <div class="flex justify-between">
                                                <span class="font-light">Kurir:</span>
                                                <span class="font-semibold text-gray-800 capitalize">{{ $order->shipping->expedition?->name_expedition ?: '-' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-light">No. Resi:</span>
                                                <span class="font-semibold text-rose-500 font-mono tracking-wide">{{ $order->shipping->tracking_number ?: 'Belum diisi oleh Kurir' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="font-light">Status:</span>
                                                @php
                                                    $shipColors = [
                                                        'pending' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                        'dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                        'sampai' => 'bg-green-50 text-green-600 border-green-100',
                                                    ];
                                                    $shipLabels = [
                                                        'pending' => 'Diproses',
                                                        'dikirim' => 'Dalam Pengiriman',
                                                        'sampai' => 'Diterima',
                                                    ];
                                                    $shipStatus = $order->shipping->status;
                                                @endphp
                                                <span class="text-xs font-medium px-3 py-1 rounded-full border {{ $shipColors[$shipStatus] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                                    {{ $shipLabels[$shipStatus] ?? $shipStatus }}
                                                </span>
                                            </div>
                                            @if($order->shipping->estimated_arrival)
                                            <div class="flex justify-between">
                                                <span class="font-light">Estimasi Tiba:</span>
                                                <span class="font-medium text-gray-800 text-xs">{{ \Carbon\Carbon::parse($order->shipping->estimated_arrival)->translatedFormat('d M Y') }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center h-full">
                                            <p class="text-xs text-gray-400 font-light italic">
                                                @if($payStatus === 'paid')
                                                    Menunggu kurir ditunjuk...
                                                @else
                                                    Selesaikan pembayaran terlebih dahulu.
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($order->complaints->isNotEmpty())
                                <div class="bg-red-50/50 border border-red-100 rounded-2xl p-5 mb-6">
                                    <h6 class="text-xs font-bold text-red-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <span>⚠️</span> Riwayat Komplain Pesanan:
                                    </h6>
                                    <div class="space-y-4">
                                        @foreach($order->complaints as $complaint)
                                            <div class="flex justify-between items-start gap-4 text-xs border-b border-red-100/50 last:border-0 pb-3 last:pb-0">
                                                <div class="text-gray-600">
                                                    <span class="font-semibold text-gray-800 capitalize bg-white px-2.5 py-1 rounded-md border border-red-100 mr-2 shadow-sm">{{ $complaint->category }}</span>
                                                    <span class="italic font-light leading-relaxed">"{{ $complaint->message }}"</span>
                                                </div>
                                                @php
                                                    $compColors = [
                                                        'open' => 'bg-red-50 text-red-600 border-red-200',
                                                        'diproses' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                        'selesai' => 'bg-green-50 text-green-600 border-green-200',
                                                    ];
                                                    $compLabels = [
                                                        'open' => 'Baru / Menunggu',
                                                        'diproses' => 'Diproses Toko',
                                                        'selesai' => 'Selesai / Teratasi',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full border text-[10px] font-bold shrink-0 {{ $compColors[$complaint->status] ?? 'bg-gray-50 text-gray-600' }}">
                                                    {{ $compLabels[$complaint->status] ?? $complaint->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Footer Summary / Action --}}
                            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-5 bg-rose-50/20 p-5 rounded-2xl border border-rose-50 mt-2">
                                <div>
                                    <span class="text-xs text-gray-400 block font-light uppercase tracking-wide mb-1">Total Transaksi</span>
                                    <span class="text-2xl font-bold text-rose-500 tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>

                                @if($orderStatus === 'pending' && $payStatus === 'pending')
                                    <div class="flex items-center gap-3">
                                        <form method="POST" action="{{ route('customer.order.cancel', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-white border border-gray-200 hover:bg-gray-50 hover:border-red-200 hover:text-red-500 text-gray-500 font-medium px-5 py-3 rounded-full text-center transition duration-300 text-sm flex items-center justify-center shadow-sm">
                                                Batalkan
                                            </button>
                                        </form>
                                        <a href="{{ route('payment.show', $order->id) }}"
                                           class="bg-rose-400 hover:bg-rose-500 text-white font-medium px-8 py-3 rounded-full text-center transition duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                                            Bayar Sekarang ✨
                                        </a>
                                    </div>
                                @elseif($orderStatus === 'pending' && $payStatus === 'paid')
                                    <span class="text-xs font-medium text-green-600 bg-green-50/80 border border-green-100 px-4 py-2 rounded-full self-start sm:self-center shadow-sm">
                                        Pembayaran Lunas, Menunggu Konfirmasi Toko
                                    </span>
                                @elseif($orderStatus === 'diproses')
                                    <div class="flex items-center gap-3">
                                        @if($payStatus === 'paid')
                                            <a href="{{ route('customer.order.complaint', $order->id) }}"
                                               class="bg-white border border-gray-200 hover:border-red-200 hover:text-red-500 text-gray-500 font-medium px-4 py-2.5 rounded-full text-xs transition shadow-sm">
                                                Ajukan Komplain
                                            </a>
                                        @endif
                                        <span class="text-xs font-medium text-blue-600 bg-blue-50/80 border border-blue-100 px-4 py-2.5 rounded-full self-start sm:self-center shadow-sm">
                                            Penjual sedang mempersiapkan pesananmu 💖
                                        </span>
                                    </div>
                                @elseif($orderStatus === 'selesai')
                                    @php
                                        $hasReviewed = \App\Models\Review::where('order_id', $order->id)->where('user_id', Auth::id())->exists();
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        @if($payStatus === 'paid')
                                            <a href="{{ route('customer.order.complaint', $order->id) }}"
                                               class="bg-white border border-gray-200 hover:border-red-200 hover:text-red-500 text-gray-500 font-medium px-4 py-2.5 rounded-full text-xs transition shadow-sm">
                                                Ajukan Komplain
                                            </a>
                                        @endif
                                        @if($hasReviewed)
                                            <span class="text-xs font-medium text-gray-500 bg-gray-100 border border-gray-200 px-5 py-2.5 rounded-full shadow-sm">
                                                Ulasan Terkirim ✓
                                            </span>
                                        @else
                                            <a href="{{ route('customer.order.review', $order->id) }}"
                                               class="bg-rose-400 hover:bg-rose-500 text-white font-medium px-6 py-2.5 rounded-full text-xs transition shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                                Beri Ulasan ⭐
                                            </a>
                                        @endif
                                    </div>
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
                        timer.closest('.bg-white').style.opacity = '0.6';
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
