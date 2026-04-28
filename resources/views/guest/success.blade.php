<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket Reservasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Background Pattern Halus */
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-pattern text-slate-600 flex items-center justify-center min-h-screen p-4">
    
    <div class="animate-fade-up bg-white w-full max-w-sm rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(225,29,72,0.15)] overflow-hidden border border-slate-100 relative">
        
        <div class="h-2 w-full bg-gradient-to-r from-red-600 to-rose-500"></div>

        <div class="p-8 text-center">
            
            @if (session('success'))
                <div class="inline-flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-full text-xs font-bold mb-6 border border-green-100 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>
                        {{-- Logika: Jika pesan mengandung kata 'diperbarui' atau 'reschedule', ubah teksnya --}}
                        @if(str_contains(strtolower(session('success')), 'diperbarui') || str_contains(strtolower(session('success')), 'reschedule'))
                            Data Berhasil Disimpan
                        @else
                            Reservasi Berhasil
                        @endif
                    </span>
                </div>
                
                {{-- Menampilkan pesan detail dari Controller di bawah badge/judul --}}
                {{-- Pastikan elemen teks utama di bawah badge menggunakan session('success') agar informatif --}}
                {{-- Contoh (sesuaikan dengan desain success page Anda): --}}
                {{-- <p class="text-gray-500 text-lg">{{ session('success') }}</p> --}}
            @endif
            <h1 class="text-2xl font-extrabold text-slate-900 mb-1 tracking-tight">E-Visitor Pass</h1>
            <p class="text-sm text-slate-400 mb-6 font-medium">Hai, <span class="text-red-600 font-bold">{{ Str::limit($visitor->name ?? 'Tamu', 20) }}</span></p>

            <div class="flex justify-center mb-8 relative">
                <div class="absolute top-1/2 -left-10 w-6 h-6 bg-[#f8fafc] rounded-full"></div>
                <div class="absolute top-1/2 -right-10 w-6 h-6 bg-[#f8fafc] rounded-full"></div>

                <div id="qr-overlay-container" class="relative inline-block p-5 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                    
                    {{-- QR Code Layer --}}
                    {!! QrCode::size(220)->errorCorrection('H')->margin(1)->color(30, 41, 59)->generate($visitor->uuid ?? 'test-uuid') !!}

                    {{-- Logo Layer --}}
                    <img id="company-logo" 
                         src="{{ asset('assets/img/logo-cpi.png') }}" 
                         alt="CP Logo" 
                         class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-14 h-14 bg-white p-1 rounded-lg shadow-sm z-10 object-contain border border-slate-50"
                    >
                </div>
            </div>

            <p class="text-xs text-slate-400 mb-6 px-4 leading-relaxed">
                Scan QR Code ini di gerbang masuk utama.<br>Berlaku untuk satu kali kunjungan.
            </p>

            <div class="space-y-3">
                
                {{-- TOMBOL DOWNLOAD (BRAND COLOR) --}}
                <button onclick="downloadVisualQR()" class="group relative w-full flex justify-center items-center gap-3 py-4 px-6 rounded-2xl text-white font-bold text-sm bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 shadow-[0_10px_20px_-5px_rgba(225,29,72,0.3)] hover:shadow-[0_15px_30px_-5px_rgba(225,29,72,0.4)] transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Simpan E-Ticket</span>
                </button>

            </div>
        </div>
        
        <div class="bg-slate-50 py-3 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">PT. Charoen Pokphand Indonesia</p>
        </div>
    </div>

    {{-- Javascript Logic (Tidak berubah) --}}
    <script>
        function downloadVisualQR() {
            const container = document.getElementById('qr-overlay-container');
            const logo = document.getElementById('company-logo');
            const btn = document.querySelector('button');

            if (!container || !logo || logo.naturalWidth === 0) {
                alert("Mohon tunggu, gambar sedang dimuat...");
                return;
            }

            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="text-sm animate-pulse">Memproses...</span>';
            btn.classList.add('cursor-wait');

            html2canvas(container, {
                backgroundColor: "#ffffff",
                scale: 3, 
                useCORS: true 
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = "E-Ticket-{{ Str::slug($visitor->name ?? 'visitor') }}.png";
                link.href = canvas.toDataURL("image/png");
                link.click();

                btn.innerHTML = originalText;
                btn.classList.remove('cursor-wait');
            }).catch(err => {
                console.error("Error:", err);
                alert("Gagal mendownload.");
                btn.innerHTML = originalText;
                btn.classList.remove('cursor-wait');
            });
        }
    </script>
</body>
</html>