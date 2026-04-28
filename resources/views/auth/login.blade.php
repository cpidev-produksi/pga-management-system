<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login PGA</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <style>
        /* Mengatur font Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            overflow: hidden; /* Mencegah scrollbar saat animasi background bergerak */
            perspective: 1000px; /* Penting untuk efek 3D tilt pada kartu */
        }

        /* Styling khusus untuk input autofill browser agar sesuai tema */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #fef2f2 inset !important; /* bg-red-50 */
            -webkit-text-fill-color: #1f2937 !important;
        }

        /* Blur backdrop untuk efek glassmorphism ringan */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transform-style: preserve-3d; /* Untuk efek 3D */
            will-change: transform; /* Optimasi performa render */
        }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen p-4 text-gray-800">

    <!-- BACKGROUND ANIMATION ELEMENTS (GSAP TARGETS) -->
    <!-- Menambahkan data-speed untuk kecepatan parallax yang berbeda -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <!-- Blob Merah Pudar 1 (Bergerak Cepat) -->
        <div data-speed="2" class="blob absolute w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70 top-0 left-0"></div>
        <!-- Blob Merah Pudar 2 (Bergerak Sedang) -->
        <div data-speed="1.5" class="blob absolute w-96 h-96 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 top-0 right-0"></div>
        <!-- Blob Merah Lebih Pekat (Bergerak Lambat) -->
        <div data-speed="1" class="blob absolute w-80 h-80 bg-red-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 -bottom-32 left-20"></div>
    </div>

    <!-- Kontainer Formulir Login -->
    <!-- Menambahkan border-t-4 border-red-600 untuk aksen merah -->
    <div class="login-card w-full max-w-md glass-effect border-t-4 border-red-600 rounded-2xl shadow-2xl overflow-hidden p-8 opacity-0 translate-y-10">

        <div class="text-center mb-8 form-element">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2 tracking-tight">
                PGA <span class="text-red-600">System</span>
            </h1>
            <p class="text-gray-500">Silakan masuk untuk melanjutkan</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4 form-element">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                    Email
                </label>
                
                <input id="email" 
                    class="block mt-1 w-full px-4 py-3 border rounded-lg shadow-sm bg-gray-50 text-gray-800 transition-all duration-300 outline-none
                    @error('email') border-red-500 ring-1 ring-red-500 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required autofocus autocomplete="username" />
                
                @error('email')
                    <div class="mt-2 flex items-center text-sm text-red-600 animate-pulse">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="mt-4 mb-4 form-element">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                    Kata Sandi
                </label>
                <input id="password" 
                    class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 bg-gray-50 text-gray-800 transition-all duration-300 outline-none" 
                    type="password" 
                    name="password" 
                    required autocomplete="current-password" />
            </div>

            <div class="block mt-4 form-element">
                <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 focus:ring-offset-white cursor-pointer" name="remember">
                    <span class="ms-2 text-sm text-gray-600 group-hover:text-red-600 transition-colors">Ingat saya</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-6 form-element">
                <a class="underline text-sm text-gray-600 hover:text-red-600 transition-colors rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                href="{{ route('password.request') }}"> Lupa kata sandi?
                </a>

                <button type="submit" id="loginBtn" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-full font-bold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 disabled:opacity-25 transition-all duration-300 ms-3 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Masuk
                </button>
            </div>
        </form>
        
        <div class="mt-8 text-center form-element">
            <p class="text-xs text-gray-400">© 2024 PGA Management. All rights reserved.</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            
            // --- 1. Animasi Masuk (Entrance) ---
            
            // Kartu Utama
            gsap.to(".login-card", {
                duration: 1.2,
                opacity: 1,
                y: 0,
                ease: "power3.out"
            });

            // Elemen Form (Staggered)
            gsap.from(".form-element", {
                duration: 0.8,
                opacity: 0,
                y: 20,
                stagger: 0.15,
                delay: 0.4,
                ease: "back.out(1.7)"
            });

            // --- 2. Animasi Ambient (Bernafas) ---
            // Membuat blobs sedikit membesar/mengecil agar tidak terlihat mati saat mouse diam
            const blobs = document.querySelectorAll(".blob");
            blobs.forEach((blob) => {
                gsap.to(blob, {
                    scale: "random(0.8, 1.2)",
                    duration: "random(4, 8)",
                    repeat: -1,
                    yoyo: true,
                    ease: "sine.inOut"
                });
            });

            // --- 3. Animasi Mouse Move (Interactive) ---
            
            document.addEventListener("mousemove", (e) => {
                const mouseX = e.clientX;
                const mouseY = e.clientY;
                
                // Menghitung posisi mouse relatif terhadap tengah layar
                // Nilai -1 (kiri/atas) sampai 1 (kanan/bawah)
                const xPct = (mouseX / window.innerWidth) - 0.5;
                const yPct = (mouseY / window.innerHeight) - 0.5;

                // A. Parallax Effect untuk Blobs Background
                // Blobs bergerak berlawanan arah dengan mouse
                blobs.forEach((blob) => {
                    const speed = blob.getAttribute('data-speed');
                    gsap.to(blob, {
                        x: -xPct * 100 * speed, // Bergerak max 100px * speed
                        y: -yPct * 100 * speed,
                        duration: 1.5, // Durasi agak lama agar gerakan smooth (ada inertia)
                        ease: "power2.out"
                    });
                });

                // B. 3D Tilt Effect untuk Kartu Login
                // Kartu sedikit miring mengikuti mouse
                gsap.to(".login-card", {
                    rotationY: xPct * 10, // Miring kiri-kanan max 10 derajat
                    rotationX: -yPct * 10, // Miring atas-bawah max 10 derajat
                    transformPerspective: 1000, // Menambah kedalaman 3D
                    duration: 1,
                    ease: "power2.out"
                });
            });

            // --- 4. Interaksi Tombol ---
            const btn = document.getElementById('loginBtn');
            btn.addEventListener('mouseenter', () => gsap.to(btn, { scale: 1.05, duration: 0.2 }));
            btn.addEventListener('mouseleave', () => gsap.to(btn, { scale: 1, duration: 0.2 }));
            btn.addEventListener('click', (e) => {
                // Hapus e.preventDefault() jika ingin form benar-benar submit
                gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 });
            });
        });
    </script>
</body>
</html>