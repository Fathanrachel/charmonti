<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] text-gray-700 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-50 border-b border-gray-100">
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
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 overflow-hidden">
            <div class="bg-rose-50/30 p-8 text-center border-b border-rose-100/30">
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Profil Saya 👤</h2>
                <p class="text-gray-500 mt-2 font-light text-sm">Kelola data alamat dan profil pengiriman pesanan Anda</p>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl flex items-center shadow-sm">
                        <span class="text-sm font-medium">✨ {{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-6 py-4 rounded-2xl flex items-center shadow-sm">
                        <span class="text-sm font-medium">⚠️ Terjadi kesalahan! Silakan lengkapi semua kolom yang wajib diisi (*).</span>
                    </div>
                @endif

                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $profile->name) }}" 
                                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email (Akun)</label>
                            <input type="email" value="{{ $user->email }}" 
                                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none bg-gray-100 text-gray-400 cursor-not-allowed" readonly>
                            <span class="text-xs text-gray-400 mt-1 block">Email tidak dapat diubah karena terhubung ke akun masuk Anda.</span>
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="contoh: 08123456789" required
                                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Pos --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos <span class="text-rose-500">*</span></label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" placeholder="contoh: 12345" required
                                   class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50">
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Provinsi <span class="text-rose-500">*</span></label>
                            <select name="province_id" id="province-select" required
                                    class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $prov)
                                    @php
                                        $selected = '';
                                        if ($profile->city_id) {
                                            $currentCity = \App\Models\City::find($profile->city_id);
                                            if ($currentCity && $currentCity->province_id == $prov->id) {
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $prov->id }}" {{ $selected }}>{{ $prov->province }}</option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="city_id" id="city-select" required
                                    class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50">
                                <option value="">-- Pilih Kota setelah Provinsi --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $profile->city_id == $city->id ? 'selected' : '' }}>{{ $city->city }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="address_line" rows="3" placeholder="Nama Jalan, Rt/Rw, Nomor Rumah, Kecamatan" required
                                  class="w-full px-5 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-400/50 transition duration-300 bg-gray-50/50 leading-relaxed">{{ old('address_line', $profile->address_line) }}</textarea>
                        @error('address_line')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" 
                                class="px-8 py-3.5 bg-rose-400 hover:bg-rose-500 text-white font-semibold rounded-full shadow-sm hover:shadow-md hover:-translate-y-0.5 transition duration-300 flex items-center gap-2">
                            <span>Simpan Perubahan 💾</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk memuat Kota secara dinamis berdasarkan Provinsi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province-select');
            const citySelect = document.getElementById('city-select');

            provinceSelect.addEventListener('change', function () {
                const provinceId = this.value;
                
                // Reset Kota select box
                citySelect.innerHTML = '<option value="">-- Sedang memuat kota... --</option>';
                
                if (!provinceId) {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota setelah Provinsi --</option>';
                    return;
                }

                // Fetch cities from API
                fetch(`/api/cities/${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.city;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        citySelect.innerHTML = '<option value="">-- Gagal memuat kota --</option>';
                    });
            });
        });
    </script>
</body>
</html>
