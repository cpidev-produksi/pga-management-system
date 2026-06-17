<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plant Belum Ditetapkan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-amber-50 flex items-center justify-center">
            <i class="fa-solid fa-triangle-exclamation text-2xl text-amber-500"></i>
        </div>
        <h1 class="text-xl font-bold text-gray-800 mb-2">Akun Belum Terhubung ke Plant</h1>
        <p class="text-sm text-gray-500 leading-relaxed mb-6">
            Akun Anda belum ditetapkan pada plant manapun, sehingga belum dapat mengakses sistem.
            Silakan hubungi administrator untuk menetapkan plant pada akun Anda.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-3 px-6 rounded-xl text-white font-semibold text-sm bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar
            </button>
        </form>
    </div>
</body>
</html>
