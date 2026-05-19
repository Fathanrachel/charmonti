<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Komplain - CharmOnTi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #FFF9F6 0%, #FFF3EC 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(253, 230, 138, 0.4);
        }
    </style>
</head>
<body class="py-12 px-4">

    <div class="max-w-xl mx-auto">
        {{-- Brand Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2.5">
                <img src="/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full object-cover border-2 border-amber-300 shadow-md">
                <span class="text-2xl font-extrabold bg-linear-to-r from-amber-500 via-orange-500 to-amber-600 bg-clip-text text-transparent tracking-tight">CharmOnTi</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800 mt-4">Ajukan Komplain</h1>
            <p class="text-sm text-gray-500 mt-1.5">Kami berkomitmen memberikan kualitas terbaik. Beritahu kami kendala pesanan Anda, dan kami akan segera mengatasinya 🤝</p>
        </div>

        <div class="glass-card rounded-3xl p-6 md:p-8 shadow-sm">
            <form method="POST" action="{{ route('customer.order.complaint.store', $order->id) }}" class="space-y-6">
                @csrf

                {{-- Order Summary --}}
                <div class="bg-amber-50/50 border border-amber-200/50 rounded-2xl p-4 flex justify-between items-center text-sm text-amber-900/80">
                    <span class="font-medium">Tanggal Pesanan:</span>
                    <span class="font-bold">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                {{-- Category Select --}}
                <div>
                    <label for="category" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kategori Masalah:</label>
                    <select name="category" 
                            id="category" 
                            required 
                            class="w-full bg-white border border-amber-200/50 rounded-2xl p-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400/50 transition">
                        <option value="" disabled selected>Pilih kategori kendala...</option>
                        <option value="Gelang Rusak / Putus">Gelang Rusak / Cacat Produksi</option>
                        <option value="Variasi Charm Salah / Kurang">Variasi Charm Salah atau Kurang</option>
                        <option value="Paket Tidak Sampai / Terlambat">Pengiriman Paket Terlambat / Tidak Sampai</option>
                        <option value="Lainnya">Kelemahan / Masalah Lainnya</option>
                    </select>
                </div>

                {{-- Message Textarea --}}
                <div>
                    <label for="message" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jelaskan Kendala Anda:</label>
                    <textarea name="message" 
                              id="message" 
                              rows="5" 
                              required 
                              placeholder="Ketik rincian kendala secara jelas (misal: warna strap tertukar, isi charm ada yang pecah, dsb)..." 
                              class="w-full bg-white border border-amber-200/50 rounded-2xl p-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400/50 placeholder-gray-400 transition"></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 pt-2">
                    <a href="{{ route('customer.orders') }}" 
                       class="flex-1 border border-amber-200 bg-white hover:bg-amber-50/50 text-amber-700 font-bold py-3.5 rounded-2xl text-center transition shadow-sm text-sm">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="flex-2 bg-linear-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-bold py-3.5 rounded-2xl transition shadow-md hover:shadow-lg text-sm text-center">
                        Kirim Komplain ⚠️
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
