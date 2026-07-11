<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Komplain - CharmOnTi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #FCFBF9 0%, #FFF0F5 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 228, 230, 0.5); /* rose-100 */
        }
    </style>
</head>
<body class="py-12 px-6">

    <div class="max-w-xl mx-auto">
        {{-- Brand Logo --}}
        <div class="text-center mb-10">
            <h1 class="text-2xl font-bold tracking-tight mb-6">
                <a href="/" class="inline-flex items-center gap-2.5 hover:opacity-90 transition">
                    <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <span class="bg-gradient-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
                </a>
            </h1>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Ajukan Komplain 🌸</h2>
            <p class="text-sm text-gray-500 mt-2 font-light">Kami berkomitmen memberikan kualitas terbaik. Beritahu kami kendala pesanan Anda, dan kami akan segera mengatasinya dengan penuh cinta 🤝</p>
        </div>

        <div class="glass-card rounded-[2rem] p-8 md:p-10 shadow-sm">
            <form method="POST" action="{{ route('customer.order.complaint.store', $order->id) }}" class="space-y-6">
                @csrf

                {{-- Order Summary --}}
                <div class="bg-rose-50/50 border border-rose-100 rounded-2xl p-5 flex justify-between items-center text-sm text-rose-800">
                    <span class="font-light">Tanggal Pesanan:</span>
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d M Y, H:i') }} WIB</span>
                </div>

                {{-- Category Select --}}
                <div>
                    <label for="category" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Kategori Masalah:</label>
                    <select name="category" 
                            id="category" 
                            required 
                            class="w-full bg-white/70 border border-rose-100 rounded-2xl p-4 text-sm text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-300 transition shadow-sm appearance-none cursor-pointer">
                        <option value="" disabled selected>Pilih kategori kendala...</option>
                        <option value="Gelang Rusak / Putus">Gelang Rusak / Cacat Produksi</option>
                        <option value="Variasi Charm Salah / Kurang">Variasi Charm Salah atau Kurang</option>
                        <option value="Paket Tidak Sampai / Terlambat">Pengiriman Paket Terlambat / Tidak Sampai</option>
                        <option value="Lainnya">Kelemahan / Masalah Lainnya</option>
                    </select>
                </div>

                {{-- Message Textarea --}}
                <div>
                    <label for="message" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Jelaskan Kendala Anda:</label>
                    <textarea name="message" 
                              id="message" 
                              rows="5" 
                              required 
                              placeholder="Ketik rincian kendala secara jelas (misal: warna strap tertukar, isi charm ada yang pecah, dsb)..." 
                              class="w-full bg-white/70 border border-rose-100 rounded-2xl p-4 text-sm text-gray-700 font-light focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-300 placeholder-gray-400 transition shadow-sm"></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('customer.orders') }}" 
                       class="sm:w-1/3 bg-white border border-gray-200 hover:border-rose-200 hover:text-rose-500 text-gray-500 font-medium py-3.5 rounded-full text-center transition shadow-sm text-sm">
                        Batal
                    </a>
                    <button type="submit" 
                            class="sm:w-2/3 bg-rose-400 hover:bg-rose-500 text-white font-medium py-3.5 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5 text-sm text-center">
                        Kirim Komplain ⚠️
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
