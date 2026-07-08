<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('charmonti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-tr from-[#e5dfdf] via-[#ece8e8] to-[#dfd7d9] min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Star Deco in background (bottom right) -->
    <div class="absolute bottom-16 right-24 text-[#c4bcbc] opacity-60 animate-pulse pointer-events-none hidden md:block">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 0l3 9 9 3-9 3-3 9-3-9-9-3 9-3z"/>
        </svg>
    </div>

    <div class="bg-[#f5f2f0]/90 backdrop-blur-md rounded-[2.5rem] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.08)] border border-white/60 p-10 w-[90%] max-w-[420px] relative z-10">
        <!-- Sparkle Header Icon -->
        <div class="flex justify-center mb-4">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#e06553] drop-shadow-[0_2px_8px_rgba(224,101,83,0.3)]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 5.6L20 10l-5.6 2.4L12 18l-2.4-5.6L4 10l5.6-2.4z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#e06553] absolute -top-1.5 -right-1 opacity-70 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l1.5 3.5L17 7l-3.5 1.5L12 12l-1.5-3.5L7 7l3.5-1.5z"/>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-[#1f1a1a] text-center mb-8 tracking-tight">Masuk ke akun kamu</h2>
 
        @if($errors->any())
            <div class="bg-red-50/50 text-[#e06553] text-xs rounded-2xl px-4 py-3 mb-5 border border-red-100/50 text-center font-medium">
                {{ $errors->first() }}
            </div>
        @endif
 
        <form method="POST" action="/login" class="space-y-6">
            @csrf
            <div>
                <label class="text-sm font-medium text-[#4a4242] block mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-5 py-4 border border-[#e5dedb] rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#e06553]/30 bg-[#FAF8F6] text-gray-700 placeholder-gray-400 transition"
                    placeholder="email@kamu.com" required>
            </div>
            <div>
                <label class="text-sm font-medium text-[#4a4242] block mb-2">Password</label>
                <input type="password" name="password"
                    class="w-full px-5 py-4 border border-[#e5dedb] rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#e06553]/30 bg-[#FAF8F6] text-gray-700 placeholder-gray-400 transition"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full mt-2 bg-[#e06553] hover:bg-[#cd5543] active:scale-[0.98] text-white font-semibold py-4 rounded-full transition duration-300 shadow-[0_4px_15px_rgba(224,101,83,0.25)] hover:shadow-[0_6px_20px_rgba(224,101,83,0.35)] text-base">
                Masuk
            </button>
        </form>
 
        <p class="text-center text-sm text-[#736868] mt-8">
            Belum punya akun? 
            <a href="/register" class="text-[#e06553] font-semibold hover:text-[#cd5543] transition">Daftar</a>
        </p>
    </div>

</body>
</html>