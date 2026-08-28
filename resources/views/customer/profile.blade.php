<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-xs px-6 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-gray-100">
        <h1 class="text-2xl font-bold tracking-tight">
            <a href="/" class="flex items-center gap-2.5 hover:opacity-90 transition">
                <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-10 w-10 rounded-full object-cover border border-rose-100 shadow-xs">
                <span class="bg-gradient-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
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
                        <span>Halo, <span class="text-rose-500 font-bold">{{ Auth::user()->profile?->name }}</span></span>
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
                        if (btn && menu) {
                            btn.addEventListener('click', function (e) {
                                e.stopPropagation();
                                menu.classList.toggle('hidden');
                            });
                            document.addEventListener('click', function () {
                                menu.classList.add('hidden');
                            });
                        }
                    });
                </script>
            @endauth
        </div>
    </nav>

    {{-- Main Container --}}
    <div class="max-w-5xl mx-auto py-10 px-4 md:px-6" x-data="{ activeTab: 'profile' }">
        
        {{-- Profile Hero Card Banner --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-rose-400 via-pink-400 to-rose-300 h-32 md:h-40 relative">
                <div class="absolute -bottom-10 left-6 md:left-10 flex items-end gap-4">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-gradient-to-tr from-rose-500 to-pink-400 text-white font-bold text-3xl md:text-4xl flex items-center justify-center border-4 border-white shadow-md">
                        {{ strtoupper(substr($profile->name ?? 'C', 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="pt-12 pb-6 px-6 md:px-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">{{ $profile->name }}</h2>
                        <span class="bg-rose-50 text-rose-600 text-xs font-semibold px-3 py-1 rounded-full border border-rose-100">
                            ✨ Pelanggan Setia
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $user->email }}
                    </p>
                </div>

                {{-- Stat Quick Cards --}}
                <div class="grid grid-cols-2 gap-3 md:gap-4 mt-2 md:mt-0">
                    <div class="bg-rose-50/50 border border-rose-100/60 rounded-2xl p-3 text-center min-w-[100px]">
                        <span class="block text-xs font-medium text-gray-500">Total Pesanan</span>
                        <span class="text-lg md:text-xl font-bold text-rose-600">{{ $totalOrdersCount }}</span>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-3 text-center min-w-[100px]">
                        <span class="block text-xs font-medium text-gray-500">Member Sejak</span>
                        <span class="text-xs md:text-sm font-bold text-gray-700">
                            {{ $user->created_at ? $user->created_at->format('M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-xs">
                <span class="text-xl">✨</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-6 py-4 rounded-2xl shadow-xs">
                <div class="flex items-center gap-2 font-semibold text-sm mb-1 text-rose-900">
                    <span>⚠️</span> Mohon periksa kembali inputan Anda:
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tab Navigation Header --}}
        <div class="flex border-b border-gray-200 mb-6 gap-2 md:gap-4 overflow-x-auto pb-1">
            <button @click="activeTab = 'profile'" 
                    :class="activeTab === 'profile' ? 'border-rose-500 text-rose-600 bg-rose-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-5 border-b-2 font-semibold text-sm rounded-t-xl transition duration-200 flex items-center gap-2 whitespace-nowrap">
                <span>👤 Data Diri & Alamat</span>
            </button>

            <button @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'border-rose-500 text-rose-600 bg-rose-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-5 border-b-2 font-semibold text-sm rounded-t-xl transition duration-200 flex items-center gap-2 whitespace-nowrap">
                <span>🔐 Ubah Password</span>
            </button>

            <button @click="activeTab = 'recent_orders'" 
                    :class="activeTab === 'recent_orders' ? 'border-rose-500 text-rose-600 bg-rose-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-3 px-5 border-b-2 font-semibold text-sm rounded-t-xl transition duration-200 flex items-center gap-2 whitespace-nowrap">
                <span>📦 Pesanan Terbaru</span>
            </button>
        </div>

        {{-- Tab Content 1: Data Diri & Alamat --}}
        <div x-show="activeTab === 'profile'" x-transition class="bg-white rounded-3xl shadow-xs border border-gray-100 p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-1">Informasi Diri & Alamat Utama</h3>
            <p class="text-xs text-gray-500 mb-6">Data ini digunakan untuk mempercepat proses checkout dan pengiriman barang pesanan Anda.</p>

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $profile->name) }}" required
                               class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Alamat Email (Akun)
                        </label>
                        <input type="email" value="{{ $user->email }}" readonly
                               class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none bg-gray-100 text-gray-400 text-sm cursor-not-allowed">
                        <span class="text-[11px] text-gray-400 mt-1 block">Email terhubung sebagai ID login akun Anda.</span>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Nomor WhatsApp / Telepon <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="contoh: 08123456789" required
                               class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm">
                    </div>

                    {{-- Kode Pos --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Kode Pos <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" placeholder="contoh: 12345" required
                               class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm">
                    </div>

                    @php
                        $selectedProvinceId = '';
                        $selectedProvinceName = '-- Pilih Provinsi --';
                        if ($profile->city_id) {
                            $currentCity = \App\Models\City::find($profile->city_id);
                            if ($currentCity) {
                                $selectedProvinceId = $currentCity->province_id;
                                $selectedProvinceName = $currentCity->province?->province ?? '-- Pilih Provinsi --';
                            }
                        }
                    @endphp

                    {{-- Provinsi Combobox (Search Langsung di Dalam Kolom) --}}
                    <div x-data="{
                        open: false,
                        search: '',
                        selectedId: '{{ $selectedProvinceId }}',
                        selectedName: '{{ $selectedProvinceName }}',
                        provinces: {{ json_encode($provinces) }},
                        get filteredProvinces() {
                            if (!this.search) return this.provinces;
                            return this.provinces.filter(p => p.province.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(prov) {
                            this.selectedId = prov.id;
                            this.selectedName = prov.province;
                            this.open = false;
                            this.search = '';
                            $dispatch('province-changed', prov.id);
                        }
                    }" class="relative">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Provinsi <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="province_id" :value="selectedId" required>
                        
                        <div class="relative" @click.outside="open = false; search = ''">
                            <div @click="open = true; $nextTick(() => { if($refs.searchInput) $refs.searchInput.focus(); })"
                                 class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus-within:ring-2 focus-within:ring-rose-400/50 hover:border-rose-300 transition duration-300 bg-gray-50/40 text-sm font-medium text-gray-700 flex items-center justify-between cursor-pointer">
                                
                                <template x-if="open">
                                    <input x-ref="searchInput" type="text" x-model="search" placeholder="Ketik untuk mencari provinsi..."
                                           class="w-full bg-transparent focus:outline-none text-sm text-gray-800 placeholder-gray-400 font-medium">
                                </template>
                                
                                <template x-if="!open">
                                    <span x-text="selectedName" :class="!selectedId ? 'text-gray-400' : 'text-gray-800 font-medium'"></span>
                                </template>

                                <svg class="w-4 h-4 text-rose-500 transition-transform duration-200 flex-shrink-0 ml-2" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div x-show="open" x-transition
                                 class="absolute z-50 mt-2 w-full bg-white border border-rose-100 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto space-y-1">
                                
                                <template x-for="prov in filteredProvinces" :key="prov.id">
                                    <div @click="select(prov)"
                                         :class="selectedId == prov.id ? 'bg-rose-100 text-rose-700 font-bold' : 'hover:bg-rose-50 hover:text-rose-600 text-gray-700'"
                                         class="px-4 py-2.5 rounded-xl text-xs cursor-pointer transition flex items-center justify-between">
                                        <span x-text="prov.province"></span>
                                        <span x-show="selectedId == prov.id" class="text-rose-600 font-bold">✓</span>
                                    </div>
                                </template>

                                <div x-show="filteredProvinces.length === 0" class="px-4 py-3 text-xs text-gray-400 text-center">
                                    Provinsi tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kota / Kabupaten Combobox (Search Langsung di Dalam Kolom) --}}
                    <div x-data="{
                        open: false,
                        search: '',
                        selectedId: '{{ $profile->city_id }}',
                        selectedName: '{{ $profile->city?->city ?? '-- Pilih Kota setelah Provinsi --' }}',
                        cities: {{ json_encode($cities) }},
                        loading: false,
                        get filteredCities() {
                            if (!this.search) return this.cities;
                            return this.cities.filter(c => c.city.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(city) {
                            this.selectedId = city.id;
                            this.selectedName = city.city;
                            this.open = false;
                            this.search = '';
                        },
                        fetchCities(provinceId) {
                            if (!provinceId) {
                                this.cities = [];
                                this.selectedId = '';
                                this.selectedName = '-- Pilih Kota setelah Provinsi --';
                                return;
                            }
                            this.loading = true;
                            this.selectedId = '';
                            this.selectedName = '-- Sedang memuat kota... --';
                            fetch('/api/cities/' + provinceId)
                                .then(res => res.json())
                                .then(data => {
                                    this.cities = data;
                                    this.selectedName = '-- Pilih Kota / Kabupaten --';
                                    this.loading = false;
                                })
                                .catch(() => {
                                    this.loading = false;
                                    this.selectedName = '-- Gagal memuat kota --';
                                });
                        }
                    }" @province-changed.window="fetchCities($event.detail)" class="relative">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Kota / Kabupaten <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="city_id" :value="selectedId" required>

                        <div class="relative" @click.outside="open = false; search = ''">
                            <div @click="open = true; $nextTick(() => { if($refs.citySearchInput) $refs.citySearchInput.focus(); })"
                                 class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus-within:ring-2 focus-within:ring-rose-400/50 hover:border-rose-300 transition duration-300 bg-gray-50/40 text-sm font-medium text-gray-700 flex items-center justify-between cursor-pointer">
                                
                                <template x-if="open">
                                    <input x-ref="citySearchInput" type="text" x-model="search" placeholder="Ketik untuk mencari kota..."
                                           class="w-full bg-transparent focus:outline-none text-sm text-gray-800 placeholder-gray-400 font-medium">
                                </template>
                                
                                <template x-if="!open">
                                    <span x-text="selectedName" :class="!selectedId ? 'text-gray-400' : 'text-gray-800 font-medium'"></span>
                                </template>

                                <svg class="w-4 h-4 text-rose-500 transition-transform duration-200 flex-shrink-0 ml-2" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <div x-show="open" x-transition
                                 class="absolute z-50 mt-2 w-full bg-white border border-rose-100 rounded-2xl shadow-xl p-2 max-h-60 overflow-y-auto space-y-1">
                                
                                <template x-for="city in filteredCities" :key="city.id">
                                    <div @click="select(city)"
                                         :class="selectedId == city.id ? 'bg-rose-100 text-rose-700 font-bold' : 'hover:bg-rose-50 hover:text-rose-600 text-gray-700'"
                                         class="px-4 py-2.5 rounded-xl text-xs cursor-pointer transition flex items-center justify-between">
                                        <span x-text="city.city"></span>
                                        <span x-show="selectedId == city.id" class="text-rose-600 font-bold">✓</span>
                                    </div>
                                </template>

                                <div x-show="filteredCities.length === 0 && !loading" class="px-4 py-3 text-xs text-gray-400 text-center">
                                    Kota tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="address_line" rows="3" placeholder="Nama Jalan, Rt/Rw, Nomor Rumah, Patokan Rumah, Kecamatan" required
                              class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm leading-relaxed">{{ old('address_line', $profile->address_line) }}</textarea>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" 
                            class="px-8 py-3.5 bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-semibold text-sm rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-300 flex items-center gap-2">
                        <span>Simpan Perubahan 💾</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Content 2: Ubah Password --}}
        <div x-show="activeTab === 'security'" x-transition class="bg-white rounded-3xl shadow-xs border border-gray-100 p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-1">Keamanan & Perubahan Kata Sandi</h3>
            <p class="text-xs text-gray-500 mb-4">Jaga keamanan akun Anda dengan meng-update kata sandi secara berkala.</p>

            {{-- Policy Notice --}}
            <div class="mb-6 p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-start gap-3 text-xs text-amber-900">
                <span class="text-lg leading-none">🛡️</span>
                <div>
                    <strong class="font-bold block text-amber-950">Aturan Batasan Perubahan Password:</strong>
                    <span>Demi keamanan akun Anda, kata sandi hanya dapat diubah <strong>1 kali setiap 7 hari (seminggu sekali)</strong>.</span>
                </div>
            </div>

            @php
                $isPasswordLocked = isset($passwordChangedAt) && $passwordChangedAt && $passwordChangedAt->gt(now()->subDays(7));
                $nextAvailableDate = $isPasswordLocked ? $passwordChangedAt->copy()->addDays(7)->translatedFormat('d F Y, H:i') : null;
            @endphp

            @if($isPasswordLocked)
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 text-xs text-rose-900">
                    <span class="text-lg leading-none">⏳</span>
                    <div>
                        <strong class="font-bold block text-rose-950">Fitur Ubah Password Dikunci Sementara:</strong>
                        <span>Kata sandi terakhir diubah pada <strong>{{ $passwordChangedAt->translatedFormat('d F Y, H:i') }} WIB</strong>.<br>
                        Anda baru dapat mengubah password kembali pada <strong>{{ $nextAvailableDate }} WIB</strong>.</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6 max-w-xl">
                @csrf
                
                {{-- Hidden Input data diri agar validasi tidak bermasalah --}}
                <input type="hidden" name="name" value="{{ $profile->name }}">
                <input type="hidden" name="phone" value="{{ $profile->phone }}">
                <input type="hidden" name="postal_code" value="{{ $profile->postal_code }}">
                <input type="hidden" name="province_id" value="{{ $profile->city?->province_id ?? '' }}">
                <input type="hidden" name="city_id" value="{{ $profile->city_id ?? '' }}">
                <input type="hidden" name="address_line" value="{{ $profile->address_line }}">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Password Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="current_password" placeholder="Masukkan password lama Anda" required
                           {{ $isPasswordLocked ? 'disabled' : '' }}
                           class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm {{ $isPasswordLocked ? 'cursor-not-allowed opacity-60' : '' }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" required
                           {{ $isPasswordLocked ? 'disabled' : '' }}
                           class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm {{ $isPasswordLocked ? 'cursor-not-allowed opacity-60' : '' }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang password baru Anda" required
                           {{ $isPasswordLocked ? 'disabled' : '' }}
                           class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/40 text-sm {{ $isPasswordLocked ? 'cursor-not-allowed opacity-60' : '' }}">
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            {{ $isPasswordLocked ? 'disabled' : '' }}
                            class="px-8 py-3.5 bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-semibold text-sm rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition duration-300 flex items-center gap-2 {{ $isPasswordLocked ? 'opacity-50 cursor-not-allowed hover:translate-y-0 hover:shadow-none' : '' }}">
                        <span>Update Password 🔐</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tab Content 3: Pesanan Terbaru --}}
        <div x-show="activeTab === 'recent_orders'" x-transition class="bg-white rounded-3xl shadow-xs border border-gray-100 p-6 md:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Riwayat Pesanan Terakhir</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau status transaksi belanja terbaru Anda.</p>
                </div>
                <a href="{{ route('customer.orders') }}" class="text-xs font-bold text-rose-500 hover:underline">
                    Lihat Semua Pesanan →
                </a>
            </div>

            @forelse($recentOrders as $order)
                <div class="border border-gray-100 rounded-2xl p-5 mb-4 hover:border-rose-200 transition duration-200 bg-gray-50/30">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3 mb-3">
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                            <span class="font-bold text-gray-800 text-sm">Order #{{ $order->id }}</span>
                            <span>•</span>
                            <span>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->translatedFormat('d M Y, H:i') : '' }}</span>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full 
                            @if($order->status == 'pending') bg-amber-50 text-amber-600 border border-amber-200
                            @elseif($order->status == 'diproses') bg-blue-50 text-blue-600 border border-blue-200
                            @elseif($order->status == 'dikirim') bg-indigo-50 text-indigo-600 border border-indigo-200
                            @elseif($order->status == 'selesai') bg-emerald-50 text-emerald-600 border border-emerald-200
                            @else bg-rose-50 text-rose-600 border border-rose-200 @endif">
                            @if($order->status == 'pending') Menunggu Pembayaran
                            @elseif($order->status == 'diproses') Sedang Diproses
                            @elseif($order->status == 'dikirim') Dalam Pengiriman
                            @elseif($order->status == 'selesai') Pesanan Selesai
                            @else Dibatalkan @endif
                        </span>
                    </div>

                    <div class="space-y-2">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-700">{{ $item->product?->product_name ?? 'Gelang Custom' }} × {{ $item->qty }}</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs">
                            <span class="text-gray-500">Total Belanja:</span>
                            <span class="font-bold text-rose-600 text-sm ml-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        @if($order->status == 'pending')
                            <a href="{{ route('payment.show', $order->id) }}" class="px-4 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold rounded-full transition">
                                Bayar Sekarang →
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400">
                    <span class="text-4xl block mb-2">🛍️</span>
                    <p class="text-sm font-medium">Belum ada transaksi pesanan.</p>
                </div>
            @endforelse
        </div>

    </div>
</body>
</html>
