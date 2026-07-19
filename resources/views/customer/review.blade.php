<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan - CharmOnTi</title>
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

    <div class="max-w-2xl mx-auto">
        {{-- Brand Logo --}}
        <div class="text-center mb-10">
            <h1 class="text-2xl font-bold tracking-tight mb-6">
                <a href="/" class="inline-flex items-center gap-2.5 hover:opacity-90 transition">
                    <img src="{{ asset('charmonti.png') }}" alt="CharmOnTi Logo" class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                    <span class="bg-gradient-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">CharmOnTi</span>
                </a>
            </h1>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Tulis Ulasan Produk 🌸</h2>
            <p class="text-sm text-gray-500 mt-2 font-light">Bagikan pengalaman belanjamu untuk membantu pembeli lain dan mendukung produk lokal kami ✨</p>
        </div>

        <form method="POST" action="{{ route('customer.order.review.store', $order->id) }}">
            @csrf

            <div class="space-y-6">
                @foreach($itemsToReview as $item)
                    <div class="glass-card rounded-3xl p-8 shadow-sm hover:shadow-md transition duration-300">
                        {{-- Product Info --}}
                        <div class="flex items-center gap-5 mb-6 border-b border-rose-50 pb-5">
                            <div class="bg-rose-50/50 rounded-2xl h-16 w-16 flex items-center justify-center shrink-0 border border-rose-100/50 overflow-hidden">
                                @if(!empty($item['image']))
                                    <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl text-rose-300">📿</span>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm md:text-base">{{ $item['name'] }}</h4>
                                <p class="text-xs text-rose-500 font-medium bg-rose-50 px-2.5 py-1 rounded-md inline-block mt-1.5 capitalize border border-rose-100/50">{{ $item['category'] }}</p>
                            </div>
                        </div>

                        {{-- Star Rating --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Beri Rating:</label>
                            <div class="flex items-center gap-2.5 star-rating-container" data-product-id="{{ $item['id'] }}">
                                <input type="hidden" name="ratings[{{ $item['id'] }}]" id="rating-input-{{ $item['id'] }}" value="5">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="text-4xl star-btn text-yellow-400 hover:scale-110 transition duration-150 transform drop-shadow-xs" 
                                            data-value="{{ $i }}" 
                                            data-product-id="{{ $item['id'] }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                        </div>

                        {{-- Comment Box --}}
                        <div>
                            <label for="comment-{{ $item['id'] }}" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Ulasan Anda (Opsional):</label>
                            <textarea name="comments[{{ $item['id'] }}]" 
                                      id="comment-{{ $item['id'] }}" 
                                      rows="3" 
                                      placeholder="Tulis pendapatmu tentang gelang cantik ini..." 
                                      class="w-full bg-white/70 border border-rose-100 rounded-2xl p-4 text-sm text-gray-700 font-light focus:outline-none focus:ring-2 focus:ring-rose-200 focus:border-rose-300 placeholder-gray-400 transition shadow-sm"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="{{ route('customer.orders') }}" 
                   class="sm:w-1/3 bg-white border border-gray-200 hover:border-rose-200 hover:text-rose-500 text-gray-500 font-medium py-3.5 rounded-full text-center transition shadow-sm text-sm">
                    Kembali
                </a>
                <button type="submit" 
                        class="sm:w-2/3 bg-rose-400 hover:bg-rose-500 text-white font-medium py-3.5 rounded-full transition shadow-sm hover:shadow-md hover:-translate-y-0.5 text-sm text-center">
                    Kirim Ulasan Cantik ✨
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const starButtons = document.querySelectorAll('.star-btn');

            starButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.dataset.productId;
                    const ratingValue = parseInt(this.dataset.value);
                    
                    // Update input hidden
                    document.getElementById(`rating-input-${productId}`).value = ratingValue;

                    // Update star colors
                    const container = this.closest('.star-rating-container');
                    const stars = container.querySelectorAll('.star-btn');
                    
                    stars.forEach(star => {
                        const starValue = parseInt(star.dataset.value);
                        if (starValue <= ratingValue) {
                            star.classList.remove('text-gray-200');
                            star.classList.add('text-yellow-400');
                        } else {
                            star.classList.remove('text-yellow-400');
                            star.classList.add('text-gray-200');
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>
