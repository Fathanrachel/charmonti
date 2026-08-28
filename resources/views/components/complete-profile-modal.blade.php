{{-- Complete Profile Modal Component for CharmOnTi --}}
<div id="complete-profile-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-md hidden opacity-0 transition-all duration-300 ease-out"
     role="dialog" 
     aria-modal="true">
    
    <div id="modal-card" 
         class="bg-white/95 border border-rose-100 w-full max-w-lg rounded-[2.5rem] shadow-2xl p-6 md:p-8 transform scale-90 translate-y-4 transition-all duration-300 ease-out relative overflow-hidden">
        
        {{-- Top Decorative Gradient Header --}}
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-gradient-to-br from-rose-200 to-pink-300 rounded-full blur-2xl opacity-60"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center text-rose-500 font-bold text-xl shadow-sm">
                    🌸
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 tracking-tight">Lengkapi Data Diri</h3>
                    <p class="text-xs text-rose-500 font-medium">Diperlukan untuk kalkulasi ongkir & pengiriman pesanan</p>
                </div>
            </div>

            <p class="text-xs text-gray-500 font-light mb-6 leading-relaxed">
                Halo! Agar pesanan Anda dapat diproses dan dikirim dengan tepat, silakan lengkapi nomor telepon dan alamat pengiriman Anda di bawah ini:
            </p>

            <form id="complete-profile-form" class="space-y-4">
                @csrf
                
                {{-- Nama Lengkap --}}
                <div>
                    <label for="modal_profile_name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                    <input type="text" 
                           id="modal_profile_name" 
                           name="name" 
                           required 
                           value="{{ old('name', Auth::user()?->profile?->name ?? Auth::user()?->name) }}"
                           placeholder="Masukkan nama lengkap Anda" 
                           class="w-full bg-white border border-rose-100 rounded-2xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-400 transition shadow-sm">
                </div>

                {{-- No. HP / WhatsApp --}}
                <div>
                    <label for="modal_profile_phone" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nomor HP / WhatsApp *</label>
                    <input type="tel" 
                           id="modal_profile_phone" 
                           name="phone" 
                           required 
                           value="{{ old('phone', Auth::user()?->profile?->phone) }}"
                           placeholder="Contoh: 081234567890" 
                           class="w-full bg-white border border-rose-100 rounded-2xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-400 transition shadow-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Provinsi --}}
                    <div>
                        <label for="modal_profile_province_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Provinsi *</label>
                        <select id="modal_profile_province_id" 
                                name="province_id" 
                                required 
                                class="w-full bg-white border border-rose-100 rounded-2xl px-3 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-400 transition shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Provinsi...</option>
                            @if(isset($provinces))
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}" {{ (Auth::user()?->profile?->province_id == $prov->id) ? 'selected' : '' }}>
                                        {{ $prov->province }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Kota / Kabupaten --}}
                    <div>
                        <label for="modal_profile_city_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kota / Kabupaten *</label>
                        <select id="modal_profile_city_id" 
                                name="city_id" 
                                required 
                                class="w-full bg-white border border-rose-100 rounded-2xl px-3 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-400 transition shadow-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Kota/Kab...</option>
                            @if(isset($cities))
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}" {{ (Auth::user()?->profile?->city_id == $c->id) ? 'selected' : '' }}>
                                        {{ $c->city }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div>
                    <label for="modal_profile_address" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Alamat Lengkap Pengiriman *</label>
                    <textarea id="modal_profile_address" 
                              name="address" 
                              rows="3" 
                              required 
                              placeholder="Nama Jalan, RT/RW, No. Rumah, Kecamatan, Kelurahan, Kode Pos..." 
                              class="w-full bg-white border border-rose-100 rounded-2xl p-3.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-400 transition shadow-sm">{{ old('address', Auth::user()?->profile?->address ?? Auth::user()?->profile?->address_line) }}</textarea>
                </div>

                <div id="modal-error-alert" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 font-medium"></div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="submit" 
                            id="btn-save-modal-profile"
                            class="w-full bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-semibold py-3.5 px-6 rounded-full shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                        <span>Simpan & Lanjutkan Checkout ✨</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('complete-profile-modal');
        const modalCard = document.getElementById('modal-card');
        const form = document.getElementById('complete-profile-form');
        const provSelect = document.getElementById('modal_profile_province_id');
        const citySelect = document.getElementById('modal_profile_city_id');
        const errorAlert = document.getElementById('modal-error-alert');
        const btnSave = document.getElementById('btn-save-modal-profile');

        // Dynamic City Loading on Province Change
        if (provSelect) {
            provSelect.addEventListener('change', function () {
                const provId = this.value;
                citySelect.innerHTML = '<option value="" disabled selected>Memuat kota...</option>';
                
                fetch(`/api/cities/${provId}`)
                    .then(res => res.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="" disabled selected>Pilih Kota/Kab...</option>';
                        data.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city.id;
                            opt.textContent = city.city;
                            citySelect.appendChild(opt);
                        });
                    })
                    .catch(err => {
                        console.error('Gagal memuat kota:', err);
                        citySelect.innerHTML = '<option value="" disabled selected>Gagal memuat data</option>';
                    });
            });
        }

        // Global function to open modal with spring animation
        window.openCompleteProfileModal = function () {
            if (!modal) return;
            modal.classList.remove('hidden');
            void modal.offsetHeight; // Force DOM Reflow for butter-smooth animation
            modal.classList.remove('opacity-0');
            modalCard.classList.remove('scale-90', 'translate-y-4');
            modalCard.classList.add('scale-100', 'translate-y-0');
        };

        // Global function to close modal
        window.closeCompleteProfileModal = function () {
            if (!modal) return;
            modal.classList.add('opacity-0');
            modalCard.classList.remove('scale-100', 'translate-y-0');
            modalCard.classList.add('scale-90', 'translate-y-4');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        // AJAX Form Submit
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                errorAlert.classList.add('hidden');
                btnSave.disabled = true;
                btnSave.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Menyimpan Data...</span>
                `;

                const formData = new FormData(form);

                fetch('{{ route("customer.profile.ajax-update") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200 && body.success) {
                        // Close modal smoothly
                        window.closeCompleteProfileModal();
                        
                        // Update checkout form values if exists
                        const addressInput = document.getElementById('shipping_address') || document.querySelector('textarea[name="shipping_address"]');
                        if (addressInput) {
                            addressInput.value = body.full_location;
                        }

                        // Trigger expedition costs update if function exists
                        if (typeof window.fetchExpeditionCosts === 'function') {
                            window.fetchExpeditionCosts(body.city_id);
                        } else {
                            window.location.reload();
                        }
                    } else {
                        errorAlert.textContent = body.message || 'Terjadi kesalahan. Silakan periksa kembali data Anda.';
                        errorAlert.classList.remove('hidden');
                        btnSave.disabled = false;
                        btnSave.innerHTML = '<span>Simpan & Lanjutkan Checkout ✨</span>';
                    }
                })
                .catch(err => {
                    console.error('Error submitting profile:', err);
                    errorAlert.textContent = 'Gagal menyimpan data. Pastikan koneksi internet terhubung.';
                    errorAlert.classList.remove('hidden');
                    btnSave.disabled = false;
                    btnSave.innerHTML = '<span>Simpan & Lanjutkan Checkout ✨</span>';
                });
            });
        }

        // Auto trigger modal if profile incomplete flag is true
        @if(isset($isProfileIncomplete) && $isProfileIncomplete)
            setTimeout(() => {
                window.openCompleteProfileModal();
            }, 400);
        @endif
    });
</script>
