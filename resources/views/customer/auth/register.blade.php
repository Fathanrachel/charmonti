<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Charm.onti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#FCFBF9] min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100/50 p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-2">
            <span class="bg-linear-to-r from-rose-400 to-pink-500 bg-clip-text text-transparent">Charm.onti</span>
        </h1>
        <p class="text-center text-gray-500 text-sm mb-8">Buat akun baru ✨</p>

        @if($errors->any())
            <div class="bg-rose-50/50 text-rose-600 text-sm rounded-2xl px-4 py-3 mb-4 border border-rose-100/50">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full mt-2 px-4 py-3 border border-gray-100/50 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-300 bg-white transition"
                    placeholder="Nama kamu" required>
            </div>
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
                    placeholder="Min. 8 karakter" required>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full mt-2 px-4 py-3 border border-gray-100/50 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-300 bg-white transition"
                    placeholder="Ulangi password" required>
            </div>
            <button type="submit"
                class="w-full bg-rose-400 hover:bg-rose-500 text-white font-semibold py-3 rounded-2xl transition shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="/login" class="text-rose-500 font-medium hover:text-rose-600 transition">Masuk</a>
        </p>
    </div>

</body>
</html>