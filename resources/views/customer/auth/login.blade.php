<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-linear-to-br from-rose-100 via-pink-100 to-rose-100/90 min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-20 right-20 w-56 h-56 bg-pink-300/40 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-10 -left-20 w-80 h-80 bg-rose-300/30 rounded-full blur-3xl"></div>
    <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-pink-200/25 rounded-full blur-2xl"></div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 w-full max-w-md relative z-10">
        <h1 class="text-2xl font-bold text-center mb-2">
            <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">Charm.onti</span>
        </h1>
        <p class="text-center text-gray-500 text-sm mb-8">Masuk ke akun kamu ✨</p>

        @if($errors->any())
            <div class="bg-rose-50/50 text-rose-600 text-sm rounded-2xl px-4 py-3 mb-4 border border-rose-100/50">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full mt-2 px-4 py-3 border border-gray-100/50 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-300 bg-white transition"
                    placeholder="email@kamu.com" required>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                    class="w-full mt-2 px-4 py-3 border border-gray-100/50 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-300 bg-white transition"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-2xl transition shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun?
            <a href="/register" class="text-rose-500 font-medium hover:text-rose-600 transition">Daftar</a>
        </p>
    </div>

</body>
</html>