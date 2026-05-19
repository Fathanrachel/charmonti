<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan - CharmOnTi</title>
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

    <div class="max-w-2xl mx-auto">
        {{-- Brand Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2.5">
                <img src="/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full object-cover border-2 border-amber-300 shadow-md">
                <span class="text-2xl font-extrabold bg-linear-to-r from-amber-500 via-orange-500 to-amber-600 bg-clip-text text-transparent tracking-tight">CharmOnTi</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800 mt-4">Tulis Ulasan Produk</h1>
            <p class="text-sm text-gray-500 mt-1.5">Bagikan pengalaman belanjamu untuk membantu pembeli lain dan mendukung produk lokal kami ✨</p>
        </div>

        <form method="POST" action="{{ route('customer.order.review.store', $order->id) }}">
            @csrf

            <div class="space-y-6">
                @foreach($order->orderItems as $item)
                    <div class="glass-card rounded-3xl p-6 shadow-sm hover:shadow-md transition duration-300">
                        {{-- Product Info --}}
                        <div class="flex items-center gap-4 mb-5 border-b border-amber-100/50 pb-4">
                            <div class="bg-amber-50 rounded-2xl h-14 w-14 flex items-center justify-center shrink-0 border border-amber-100 overflow-hidden">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl">📿</span>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm md:text-base">{{ $item->product->name }}</h4>
                                <p class="text-xs text-amber-600 font-semibold bg-amber-50 px-2 py-0.5 rounded-md inline-block mt-0.5 capitalize">{{ $item->product->category }}</p>
                            </div>
                        </div>

                        {{-- Star Rating --}}
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Beri Rating:</label>
                            <div class="flex items-center gap-2 star-rating-container" data-product-id="{{ $item->product->id }}">
                                <input type="hidden" name="ratings[{{ $item->product->id }}]" id="rating-input-{{ $item->product->id }}" value="5">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="text-4xl star-btn text-amber-400 hover:scale-110 transition duration-150 transform" 
                                            data-value="{{ $i }}" 
                                            data-product-id="{{ $item->product->id }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                        </div>

                        {{-- Comment Box --}}
                        <div>
                            <label for="comment-{{ $item->product->id }}" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ulasan Anda (Opsional):</label>
                            <textarea name="comments[{{ $item->product->id }}]" 
                                      id="comment-{{ $item->product->id }}" 
                                      rows="3" 
                                      placeholder="Tulis pendapatmu tentang gelang cantik ini..." 
                                      class="w-full bg-white/70 border border-amber-200/50 rounded-2xl p-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-400/50 placeholder-gray-400 transition"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4 mt-8">
                <a href="{{ route('customer.orders') }}" 
                   class="flex-1 border border-amber-200 bg-white hover:bg-amber-50/50 text-amber-700 font-bold py-3.5 rounded-2xl text-center transition shadow-sm text-sm">
                    Kembali
                </a>
                <button type="submit" 
                        class="flex-2 bg-linear-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3.5 rounded-2xl transition shadow-md hover:shadow-lg text-sm text-center">
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
                            star.classList.remove('text-gray-300');
                            star.classList.add('text-amber-400');
                        } else {
                            star.classList.remove('text-amber-400');
                            star.classList.add('text-gray-300');
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>
