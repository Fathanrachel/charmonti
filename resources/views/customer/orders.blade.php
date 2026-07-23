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
                                    @if($item->product?->product_name === 'Gelang Custom')
                                        @continue
                                    @endif
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
                                    @php
                                        $customPrice = 20000; // Base price tali strap
                                        foreach($order->customBahanOrder->customBahanOrderItems as $customItem) {
                                            $customPrice += ($customItem->bahan->price ?? 0) * ($customItem->qty ?? 1);
                                        }
                                        $strapColor = ucfirst(trim($order->customBahanOrder->warna));
                                        $strapBahan = \App\Models\Bahan::where('nama_bahan', 'Tali Gelang ' . $strapColor)->first();
                                    @endphp
                                    <div class="bg-rose-50/20 border border-rose-100/50 rounded-2xl p-5">
                                        <div class="flex items-center justify-between gap-4 mb-4">
                                            <div class="flex items-center gap-4">
                                                <div class="bg-rose-50/80 rounded-xl h-14 w-14 flex items-center justify-center shrink-0 border border-rose-100/50 overflow-hidden">
                                                    @if($strapBahan && $strapBahan->image)
                                                        <img src="{{ Storage::url($strapBahan->image) }}" alt="Tali Gelang {{ $strapColor }}" class="w-full h-full object-cover rounded-xl">
                                                    @else
                                                        <span class="text-2xl">✨</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-800 text-sm">Gelang Custom (Warna: {{ $strapColor }})</h4>
                                                    @if($order->customBahanOrder->request_note)
                                                        <p class="text-xs text-gray-500 mt-0.5 italic">Catatan: "{{ $order->customBahanOrder->request_note }}"</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <span class="font-bold text-gray-800 text-sm">Rp {{ number_format($customPrice, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Daftar Bahan/Charm --}}
                                        <div class="border-t border-rose-100/40 pt-3">
                                            <p class="text-xs font-semibold text-gray-400 mb-2.5 uppercase tracking-wide">Bahan / Charm yang Digunakan:</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                                @php
                                                    $groupedCharms = $order->customBahanOrder->customBahanOrderItems->groupBy('bahan_id');
                                                @endphp
                                                @foreach($groupedCharms as $bahanId => $items)
                                                    @php
                                                        $firstItem = $items->first();
                                                        $totalQty = $items->sum('qty');
                                                    @endphp
                                                    <div class="flex items-center gap-3">
                                                        <div class="h-8 w-8 rounded-lg bg-white border border-rose-50 flex items-center justify-center shrink-0">
                                                            @if($firstItem->bahan->image)
                                                                <img src="{{ Storage::url($firstItem->bahan->image) }}" alt="{{ $firstItem->bahan->nama_bahan }}" class="h-full w-full object-cover rounded-lg">
                                                            @else
                                                                <span class="text-sm">💎</span>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-xs font-semibold text-gray-700 truncate">
                                                                {{ $firstItem->bahan->nama_bahan }}
                                                                <span class="ml-1 text-[10px] text-rose-500 font-bold bg-rose-50 px-1.5 py-0.5 rounded-md border border-rose-100/50">
                                                                    {{ $totalQty }}x
                                                                </span>
                                                            </p>
                                                            <p class="text-[10px] text-gray-400 font-light">Rp {{ number_format($firstItem->bahan->price, 0, ',', '.') }} /pcs</p>
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
                                                <span class="font-light">Ongkos Kirim:</span>
                                                <span class="font-semibold text-rose-500">Rp {{ number_format($order->shipping->shipping_cost, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-light">No. Resi:</span>
                                                @if($order->shipping->status === 'batal' || $order->status === 'batal')
                                                    <span class="font-semibold text-gray-400 font-mono">-</span>
                                                @else
                                                    @if($order->shipping->tracking_number)
                                                        <span class="font-semibold text-gray-800 font-mono tracking-wide">{{ $order->shipping->tracking_number }}</span>
                                                    @else
                                                        <span class="font-medium text-rose-400 italic text-xs">Menunggu proses input</span>
                                                    @endif
                                                @endif
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
                                        <span>⚠️</span> Riwayat Obrolan Keluhan:
                                    </h6>
                                    <div class="space-y-5">
                                        @foreach($order->complaints as $complaint)
                                            <div class="border-b border-red-100/50 last:border-0 pb-5 last:pb-0 space-y-4">
                                                <div class="flex justify-between items-center bg-white/60 p-2.5 rounded-xl border border-red-100/30">
                                                    <span class="font-bold text-gray-800 text-xs capitalize">Kategori: {{ $complaint->category }}</span>
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
                                                    <span class="px-3 py-1 rounded-full border text-[10px] font-bold {{ $compColors[$complaint->status] ?? 'bg-gray-50 text-gray-600' }}">
                                                        {{ $compLabels[$complaint->status] ?? $complaint->status }}
                                                    </span>
                                                </div>

                                                {{-- Chat Thread list --}}
                                                <div class="space-y-3.5 pl-2">
                                                    @php
                                                        // Split by double newline to parse separate messages
                                                        $chatLines = explode("\n\n", $complaint->message);
                                                    @endphp
                                                    @foreach($chatLines as $index => $line)
                                                        @if(trim($line))
                                                            @php
                                                                $isUserMessage = !str_starts_with(trim($line), '[Admin]') && !str_contains($line, '- Owner') && !str_contains($line, '- Admin');
                                                            @endphp
                                                            <div class="flex {{ $isUserMessage ? 'justify-end' : 'justify-start' }}">
                                                                <div class="rounded-2xl px-4 py-2.5 max-w-[85%] text-xs shadow-xs border {{ $isUserMessage ? 'bg-white border-red-100/60 rounded-br-none text-gray-800' : 'bg-gray-100 border-gray-200 rounded-bl-none text-gray-700' }}">
                                                                    <p class="leading-relaxed whitespace-pre-line">{{ $line }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                
                                                {{-- Store Reply (Tanggapan Toko - main reply field) --}}
                                                @if($complaint->reply_message)
                                                    <div class="ml-2 bg-rose-50/70 border border-rose-100/40 rounded-2xl p-4 text-xs text-gray-700 relative">
                                                        <div class="absolute -top-1.5 left-6 w-3 h-3 bg-rose-50 border-t border-l border-rose-100/40 transform rotate-45"></div>
                                                        <div class="flex items-center gap-1.5 font-bold text-rose-500 mb-1">
                                                            <span>💬</span> Tanggapan Toko:
                                                        </div>
                                                        <p class="font-light leading-relaxed">{{ $complaint->reply_message }}</p>
                                                    </div>
                                                @endif

                                                {{-- Quick Reply Form for customer --}}
                                                @if($complaint->status !== 'selesai')
                                                    <div class="pt-2 pl-2">
                                                        <button type="button" onclick="toggleReplyForm({{ $complaint->id }})" 
                                                                class="text-[11px] font-semibold text-rose-500 hover:text-rose-600 transition flex items-center gap-1 hover:underline">
                                                            <span>💬</span> Kirim Balasan Baru
                                                        </button>
                                                        
                                                        <form id="reply-form-{{ $complaint->id }}" method="POST" action="{{ route('customer.complaint.reply', $complaint->id) }}" class="hidden mt-3 space-y-2.5">
                                                            @csrf
                                                            <textarea name="reply_message" rows="2" required 
                                                                      placeholder="Tulis balasan atau penjelasan tambahan di sini..." 
                                                                      class="w-full bg-white border border-red-100/60 rounded-2xl p-3 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-200 placeholder-gray-400 transition shadow-xs"></textarea>
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" onclick="toggleReplyForm({{ $complaint->id }})" 
                                                                        class="bg-white border border-gray-200 text-gray-400 font-semibold px-4 py-1.5 rounded-full text-[10px] transition">
                                                                    Batal
                                                                </button>
                                                                <button type="submit" 
                                                                        class="bg-rose-400 hover:bg-rose-500 text-white font-semibold px-4 py-1.5 rounded-full text-[10px] transition shadow-xs">
                                                                    Kirim Balasan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif
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
                                        <form method="POST" action="{{ route('customer.order.cancel', $order->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" onclick="openCancelModal(event, this.closest('form'))"
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
                                    <div class="flex items-center gap-3">
                                        @if($order->shipping?->status === 'dikirim')
                                            <form method="POST" action="{{ route('customer.order.confirm-received', $order->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="openConfirmReceivedModal(event, this.closest('form'))"
                                                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-2.5 rounded-full text-xs transition shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-1.5">
                                                    <span>Pesanan Diterima ✅</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs font-medium text-green-600 bg-green-50/80 border border-green-100 px-4 py-2.5 rounded-full self-start sm:self-center shadow-sm">
                                                Pembayaran Lunas, Menunggu Konfirmasi Toko
                                            </span>
                                        @endif
                                    </div>
                                @elseif($orderStatus === 'diproses')
                                    <div class="flex items-center gap-3">
                                        @if($order->shipping?->status === 'dikirim')
                                            <form method="POST" action="{{ route('customer.order.confirm-received', $order->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="openConfirmReceivedModal(event, this.closest('form'))"
                                                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-2.5 rounded-full text-xs transition shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-1.5">
                                                    <span>Pesanan Diterima ✅</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs font-medium text-blue-600 bg-blue-50/80 border border-blue-100 px-4 py-2.5 rounded-full self-start sm:self-center shadow-sm">
                                                Penjual sedang mempersiapkan pesananmu 💖
                                            </span>
                                        @endif
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
                                        @if($order->shipping?->status === 'dikirim')
                                            <form method="POST" action="{{ route('customer.order.confirm-received', $order->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" onclick="openConfirmReceivedModal(event, this.closest('form'))"
                                                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-2.5 rounded-full text-xs transition shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-1.5">
                                                    <span>Pesanan Diterima ✅</span>
                                                </button>
                                            </form>
                                        @elseif($hasReviewed)
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

        let activeCancelForm = null;

        function openCancelModal(event, form) {
            event.preventDefault();
            activeCancelForm = form;
            
            const modal = document.getElementById('cancel-modal');
            const content = document.getElementById('cancel-modal-content');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancel-modal');
            const content = document.getElementById('cancel-modal-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                activeCancelForm = null;
            }, 300);
        }

        let activeConfirmReceivedForm = null;

        function openConfirmReceivedModal(event, form) {
            event.preventDefault();
            activeConfirmReceivedForm = form;
            
            const modal = document.getElementById('confirm-received-modal');
            const content = document.getElementById('confirm-received-modal-content');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeConfirmReceivedModal() {
            const modal = document.getElementById('confirm-received-modal');
            const content = document.getElementById('confirm-received-modal-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                activeConfirmReceivedForm = null;
            }, 300);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cancelBtn = document.getElementById('confirm-cancel-btn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (activeCancelForm) {
                        activeCancelForm.submit();
                    }
                });
            }

            const confirmReceivedBtn = document.getElementById('confirm-received-btn');
            if (confirmReceivedBtn) {
                confirmReceivedBtn.addEventListener('click', function() {
                    if (activeConfirmReceivedForm) {
                        activeConfirmReceivedForm.submit();
                    }
                });
            }
        });

        function toggleReplyForm(complaintId) {
            const form = document.getElementById(`reply-form-${complaintId}`);
            if (form) {
                form.classList.toggle('hidden');
                if (!form.classList.contains('hidden')) {
                    form.querySelector('textarea').focus();
                }
            }
        }
    </script>

    <!-- Custom Cancel Modal -->
    <div id="cancel-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop overlay -->
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity duration-300" onclick="closeCancelModal()"></div>
        
        <!-- Modal Content Box -->
        <div class="bg-white/95 backdrop-blur-md rounded-[2rem] border border-gray-100 p-8 max-w-sm w-[90%] relative z-10 shadow-[0_20px_50px_rgba(244,114,182,0.12)] transform scale-95 opacity-0 transition-all duration-300 ease-out" id="cancel-modal-content">
            <!-- Icon Warning -->
            <div class="flex justify-center mb-5">
                <div class="bg-rose-50 h-14 w-14 rounded-full flex items-center justify-center text-rose-500 border border-rose-100/50 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 text-center mb-2 tracking-tight">Batalkan Pesanan?</h3>
            <p class="text-sm text-gray-500 font-light text-center mb-6 leading-relaxed">Apakah Anda yakin ingin membatalkan pesanan ini? Stok barang akan otomatis dikembalikan ke gudang.</p>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeCancelModal()"
                    class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 font-semibold py-3 rounded-full text-center transition text-sm">
                    Kembali
                </button>
                <button type="button" id="confirm-cancel-btn"
                    class="flex-1 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-full text-center transition duration-300 shadow-[0_4px_15px_rgba(244,114,182,0.2)] text-sm">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Confirm Received Modal -->
    <div id="confirm-received-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop overlay -->
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-xs transition-opacity duration-300" onclick="closeConfirmReceivedModal()"></div>
        
        <!-- Modal Content Box -->
        <div class="bg-white/95 backdrop-blur-md rounded-[2rem] border border-emerald-100 p-8 max-w-sm w-[90%] relative z-10 shadow-[0_20px_50px_rgba(16,185,129,0.15)] transform scale-95 opacity-0 transition-all duration-300 ease-out" id="confirm-received-modal-content">
            <!-- Icon Check -->
            <div class="flex justify-center mb-5">
                <div class="bg-emerald-50 h-16 w-16 rounded-full flex items-center justify-center text-emerald-500 border border-emerald-100/60 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 text-center mb-2 tracking-tight">Pesanan Sudah Diterima? 📦</h3>
            <p class="text-sm text-gray-500 font-light text-center mb-6 leading-relaxed">Apakah Anda yakin pesanan ini sudah Anda terima dengan baik? Setelah dikonfirmasi, Anda dapat memberikan ulasan cantikmu!</p>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmReceivedModal()"
                    class="flex-1 bg-white border border-gray-200 hover:bg-gray-50 text-gray-500 font-semibold py-3 rounded-full text-center transition text-sm">
                    Batal
                </button>
                <button type="button" id="confirm-received-btn"
                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-full text-center transition duration-300 shadow-[0_4px_15px_rgba(16,185,129,0.3)] text-sm">
                    Ya, Diterima! 🎉
                </button>
            </div>
        </div>
    </div>

</body>
</html>
