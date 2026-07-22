<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelang Custom — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-sm">
                <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
            </a>
        </h1>
        <a href="/" class="text-sm font-medium text-gray-500 hover:text-rose-500 transition">← Kembali</a>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 tracking-tight">Buat Gelang Custom 🌸</h2>
        <p class="text-gray-500 text-base font-light mb-8">Pilih warna strap dan charm favoritmu untuk merangkai kisahmu sendiri (maks. 15 charm)</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 text-sm rounded-2xl px-5 py-4 mb-6 border border-red-100 shadow-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('cart.add-custom') }}" class="space-y-8">
            @csrf

            {{-- Pilih Warna Strap --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
                <h3 class="font-bold text-lg text-gray-800 mb-5">1. Pilih Warna Strap</h3>
                <div class="flex gap-4">
                    @php
                        $isSilverOut = ($strapSilver && $strapSilver->dynamic_stock <= 0);
                        $isGoldOut = ($strapGold && $strapGold->dynamic_stock <= 0);
                    @endphp
                    
                    {{-- Silver Strap --}}
                    <label class="flex-1 cursor-pointer group {{ $isSilverOut ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="radio" name="warna" value="silver" class="hidden peer" required {{ $isSilverOut ? 'disabled' : '' }}>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 {{ $isSilverOut ? 'bg-gray-100' : 'group-hover:bg-gray-50' }} shadow-sm">
                            <div class="h-28 flex items-center justify-center mb-3 overflow-hidden rounded-2xl bg-rose-50/40 relative cursor-pointer group/img"
                                 onclick="openStrapModal('Silver', '{{ $strapSilver && $strapSilver->image ? Storage::url($strapSilver->image) : '' }}', {{ $strapSilver ? $strapSilver->dynamic_stock : 0 }}, {{ $isSilverOut ? 'true' : 'false' }}, @json($strapSilver?->description ?? ''))"
                                 title="Lihat detail model strap">
                                @if($strapSilver && $strapSilver->image)
                                    <img src="{{ Storage::url($strapSilver->image) }}" alt="Tali Gelang Silver" class="w-full h-full object-cover rounded-2xl group-hover/img:scale-105 transition duration-500">
                                @else
                                    <span class="text-4xl">⚪</span>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/10 transition duration-300 rounded-2xl flex items-center justify-center">
                                    <span class="opacity-0 group-hover/img:opacity-100 transition duration-300 bg-white/90 rounded-full p-1.5 shadow text-gray-600 text-xs">
                                        🔍 Zoom
                                    </span>
                                </div>
                            </div>
                            <span class="font-semibold text-gray-700 block">Silver</span>
                            <span class="text-xs text-gray-400 block mt-1">
                                @if($isSilverOut)
                                    <strong class="text-red-500">Stok Habis</strong>
                                @else
                                    Stok: <strong class="text-gray-600">{{ $strapSilver->dynamic_stock }}</strong>
                                @endif
                            </span>
                        </div>
                    </label>
                    
                    {{-- Gold Strap --}}
                    <label class="flex-1 cursor-pointer group {{ $isGoldOut ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="radio" name="warna" value="gold" class="hidden peer" {{ $isGoldOut ? 'disabled' : '' }}>
                        <div class="border-2 border-gray-100 peer-checked:border-rose-400 peer-checked:bg-rose-50/50 rounded-2xl p-5 text-center transition duration-300 {{ $isGoldOut ? 'bg-gray-100' : 'group-hover:bg-gray-50' }} shadow-sm">
                            <div class="h-28 flex items-center justify-center mb-3 overflow-hidden rounded-2xl bg-rose-50/40 relative cursor-pointer group/img"
                                 onclick="openStrapModal('Gold', '{{ $strapGold && $strapGold->image ? Storage::url($strapGold->image) : '' }}', {{ $strapGold ? $strapGold->dynamic_stock : 0 }}, {{ $isGoldOut ? 'true' : 'false' }}, @json($strapGold?->description ?? ''))"
                                 title="Lihat detail model strap">
                                @if($strapGold && $strapGold->image)
                                    <img src="{{ Storage::url($strapGold->image) }}" alt="Tali Gelang Gold" class="w-full h-full object-cover rounded-2xl group-hover/img:scale-105 transition duration-500">
                                @else
                                    <span class="text-4xl">🟡</span>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/10 transition duration-300 rounded-2xl flex items-center justify-center">
                                    <span class="opacity-0 group-hover/img:opacity-100 transition duration-300 bg-white/90 rounded-full p-1.5 shadow text-gray-600 text-xs">
                                        🔍 Zoom
                                    </span>
                                </div>
                            </div>
                            <span class="font-semibold text-gray-700 block">Gold</span>
                            <span class="text-xs text-gray-400 block mt-1">
                                @if($isGoldOut)
                                    <strong class="text-red-500">Stok Habis</strong>
                                @else
                                    Stok: <strong class="text-gray-600">{{ $strapGold->dynamic_stock }}</strong>
                                @endif
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Pilih Charm --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800">2. Pilih Charm</h3>
                    <span class="text-sm font-medium bg-rose-50 text-rose-500 px-4 py-1.5 rounded-full border border-rose-100">Dipilih: <span id="charm-count" class="font-bold">0</span>/15</span>
                </div>

                {{-- Info Notice: foto berisi banyak charm --}}
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200/70 rounded-2xl px-4 py-3 mb-6">
                    <span class="text-amber-500 text-lg mt-0.5 shrink-0">💡</span>
                    <p class="text-amber-700 text-xs leading-relaxed">
                        <strong class="font-semibold">Perhatian:</strong> Setiap foto charm mungkin menampilkan <strong>banyak variasi</strong> dalam 1 gambar (contoh: A–Z, berbagai motif, dll.).
                        Setelah memilih jumlah, harap tuliskan <strong>charm spesifik yang diinginkan</strong> di kolom <strong>Catatan Desain</strong> di bawah.
                    </p>
                </div>

                @if($charms->isEmpty())
                    <p class="text-gray-400 text-center py-8 font-light">Belum ada charm tersedia.</p>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($charms as $charm)
                        @php
                            $charmStock = $charm->dynamic_stock;
                            $isCharmOutOfStock = $charmStock <= 0;
                        @endphp
                        <div class="charm-card-container group {{ $isCharmOutOfStock ? 'opacity-65' : '' }} flex flex-col h-full">
                            <div class="charm-card border-2 border-gray-100 rounded-2xl p-4 text-center transition duration-300 flex flex-col justify-between h-full {{ $isCharmOutOfStock ? 'bg-gray-50/50' : 'group-hover:border-rose-200' }}">
                                <div>
                                    <div class="bg-rose-50/50 rounded-xl h-20 flex items-center justify-center mb-3 overflow-hidden relative cursor-pointer"
                                         onclick="openCharmModal({{ $charm->id }}, '{{ addslashes($charm->nama_bahan) }}', {{ $charm->price }}, {{ $charmStock }}, {{ $isCharmOutOfStock ? 'true' : 'false' }}, '{{ $charm->image ? Storage::url($charm->image) : '' }}')"
                                         title="Lihat detail charm">
                                        @if($isCharmOutOfStock)
                                            <div class="absolute inset-0 bg-black/5 flex items-center justify-center z-10">
                                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Habis</span>
                                            </div>
                                        @endif
                                        @if($charm->image)
                                            <img src="{{ Storage::url($charm->image) }}"
                                                alt="{{ $charm->nama_bahan }}"
                                                class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition duration-500">
                                        @else
                                            <span class="text-3xl text-rose-300">📿</span>
                                        @endif
                                        {{-- Zoom hint overlay --}}
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 rounded-xl flex items-center justify-center">
                                            <span class="opacity-0 group-hover:opacity-100 transition duration-300 bg-white/90 rounded-full p-1 shadow text-gray-600 text-xs">
                                                🔍
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 leading-tight mb-2 min-h-[2.5rem] flex items-center justify-center text-center">{{ $charm->nama_bahan }}</p>
                                </div>

                                <div class="mt-auto">
                                    <div class="flex flex-col gap-0.5 mb-3">
                                        <span class="text-xs text-rose-500 font-bold">
                                            Rp {{ number_format($charm->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[10px] text-gray-400">
                                            @if($isCharmOutOfStock)
                                                <strong class="text-red-500">Stok Habis</strong>
                                            @else
                                                Stok: <strong class="text-gray-600">{{ $charmStock }}</strong>
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Counter Buttons --}}
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button" 
                                            onclick="adjustQty({{ $charm->id }}, -1)"
                                            class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition font-bold select-none disabled:opacity-40"
                                            {{ $isCharmOutOfStock ? 'disabled' : '' }}>-</button>
                                        
                                        <span id="qty-display-{{ $charm->id }}" class="font-bold text-gray-700 w-6 text-center text-sm">0</span>
                                        
                                        <input type="hidden" name="charms[{{ $charm->id }}]" id="qty-input-{{ $charm->id }}" value="0" class="charm-qty-input">

                                        <button type="button" 
                                            onclick="adjustQty({{ $charm->id }}, 1)"
                                            class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:text-rose-500 transition font-bold select-none disabled:opacity-40"
                                            {{ $isCharmOutOfStock ? 'disabled' : '' }}>+</button>
                                    </div>

                                    {{-- Notes per charm (muncul otomatis & smooth saat qty > 0) --}}
                                    <div id="notes-wrap-{{ $charm->id }}" class="overflow-hidden max-h-0 opacity-0 transform -translate-y-2 transition-all duration-300 ease-out">
                                        <div class="pt-3">
                                            <input type="text"
                                                name="charm_notes[{{ $charm->id }}]"
                                                id="notes-input-{{ $charm->id }}"
                                                maxlength="100"
                                                placeholder="Tulis variasi charm ini..."
                                                class="w-full px-3 py-2 text-xs border border-rose-200 bg-rose-50/40 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-300/50 transition placeholder-gray-400">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Catatan --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-6">
                <h3 class="font-bold text-lg text-gray-800 mb-2">3. Catatan Desain</h3>

                {{-- Reminder untuk spesifikasi charm --}}
                <div class="flex items-start gap-3 bg-rose-50/60 border border-rose-100 rounded-2xl px-4 py-3">
                    <span class="text-rose-400 text-base shrink-0 mt-0.5">✏️</span>
                    <p class="text-rose-600 text-xs leading-relaxed">
                        <strong class="font-semibold">Wajib diisi jika kamu memilih charm yang memiliki banyak variasi</strong> dalam satu foto (contoh: charm huruf, charm motif, dll.).
                        Tuliskan dengan jelas charm mana yang kamu inginkan agar pesananmu bisa kami proses dengan tepat. 🌸
                    </p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Catatan Tambahan <span class="text-rose-400 font-normal text-xs">(isi jika ada variasi charm yang dipilih)</span></label>
                    <textarea name="request_note" rows="3"
                        class="w-full px-5 py-3 bg-gray-50/50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition leading-relaxed"
                        placeholder="Contoh: charm huruf 'R', 'A', 'C' — charm anjing, kucing, bintang — diurutkan bintang, bulat, bintang ya..."></textarea>
                </div>
            </div>

            {{-- Total & Submit --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 space-y-5">
                <div class="bg-rose-50/30 border border-rose-100/50 rounded-2xl p-6 text-sm space-y-3">
                    <div class="flex justify-between text-gray-500">
                        <span>Strap Gelang</span>
                        <span class="font-medium text-gray-700">Rp 20.000</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Harga Charm Pilihan</span>
                        <span id="subtotal-price" class="font-medium text-gray-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 pt-4 border-t border-dashed border-rose-200/60 text-base">
                        <span>Total Desain</span>
                        <span id="total-price" class="text-rose-500 text-lg">Rp 20.000</span>
                    </div>
                </div>
                <button type="submit"
                    class="w-full mt-2 bg-rose-400 hover:bg-rose-500 text-white font-semibold py-4 rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 text-base">
                    Masukkan Desain ke Keranjang 🛒
                </button>
            </div>

        </form>
    </div>

    {{-- ✨ Custom Notification Modal --}}
    <div id="custom-alert-modal" class="fixed inset-0 z-100 items-center justify-center p-4 hidden" onclick="closeCustomAlertModal(event)">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div id="custom-alert-modal-card" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden p-6 text-center transform scale-90 opacity-0 transition-all duration-300">
            <div class="w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100 text-2xl">
                ⚠️
            </div>
            <h4 class="font-bold text-gray-800 text-lg mb-1">Informasi Stok</h4>
            <p id="custom-alert-modal-msg" class="text-sm text-gray-500 font-light leading-relaxed mb-6"></p>
            <button onclick="closeCustomAlertModal()" class="w-full bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-full shadow-sm hover:shadow-md transition">
                Mengerti 👍
            </button>
        </div>
    </div>

    {{-- ✨ Strap Detail Modal --}}
    <div id="strap-modal" class="fixed inset-0 z-50 items-center justify-center p-4 hidden" onclick="closeStrapModal(event)">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div id="strap-modal-card" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[88vh] flex flex-col overflow-hidden transform scale-90 opacity-0 transition-all duration-300">
            <button onclick="closeStrapModal()" 
                class="absolute top-4 right-4 z-20 bg-white/90 hover:bg-gray-100 rounded-full w-10 h-10 flex items-center justify-center text-gray-500 shadow transition text-base">
                ✕
            </button>
            <div class="bg-rose-50/60 h-60 sm:h-72 shrink-0 flex items-center justify-center overflow-hidden relative p-6">
                <img id="strap-modal-img" src="" alt="" class="w-full h-full object-contain rounded-2xl">
                <span id="strap-modal-placeholder" class="text-8xl hidden">⚪</span>
                <div id="strap-modal-stock-badge" class="hidden absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full">Stok Habis</div>
            </div>
            <div class="p-6 sm:p-8 overflow-y-auto flex-1">
                <h3 id="strap-modal-name" class="text-xl sm:text-2xl font-bold text-gray-800 mb-2"></h3>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-rose-500 font-bold text-lg sm:text-xl">Model Strap Gelang</span>
                    <span id="strap-modal-stock" class="text-xs sm:text-sm text-gray-400 bg-gray-50 border border-gray-100 px-3.5 py-1.5 rounded-full"></span>
                </div>
                <p id="strap-modal-desc" class="text-xs text-gray-500 leading-relaxed bg-gray-50 border border-gray-100 p-4 rounded-2xl whitespace-pre-line">
                    Tali gelang dapat disesuaikan (adjustable size) dan terbuat dari bahan berkualitas tinggi. Pilih warna favoritmu untuk memulai merangkai gelang custom! ✨
                </p>
            </div>
        </div>
    </div>

    {{-- ✨ Charm Detail Modal --}}
    <div id="charm-modal" class="fixed inset-0 z-50 items-center justify-center p-4 hidden" onclick="closeCharmModal(event)">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        {{-- Modal Card --}}
        <div id="charm-modal-card" class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[88vh] flex flex-col overflow-hidden transform scale-90 opacity-0 transition-all duration-300">
            {{-- Close Button --}}
            <button onclick="document.getElementById('charm-modal').classList.add('hidden')" 
                class="absolute top-4 right-4 z-20 bg-white/90 hover:bg-gray-100 rounded-full w-10 h-10 flex items-center justify-center text-gray-500 shadow transition text-base">
                ✕
            </button>

            {{-- Image --}}
            <div class="bg-rose-50/60 h-56 sm:h-64 shrink-0 flex items-center justify-center overflow-hidden relative" id="modal-img-wrap">
                <img id="modal-img" src="" alt="" class="w-full h-full object-contain p-5">
                <span id="modal-img-placeholder" class="text-8xl hidden">📿</span>
                <div id="modal-stock-badge" class="hidden absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-4 py-1.5 rounded-full">Stok Habis</div>
            </div>

            {{-- Detail (Scrollable Body) --}}
            <div class="p-6 sm:p-8 overflow-y-auto flex-1">
                <h3 id="modal-name" class="text-xl sm:text-2xl font-bold text-gray-800 mb-2"></h3>
                <div class="flex items-center justify-between mb-4">
                    <span id="modal-price" class="text-rose-500 font-bold text-lg sm:text-xl"></span>
                    <span id="modal-stock" class="text-xs sm:text-sm text-gray-400 bg-gray-50 border border-gray-100 px-3.5 py-1.5 rounded-full"></span>
                </div>

                {{-- Deskripsi Bahan --}}
                <div id="modal-desc-wrap" class="hidden mb-6 bg-rose-50/50 border border-rose-100/60 p-4 rounded-2xl text-left">
                    <p class="text-[11px] font-semibold text-rose-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                        <span>📝 Deskripsi Bahan</span>
                    </p>
                    <p id="modal-desc" class="text-xs text-gray-600 font-light leading-relaxed whitespace-pre-line"></p>
                </div>

                {{-- Counter inside modal --}}
                <div id="modal-actions" class="flex items-center justify-center gap-6 mt-2">
                    <button type="button" id="modal-btn-minus"
                        class="w-11 h-11 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-2xl select-none">
                        −
                    </button>
                    <span id="modal-qty" class="font-bold text-gray-700 text-2xl w-10 text-center">0</span>
                    <button type="button" id="modal-btn-plus"
                        class="w-11 h-11 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-500 hover:bg-rose-50 hover:border-rose-300 hover:text-rose-500 transition font-bold text-2xl select-none">
                        +
                    </button>
                </div>
                {{-- Notes per charm di dalam modal (sync dengan card & smooth transition) --}}
                <div id="modal-notes-wrap" class="overflow-hidden max-h-0 opacity-0 transform -translate-y-2 transition-all duration-300 ease-out">
                    <div class="pt-5">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">✏️ Variasi charm yang kamu inginkan:</label>
                        <input type="text"
                            id="modal-notes-input"
                            maxlength="100"
                            placeholder="Contoh: huruf &quot;R&quot;, warna biru, motif kucing..."
                            class="w-full px-4 py-2.5 text-sm border border-rose-200 bg-rose-50/40 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-300/50 transition placeholder-gray-400">
                    </div>
                </div>
                <div id="modal-outofstock-msg" class="hidden text-center text-red-400 text-base font-medium mt-4">Stok manik-manik ini sedang habis</div>
            </div>
        </div>
    </div>

    <script>
        const prices = {
            @foreach($charms as $charm)
                {{ $charm->id }}: {{ $charm->price }},
            @endforeach
        };

        const stocks = {
            @foreach($charms as $charm)
                {{ $charm->id }}: {{ $charm->dynamic_stock }},
            @endforeach
        };

        const descriptions = {
            @foreach($charms as $charm)
                {{ $charm->id }}: @json($charm->description ?? ''),
            @endforeach
        };

        const countEl = document.getElementById('charm-count');
        const subtotalEl = document.getElementById('subtotal-price');
        const totalEl = document.getElementById('total-price');

        function adjustQty(charmId, delta) {
            const input = document.getElementById('qty-input-' + charmId);
            const display = document.getElementById('qty-display-' + charmId);
            const card = display.closest('.charm-card');

            let currentVal = parseInt(input.value) || 0;
            let currentTotal = getTotalQty();

            // Validate against stock limit and max charms limit
            if (delta > 0) {
                if (currentVal >= stocks[charmId]) {
                    showCustomAlert('Stok untuk manik-manik ini tidak mencukupi (Tersisa: ' + stocks[charmId] + ' pcs)');
                    return;
                }
                if (currentTotal >= 15) {
                    showCustomAlert('Maksimal manik/charm yang dapat dimasukkan adalah 15 pcs.');
                    return;
                }
            }

            let newVal = currentVal + delta;
            if (newVal < 0) newVal = 0;

            input.value = newVal;
            display.textContent = newVal;

            // Update Card Highlight Styles & Smooth Notes Animation
            const notesWrap = document.getElementById('notes-wrap-' + charmId);
            const notesInput = document.getElementById('notes-input-' + charmId);

            if (newVal > 0) {
                card.classList.add('border-rose-400', 'bg-rose-50/30');
                // Smooth Expand
                notesWrap.classList.remove('max-h-0', 'opacity-0', '-translate-y-2');
                notesWrap.classList.add('max-h-24', 'opacity-100', 'translate-y-0');
            } else {
                card.classList.remove('border-rose-400', 'bg-rose-50/30');
                // Smooth Collapse & Reset
                notesWrap.classList.remove('max-h-24', 'opacity-100', 'translate-y-0');
                notesWrap.classList.add('max-h-0', 'opacity-0', '-translate-y-2');
                setTimeout(() => {
                    if (parseInt(input.value) === 0) {
                        notesInput.value = '';
                    }
                }, 300);
            }

            // Sync modal qty display & smooth modal notes transition
            if (typeof _modalCharmId !== 'undefined' && _modalCharmId == charmId) {
                document.getElementById('modal-qty').textContent = newVal;
                const modalNotesWrap = document.getElementById('modal-notes-wrap');
                if (newVal > 0) {
                    modalNotesWrap.classList.remove('max-h-0', 'opacity-0', '-translate-y-2');
                    modalNotesWrap.classList.add('max-h-32', 'opacity-100', 'translate-y-0');
                } else {
                    modalNotesWrap.classList.remove('max-h-32', 'opacity-100', 'translate-y-0');
                    modalNotesWrap.classList.add('max-h-0', 'opacity-0', '-translate-y-2');
                }
            }

            calculateTotal();
        }

        function getTotalQty() {
            let total = 0;
            document.querySelectorAll('.charm-qty-input').forEach(input => {
                total += parseInt(input.value) || 0;
            });
            return total;
        }

        function calculateTotal() {
            let totalQty = getTotalQty();
            countEl.textContent = totalQty;

            let subtotal = 0;
            document.querySelectorAll('.charm-qty-input').forEach(input => {
                const id = input.id.replace('qty-input-', '');
                const qty = parseInt(input.value) || 0;
                subtotal += (prices[id] || 0) * qty;
            });

            let totalPrice = 20000 + subtotal;

            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            totalEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }

        // Run initially
        calculateTotal();

        // ── Charm Detail Modal ──────────────────────────────────────
        let _modalCharmId = null;

        function openCharmModal(id, name, price, stock, outOfStock, imgUrl) {
            _modalCharmId = id;

            // Populate image
            const img = document.getElementById('modal-img');
            const placeholder = document.getElementById('modal-img-placeholder');
            if (imgUrl) {
                img.src = imgUrl;
                img.alt = name;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // Stock badge
            const badge = document.getElementById('modal-stock-badge');
            badge.classList.toggle('hidden', !outOfStock);

            // Info text
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-price').textContent =
                'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('modal-stock').textContent =
                outOfStock ? 'Stok Habis' : 'Stok: ' + stock;

            // Deskripsi bahan
            const desc = (descriptions[id] || '').trim();
            const descWrap = document.getElementById('modal-desc-wrap');
            const descEl = document.getElementById('modal-desc');
            if (desc) {
                descEl.textContent = desc;
                descWrap.classList.remove('hidden');
            } else {
                descWrap.classList.add('hidden');
            }

            // Counter sync
            const currentQty = parseInt(document.getElementById('qty-input-' + id).value) || 0;
            document.getElementById('modal-qty').textContent = currentQty;

            // Out of stock state
            const actions = document.getElementById('modal-actions');
            const oos = document.getElementById('modal-outofstock-msg');
            if (outOfStock) {
                actions.classList.add('opacity-40', 'pointer-events-none');
                oos.classList.remove('hidden');
            } else {
                actions.classList.remove('opacity-40', 'pointer-events-none');
                oos.classList.add('hidden');
            }

            // Wire buttons
            document.getElementById('modal-btn-minus').onclick = () => {
                adjustQty(_modalCharmId, -1);
                document.getElementById('modal-qty').textContent =
                    parseInt(document.getElementById('qty-input-' + _modalCharmId).value) || 0;
            };
            document.getElementById('modal-btn-plus').onclick = () => {
                adjustQty(_modalCharmId, 1);
                document.getElementById('modal-qty').textContent =
                    parseInt(document.getElementById('qty-input-' + _modalCharmId).value) || 0;
            };

            // Sync notes: tampilkan field notes di modal jika qty > 0
            const modalNotesWrap = document.getElementById('modal-notes-wrap');
            const modalNotesInput = document.getElementById('modal-notes-input');
            const cardNotesInput  = document.getElementById('notes-input-' + id);

            if (currentQty > 0) {
                modalNotesWrap.classList.remove('max-h-0', 'opacity-0', '-translate-y-2');
                modalNotesWrap.classList.add('max-h-32', 'opacity-100', 'translate-y-0');
            } else {
                modalNotesWrap.classList.remove('max-h-32', 'opacity-100', 'translate-y-0');
                modalNotesWrap.classList.add('max-h-0', 'opacity-0', '-translate-y-2');
            }

            // Isi nilai notes dari card ke modal
            modalNotesInput.value = cardNotesInput ? cardNotesInput.value : '';

            // Saat notes di modal berubah → update card notes juga (two-way sync)
            modalNotesInput.oninput = () => {
                if (cardNotesInput) cardNotesInput.value = modalNotesInput.value;
            };

            // Show with animation
            const modal = document.getElementById('charm-modal');
            const card  = document.getElementById('charm-modal-card');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCharmModal(e) {
            // Only close if clicking the backdrop (not the card itself)
            if (e && e.target.closest('#charm-modal-card')) return;
            const modal = document.getElementById('charm-modal');
            const card  = document.getElementById('charm-modal-card');
            card.classList.add('scale-90', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 280);
        }

        // Close on Escape key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeCharmModal();
                closeStrapModal();
            }
        });

        // ── Strap Detail Modal ──────────────────────────────────────
        function openStrapModal(colorName, imgUrl, stock, outOfStock, desc) {
            const img = document.getElementById('strap-modal-img');
            const placeholder = document.getElementById('strap-modal-placeholder');
            if (imgUrl) {
                img.src = imgUrl;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            document.getElementById('strap-modal-name').textContent = 'Tali Gelang ' + colorName;
            document.getElementById('strap-modal-stock').textContent = outOfStock ? 'Stok Habis' : 'Stok: ' + stock;
            document.getElementById('strap-modal-stock-badge').classList.toggle('hidden', !outOfStock);

            const defaultDesc = 'Tali gelang dapat disesuaikan (adjustable size) dan terbuat dari bahan berkualitas tinggi. Pilih warna favoritmu untuk memulai merangkai gelang custom! ✨';
            const descEl = document.getElementById('strap-modal-desc');
            if (descEl) {
                descEl.textContent = (desc && desc.trim()) ? desc : defaultDesc;
            }

            const modal = document.getElementById('strap-modal');
            const card  = document.getElementById('strap-modal-card');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeStrapModal(e) {
            if (e && e.target.closest('#strap-modal-card')) return;
            const modal = document.getElementById('strap-modal');
            const card  = document.getElementById('strap-modal-card');
            if (!card) return;
            card.classList.add('scale-90', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 280);
        }

        // ── Custom Alert Modal ──────────────────────────────────────
        function showCustomAlert(msg) {
            document.getElementById('custom-alert-modal-msg').textContent = msg;
            const modal = document.getElementById('custom-alert-modal');
            const card  = document.getElementById('custom-alert-modal-card');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeCustomAlertModal(e) {
            if (e && e.target.closest('#custom-alert-modal-card')) return;
            const modal = document.getElementById('custom-alert-modal');
            const card  = document.getElementById('custom-alert-modal-card');
            if (!card) return;
            card.classList.add('scale-90', 'opacity-0');
            card.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 280);
        }
    </script>

</body>
</html>