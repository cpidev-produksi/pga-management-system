@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 sm:p-6 lg:p-8">
    
    <header class="flex items-center justify-between pb-6 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Scan QR Code Pengunjung</h1>
        </div>
    </header>

    <div class="mt-8 max-w-xl mx-auto">
        {{-- VIEW SCANNER --}}
        <div id="scanner-view">
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div id="reader" class="w-full border rounded-lg overflow-hidden"></div>
                
                {{-- Area Notifikasi (Error/Warning) --}}
                <div id="feedback-area" class="hidden mt-4 p-4 rounded-lg text-center font-medium border transition-all duration-300">
                    <div id="feedback-icon" class="text-3xl mb-2"></div>
                    <div id="feedback-message" class="mb-4"></div>
                    
                    {{-- TOMBOL MANUAL RESUME (Default Hidden, Muncul via JS) --}}
                    <button id="btn-resume" onclick="manualResume()" class="hidden w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg transition shadow-sm">
                        Scan QR Lain
                    </button>
                </div>

                <div class="mt-4 text-center text-sm text-gray-500">
                    Arahkan kamera ke QR Code. Pastikan pencahayaan cukup.
                </div>
            </div>
        </div>

        {{-- VIEW SUKSES (Hanya muncul jika scan BERHASIL) --}}
        <div id="success-view" style="display: none;">
            <div class="bg-white p-8 rounded-2xl shadow-sm text-center">
                <div class="mb-6">
                    <i class="fa-solid fa-circle-check text-6xl text-green-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Scan Berhasil!</h2>
                <p id="success-name" class="text-gray-600 mb-8 font-medium"></p>
                
                <div class="flex flex-col gap-3">
                    <a id="btn-detail-visitor" href="#" class="w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-3 px-4 rounded-lg transition shadow-sm flex items-center justify-center">
                        <i class="fa-regular fa-eye mr-2"></i> Lihat Detail Visitor
                    </a>

                    <button onclick="location.reload()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                        Scan Pengunjung Berikutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scannerView = document.getElementById('scanner-view');
        const successView = document.getElementById('success-view');
        
        const feedbackArea = document.getElementById('feedback-area');
        const feedbackMsg = document.getElementById('feedback-message');
        const feedbackIcon = document.getElementById('feedback-icon');
        const btnResume = document.getElementById('btn-resume');
        
        const successName = document.getElementById('success-name');
        const btnDetailVisitor = document.getElementById('btn-detail-visitor');
        
        let isProcessing = false; 
        let html5QrcodeScanner = null;

        const detailRouteTemplate = "{{ route('visitors.show', ':uuid') }}";

        function showFeedback(message, type) {
            feedbackArea.classList.remove('hidden', 'bg-red-50', 'border-red-200', 'text-red-700', 'bg-yellow-50', 'border-yellow-200', 'text-yellow-700', 'bg-blue-50', 'border-blue-200', 'text-blue-700');
            btnResume.classList.add('hidden');

            if (type === 'error') {
                feedbackArea.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                feedbackIcon.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
                btnResume.classList.remove('hidden');
            } else if (type === 'warning') {
                feedbackArea.classList.add('bg-yellow-50', 'border-yellow-200', 'text-yellow-700');
                feedbackIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                btnResume.classList.remove('hidden');
            } else {
                feedbackArea.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
                feedbackIcon.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            }

            feedbackMsg.innerText = message;
        }

        window.manualResume = function() {
            location.reload(); 
        };

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return; 
            isProcessing = true;

            try {
                if (html5QrcodeScanner) {
                    try {
                        html5QrcodeScanner.pause(true);
                    } catch (err) {
                        console.log("Ignored pause error:", err);
                    }
                }
            } catch (e) {
                console.log("Scanner pause error ignored:", e);
            }

            showFeedback('Memeriksa data...', 'loading');

            const actionUrl = `{{ url('visitors') }}/${decodedText}/scan`;
            const csrfToken = '{{ csrf_token() }}';

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    try { 
                        if(html5QrcodeScanner) html5QrcodeScanner.clear(); 
                    } catch(e){}
                    
                    successName.innerText = data.message;

                    if (data.uuid) {
                        const finalUrl = detailRouteTemplate.replace(':uuid', data.uuid);
                        btnDetailVisitor.href = finalUrl;
                    }

                    scannerView.style.display = 'none';
                    successView.style.display = 'block';

                } else {
                    const type = data.status === 'warning' ? 'warning' : 'error';
                    showFeedback(data.message, type);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showFeedback('Gagal menghubungi server atau koneksi terputus.', 'error');
                btnResume.classList.remove('hidden');
            })
            .finally(() => {
                isProcessing = false;
            });
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        try {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    rememberLastUsedCamera: true,
                    aspectRatio: 1.0,
                    videoConstraints: {
                        facingMode: "environment" 
                    }
                },
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        } catch (e) {
            console.error("Scanner init error:", e);
            showFeedback("Gagal menginisialisasi kamera. Silakan refresh halaman.", "error");
        }


    });
</script>
@endsection