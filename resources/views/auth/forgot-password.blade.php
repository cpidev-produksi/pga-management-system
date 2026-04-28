<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - PGA System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; overflow: hidden; perspective: 1000px; }
        input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus, input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #fef2f2 inset !important;
            -webkit-text-fill-color: #1f2937 !important;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transform-style: preserve-3d;
            will-change: transform;
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen p-4 text-gray-800">

    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div data-speed="2" class="blob absolute w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 top-0 left-0"></div>
        <div data-speed="1.5" class="blob absolute w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 top-0 right-0"></div>
        <div data-speed="1" class="blob absolute w-80 h-80 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 -bottom-32 left-20"></div>
    </div>

    <div class="login-card w-full max-w-md glass-effect border-t-4 border-red-600 rounded-2xl shadow-2xl overflow-hidden p-8 opacity-0 translate-y-10">
        
        <div class="text-center mb-6 form-element">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">
                Reset <span class="text-red-600">Password</span>
            </h1>
            <p class="text-gray-500 text-sm">
                Masukkan email Anda, kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-lg border border-green-200 form-element">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6 form-element">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Terdaftar</label>
                <input id="email" 
                    class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 bg-gray-50 text-gray-800 transition-all duration-300 outline-none" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required autofocus />
                
                @error('email')
                    <p class="mt-2 text-sm text-red-600 font-medium animate-pulse">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-4 form-element">
                <a class="text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center gap-1" href="{{ route('login') }}">
                    &larr; Kembali Login
                </a>

                <button type="submit" id="submitBtn" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Kirim Link
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.to(".login-card", { duration: 1.2, opacity: 1, y: 0, ease: "power3.out" });
            gsap.from(".form-element", { duration: 0.8, opacity: 0, y: 20, stagger: 0.15, delay: 0.4, ease: "back.out(1.7)" });

            const blobs = document.querySelectorAll(".blob");
            blobs.forEach((blob) => {
                gsap.to(blob, { scale: "random(0.8, 1.2)", duration: "random(4, 8)", repeat: -1, yoyo: true, ease: "sine.inOut" });
            });

            document.addEventListener("mousemove", (e) => {
                const xPct = (e.clientX / window.innerWidth) - 0.5;
                const yPct = (e.clientY / window.innerHeight) - 0.5;
                blobs.forEach((blob) => {
                    const speed = blob.getAttribute('data-speed');
                    gsap.to(blob, { x: -xPct * 100 * speed, y: -yPct * 100 * speed, duration: 1.5, ease: "power2.out" });
                });
                gsap.to(".login-card", { rotationY: xPct * 10, rotationX: -yPct * 10, transformPerspective: 1000, duration: 1, ease: "power2.out" });
            });

            const btn = document.getElementById('submitBtn');
            btn.addEventListener('mouseenter', () => gsap.to(btn, { scale: 1.05, duration: 0.2 }));
            btn.addEventListener('mouseleave', () => gsap.to(btn, { scale: 1, duration: 0.2 }));
            btn.addEventListener('click', () => gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 }));
        });
    </script>
</body>
</html>