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

        {{-- MODE PILIHAN SCAN --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm mb-4">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="text-sm text-gray-600">
                    Pilih mode scan:
                </div>
                <div class="inline-flex rounded-lg border border-gray-200 overflow-hidden">
                    <button id="mode-camera" type="button" class="px-4 py-2 text-sm font-semibold bg-blue-600 text-white">Kamera</button>
                    <button id="mode-scanner" type="button" class="px-4 py-2 text-sm font-semibold bg-white text-gray-700 hover:bg-gray-50">Scanner</button>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">
                Catatan: Scanner USB BP-OM200 bekerja dengan membaca QR Code dan mengirim otomatis. Mode kamera hanya untuk webcam / smartphone.
            </p>
        </div>

        {{-- VIEW SCANNER (KAMERA) --}}
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

        {{-- VIEW SCANNER (USB KEYBOARD) --}}
        <div id="scanner-usb-view" style="display: none;">
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Scanner</label>

                <input
                    id="usb-scan-input"
                    type="text"
                    inputmode="none"
                    autocomplete="off"
                    autocapitalize="off"
                    spellcheck="false"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    placeholder="Klik di sini (atau otomatis fokus), lalu scan QR..."
                />

                <p class="mt-3 text-xs text-gray-500">
                    Tips: Pastikan kursor berada di kolom ini. Arahkan QR Code ke scanner dan akan mengisi otomatis.
                </p>

                {{-- feedback khusus USB --}}
                <div id="feedback-area-usb" class="hidden mt-4 p-4 rounded-lg text-center font-medium border transition-all duration-300">
                    <div id="feedback-icon-usb" class="text-3xl mb-2"></div>
                    <div id="feedback-message-usb" class="mb-4"></div>
                    <button id="btn-resume-usb" type="button" class="hidden w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded-lg transition shadow-sm">
                        Scan QR Lain
                    </button>
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
        const usbView = document.getElementById('scanner-usb-view');
        const successView = document.getElementById('success-view');

        const modeCameraBtn = document.getElementById('mode-camera');
        const modeScannerBtn = document.getElementById('mode-scanner');

        const feedbackArea = document.getElementById('feedback-area');
        const feedbackMsg = document.getElementById('feedback-message');
        const feedbackIcon = document.getElementById('feedback-icon');
        const btnResume = document.getElementById('btn-resume');

        const feedbackAreaUsb = document.getElementById('feedback-area-usb');
        const feedbackMsgUsb = document.getElementById('feedback-message-usb');
        const feedbackIconUsb = document.getElementById('feedback-icon-usb');
        const btnResumeUsb = document.getElementById('btn-resume-usb');

        const usbInput = document.getElementById('usb-scan-input');

        const successName = document.getElementById('success-name');
        const btnDetailVisitor = document.getElementById('btn-detail-visitor');

        let isProcessing = false;
        let html5QrcodeScanner = null;

        const detailRouteTemplate = "{{ route('visitors.show', ':uuid') }}";

        function showFeedbackGeneric(area, msgEl, iconEl, btnEl, message, type) {
            area.classList.remove(
                'hidden',
                'bg-red-50', 'border-red-200', 'text-red-700',
                'bg-yellow-50', 'border-yellow-200', 'text-yellow-700',
                'bg-blue-50', 'border-blue-200', 'text-blue-700'
            );
            btnEl.classList.add('hidden');

            if (type === 'error') {
                area.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
                iconEl.innerHTML = '<i class="fa-solid fa-circle-xmark"></i>';
                btnEl.classList.remove('hidden');
            } else if (type === 'warning') {
                area.classList.add('bg-yellow-50', 'border-yellow-200', 'text-yellow-700');
                iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                btnEl.classList.remove('hidden');
            } else {
                area.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
                iconEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            }

            msgEl.innerText = message;
        }

        function showFeedback(message, type) {
            showFeedbackGeneric(feedbackArea, feedbackMsg, feedbackIcon, btnResume, message, type);
        }

        function showFeedbackUsb(message, type) {
            showFeedbackGeneric(feedbackAreaUsb, feedbackMsgUsb, feedbackIconUsb, btnResumeUsb, message, type);
        }

        window.manualResume = function() {
            location.reload();
        };

        function setMode(mode) {
            // Reset success view ketika ganti mode
            successView.style.display = 'none';

            if (mode === 'camera') {
                usbView.style.display = 'none';
                scannerView.style.display = 'block';

                modeCameraBtn.classList.add('bg-blue-600', 'text-white');
                modeCameraBtn.classList.remove('bg-white', 'text-gray-700');

                modeScannerBtn.classList.add('bg-white', 'text-gray-700');
                modeScannerBtn.classList.remove('bg-blue-600', 'text-white');

                // start camera if needed
                if (!html5QrcodeScanner) initCameraScanner();
            } else {
                scannerView.style.display = 'none';
                usbView.style.display = 'block';

                modeScannerBtn.classList.add('bg-blue-600', 'text-white');
                modeScannerBtn.classList.remove('bg-white', 'text-gray-700');

                modeCameraBtn.classList.add('bg-white', 'text-gray-700');
                modeCameraBtn.classList.remove('bg-blue-600', 'text-white');

                // stop camera to release webcam
                try {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.clear();
                        html5QrcodeScanner = null;
                    }
                } catch (e) {}

                // focus input agar siap scan
                setTimeout(() => usbInput && usbInput.focus(), 50);
            }
        }

        function submitUuid(uuid, feedbackFn) {
            if (isProcessing) return;
            isProcessing = true;

            feedbackFn('Memeriksa data...', 'loading');

            const cleaned = (uuid || '').trim();
            const actionUrl = `{{ route('visitors.scan') }}`;
            const csrfToken = '{{ csrf_token() }}';

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ payload: cleaned })
            })
            .then(async (response) => {
                // Aman untuk kasus error non-JSON
                const text = await response.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error(text || 'Invalid JSON');
                }
            })
            .then(data => {
                if (data.status === 'success') {
                    successName.innerText = data.message;

                    if (data.uuid) {
                        const finalUrl = detailRouteTemplate.replace(':uuid', data.uuid);
                        btnDetailVisitor.href = finalUrl;
                    }

                    scannerView.style.display = 'none';
                    usbView.style.display = 'none';
                    successView.style.display = 'block';
                } else {
                    const type = data.status === 'warning' ? 'warning' : 'error';
                    feedbackFn(data.message, type);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                feedbackFn('Gagal menghubungi server atau koneksi terputus.', 'error');
            })
            .finally(() => {
                isProcessing = false;
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // kamera -> pakai flow yang sama
            try {
                if (html5QrcodeScanner) {
                    try { html5QrcodeScanner.pause(true); } catch (err) {}
                }
            } catch (e) {}

            submitUuid(decodedText, showFeedback);
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        function initCameraScanner() {
            try {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 },
                        rememberLastUsedCamera: true,
                        aspectRatio: 1.0,
                        videoConstraints: { facingMode: "environment" }
                    },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            } catch (e) {
                console.error("Scanner init error:", e);
                showFeedback("Gagal menginisialisasi kamera. Silakan refresh halaman.", "error");
            }
        }

        // USB scanner: proses saat Enter (suffix scanner Anda sudah Enter)
        if (usbInput) {
            usbInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const value = usbInput.value;
                    usbInput.value = '';
                    submitUuid(value, showFeedbackUsb);
                }
            });

            // jaga fokus supaya user tinggal scan terus
            usbInput.addEventListener('blur', function () {
                setTimeout(() => {
                    if (usbView.style.display !== 'none') usbInput.focus();
                }, 100);
            });
        }

        if (btnResumeUsb) {
            btnResumeUsb.addEventListener('click', function () {
                if (usbInput) usbInput.focus();
            });
        }

        if (modeCameraBtn) modeCameraBtn.addEventListener('click', () => setMode('camera'));
        if (modeScannerBtn) modeScannerBtn.addEventListener('click', () => setMode('scanner'));

        // default tetap kamera agar tidak mengubah kebiasaan user
        setMode('camera');
    });
</script>
@endsection