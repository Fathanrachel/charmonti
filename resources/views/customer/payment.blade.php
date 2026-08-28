<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-xs px-6 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-gray-100">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-xs">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="{{ route('customer.orders') }}" class="text-sm font-semibold text-gray-500 hover:text-rose-500 transition flex items-center gap-1">
            ← Batal & Kembali
        </a>
    </nav>

    <div class="max-w-2xl mx-auto px-6 py-10 flex-1 flex flex-col justify-center w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">Selesaikan Pembayaran ✨</h2>
            <p class="text-gray-500 mt-2 text-sm md:text-base font-light">Satu langkah lagi untuk memiliki gelang cantik impianmu 🌸</p>
        </div>

        {{-- Info Order Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6">
            <div class="flex justify-between items-center pb-5 border-b border-gray-100">
                <div>
                    <span class="text-gray-900 font-bold text-lg block">Rincian Pesanan</span>
                    <span class="text-xs text-gray-400">Order ID: #{{ $order->id }}</span>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400 block font-medium">Total Pembayaran</span>
                    <span class="text-rose-500 font-extrabold text-xl md:text-2xl tracking-tight">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- List Order Items --}}
            <div class="py-4 space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center justify-between gap-4 py-2 border-b border-gray-50 last:border-none">
                    <div class="flex items-center gap-4 min-w-0 flex-1">
                        <div class="bg-rose-50/60 rounded-2xl h-14 w-14 flex items-center justify-center shrink-0 border border-rose-100/50 overflow-hidden shadow-2xs">
                            @if($item->product->product_name === 'Gelang Custom' && $order->customBahanOrder)
                                @php
                                    $isNoStrap = ($order->customBahanOrder->warna ?? '') === 'tanpa_strap';
                                    $strapColor = $isNoStrap ? 'Tanpa Strap' : ucfirst(trim($order->customBahanOrder->warna ?? ''));
                                    $colorSearch = strtolower(trim($order->customBahanOrder->warna ?? ''));
                                    $strapBahan = !$isNoStrap ? \App\Models\Bahan::whereRaw('LOWER(nama_bahan) LIKE ?', ['%' . $colorSearch . '%'])->first() : null;
                                    $firstCharmItem = $order->customBahanOrder->customBahanOrderItems->first();
                                    $displayImage = $strapBahan?->image ?? $firstCharmItem?->bahan?->image;
                                @endphp
                                @if($displayImage)
                                    <img src="{{ Storage::url($displayImage) }}" alt="{{ $isNoStrap ? 'Charm Custom' : 'Tali Gelang ' . $strapColor }}" class="w-full h-full object-cover rounded-2xl">
                                @else
                                    <span class="text-2xl text-rose-400">💎</span>
                                @endif
                            @elseif($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->product_name }}" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <span class="text-2xl text-rose-400">📿</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            @if($item->product->product_name === 'Gelang Custom' && $order->customBahanOrder)
                                <p class="text-sm font-bold text-gray-800 leading-snug">Gelang Custom ({{ $isNoStrap ? 'Tanpa Strap' : 'Warna: ' . $strapColor }})</p>
                            @else
                                <p class="text-sm font-bold text-gray-800 leading-snug">{{ $item->product->product_name }}</p>
                            @endif
                            <p class="text-xs text-rose-500 font-semibold mt-0.5 bg-rose-50 inline-block px-2 py-0.5 rounded-md">{{ $item->qty }}x</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold text-gray-800 block">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                        @if($item->qty > 1)
                            <span class="text-[11px] text-gray-400">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            {{-- Ongkos Kirim --}}
            @if($order->shipping)
            <div class="pt-4 border-t border-dashed border-rose-100 flex justify-between items-center text-sm">
                <span class="text-gray-500 font-medium flex items-center gap-1.5">
                    <span>🚚</span> Ongkos Kirim ({{ $order->shipping->expedition?->name_expedition ?? 'Ekspedisi' }})
                </span>
                <span class="font-bold text-gray-800">Rp {{ number_format($order->shipping->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        {{-- Tombol Bayar --}}
        <div class="space-y-3">
            <button id="pay-button"
                class="w-full bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-bold py-4 rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-300 text-base md:text-lg flex justify-center items-center gap-2">
                <span>Bayar Sekarang 💳</span>
            </button>
            
            <a href="{{ route('payment.check-status', $order->id) }}" id="check-status-btn"
                class="hidden w-full bg-white hover:bg-rose-50 text-rose-500 border border-rose-200 font-semibold py-3.5 rounded-full shadow-xs hover:shadow-sm hover:-translate-y-0.5 transition duration-300 text-sm flex justify-center items-center gap-2">
                <span>Saya Sudah Bayar (Cek Status) 🔄</span>
            </a>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6 font-medium flex items-center justify-center gap-1.5">
            <span>🔒</span> Pembayaran diproses secara aman oleh Midtrans
        </p>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        function showToast(message, type = 'info') {
            const existingToast = document.getElementById('custom-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.id = 'custom-toast';
            toast.className = `fixed top-6 right-6 z-50 flex items-center gap-3 bg-white/95 backdrop-blur-md rounded-2xl px-6 py-4 border shadow-xl transform translate-y-2 opacity-0 transition-all duration-300 ease-out`;
            
            if (type === 'error') {
                toast.classList.add('border-red-100');
                toast.innerHTML = `
                    <div class="h-8 w-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 shrink-0">⚠️</div>
                    <span class="text-sm font-semibold text-gray-700">${message}</span>
                `;
            } else {
                toast.classList.add('border-rose-100');
                toast.innerHTML = `
                    <div class="h-8 w-8 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">✨</div>
                    <span class="text-sm font-semibold text-gray-700">${message}</span>
                `;
            }

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const orderId = '{{ $order->id }}';
            const checkStatusBtn = document.getElementById('check-status-btn');
            if (localStorage.getItem('payment_initialized_' + orderId) === 'true') {
                checkStatusBtn.classList.remove('hidden');
            }
        });

        document.getElementById('pay-button').onclick = function() {
            const orderId = '{{ $order->id }}';
            localStorage.setItem('payment_initialized_' + orderId, 'true');
            document.getElementById('check-status-btn').classList.remove('hidden');

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    localStorage.removeItem('payment_initialized_' + orderId);
                    window.location.href = '{{ route('payment.check-status', $order->id) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('payment.check-status', $order->id) }}';
                },
                onError: function(result) {
                    showToast('Pembayaran gagal. Silakan coba lagi.', 'error');
                },
                onClose: function() {
                    showToast('Kamu menutup popup pembayaran. Jika sudah bayar, klik tombol Cek Status di bawah.', 'info');
                }
            });
        };
    </script>

</body>
</html>