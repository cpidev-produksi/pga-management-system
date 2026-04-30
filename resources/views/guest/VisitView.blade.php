<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Tamu - PT. Charoen Pokphand Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .container-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .page-content {
            transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
            will-change: opacity, transform;
        }
        .page-hidden {
            opacity: 0;
            transform: translateY(10px);
            pointer-events: none;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        
        .iti { width: 100%; }
        .iti__flag { background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png"); }
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti__flag { background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png"); }
        }

        .ts-control {
        border-radius: 0.375rem !important; /* rounded-md */
        padding: 0.5rem 0.75rem !important; /* px-3 py-2 */
        background-color: #f9fafb !important; /* bg-gray-50 */
        border: 1px solid #d1d5db !important; /* border-gray-300 */
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5) !important; /* focus:ring-blue-500 */
            border-color: #3b82f6 !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
    
    <nav class="bg-white shadow-md py-4 px-8 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center">
            <img src="assets/img/logo-cpi.png" alt="CPI Logo" class="h-10 mr-4">
            <span class="text-xl font-bold hidden md:inline">PT. Charoen Pokphand Indonesia</span>
            <span class="text-xl font-bold md:hidden">PT. CPI</span>
        </div>
        <div class="text-gray-600 font-medium text-sm md:text-base">Plant Salatiga</div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4">
        
        <div id="main-page" class="relative container-card page-content max-w-4xl p-10 text-center flex flex-col items-center justify-center min-h-[400px] bg-white rounded-2xl shadow-xl">

            <div class="bg-blue-50 p-4 rounded-full mb-6">
                <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-extrabold mb-3 text-gray-900 tracking-tight">Sistem Penerimaan Tamu</h1>
            <p class="text-lg md:text-xl mb-2 text-gray-600 font-medium">PT. Charoen Pokphand Indonesia</p>
            
            <div class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-sm font-medium mb-8">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Plant Salatiga
            </div>

            <button id="btn-reservasi" class="group relative bg-blue-600 text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 ease-in-out">
                <span class="flex items-center">
                    Reservasi Sekarang
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </span>
            </button>
            
            <p class="mt-6 text-sm text-gray-400">*Mohon siapkan KTP/Identitas diri Anda untuk pengisian data.</p>
        </div>

        <div id="peraturan-page" class="container-card page-content page-hidden max-w-4xl p-10" style="display: none;">
            <h2 class="text-2xl font-bold mb-6 text-center">Tata Tertib Perusahaan</h2>
            <div class="text-gray-700 text-left mb-6 max-h-[50vh] overflow-y-auto min-h-[300px]">
                <img src="assets/img/poster-peraturan-min.png" alt="Poster Peraturan Perusahaan" class="w-full h-auto rounded-lg shadow-md" loading="eager" decoding="async" width="600" height="800">
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-6 shadow-sm flex items-start transition-colors hover:bg-blue-100/50">
                <div class="flex items-center h-5 mt-0.5">
                    <input type="checkbox" id="check-peraturan" class="h-5 w-5 rounded text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                </div>
                <div class="ml-3 cursor-pointer" onclick="document.getElementById('check-peraturan').click()">
                    <label for="check-peraturan" class="font-bold text-gray-800 text-sm md:text-base cursor-pointer">
                        Saya telah membaca dan menyetujui semua peraturan di atas.
                    </label>
                    <label for="check-peraturan" class="block text-xs text-gray-500 italic cursor-pointer mt-0.5">
                        I have read and agreed to all the regulations above.
                    </label>
                </div>
            </div>
            <button id="btn-setuju" class="w-full group relative bg-blue-600 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300 ease-in-out disabled:bg-gray-300 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0" disabled>
                <span class="flex flex-col items-center leading-none gap-1">
                    <span class="text-base md:text-lg">Setuju & Lanjutkan</span>
                    <span class="text-[10px] md:text-xs font-normal italic opacity-80">Agree & Continue</span>
                </span>
            </button>
        </div>
        <!-- K3/kebijakan step removed, layout cleaned up. -->

        <div id="form-page" class="container-card page-content page-hidden max-w-7xl mx-auto p-8" style="display: none;">
                        @if ($errors->any())
                            <div class="mb-6">
                                <ul class="text-red-600 text-sm list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
            <form action="{{ route('reservasi.store') }}" method="POST">
                @csrf
                
                <div class="flex flex-col mb-10 border-b pb-6">
                    <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Formulir Reservasi Online
                    </h2>
                    <p class="text-gray-500 text-sm mt-1 ml-11">Online Reservation Form</p>
                </div>

                <div class="grid lg:grid-cols-2 gap-x-16 gap-y-10">
                    
                    <div class="space-y-6">
                        <div class="border-b pb-2 mb-6">
                            <h3 class="text-xl font-bold text-gray-800">General Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Visitor Type <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Tipe Pengunjung</span>
                            </label>
                            <div class="md:col-span-2 flex space-x-6">
                                <div class="flex items-center"><input type="radio" id="visitor-domestic" name="visitor_type" value="Domestic" {{ old('visitor_type', 'Domestic') == 'Domestic' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"><label for="visitor-domestic" class="ml-2 text-sm text-gray-700">Domestic</label></div>
                                <div class="flex items-center"><input type="radio" id="visitor-international" name="visitor_type" value="International" {{ old('visitor_type') == 'International' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"><label for="visitor-international" class="ml-2 text-sm text-gray-700">International</label></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start border-b border-gray-100 pb-4 mb-4">
                            <label class="md:col-span-1 block pt-1">
                                <span class="text-sm font-medium text-gray-700">Visitor Category <span class="text-red-500">*</span></span>
                                <span class="block text-xs text-gray-400 font-normal italic">Kategori Tamu</span>
                            </label>
                            <div class="md:col-span-2 flex flex-col sm:flex-row sm:space-x-8 space-y-3 sm:space-y-0">
                                <div class="flex items-start">
                                    <input type="radio" id="cat-external" name="special_category" value="External" {{ old('special_category', 'External') == 'External' ? 'checked' : '' }} class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                    <label for="cat-external" class="ml-3 cursor-pointer">
                                        <span class="block text-sm font-medium text-gray-700">External Guest</span>
                                        <span class="block text-xs text-gray-500 italic">Tamu Luar Perusahaan</span>
                                    </label>
                                </div>
                                <div class="flex items-start">
                                    <input type="radio" id="cat-internal" name="special_category" value="Internal" {{ old('special_category') == 'Internal' ? 'checked' : '' }} class="mt-0.5 h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                    <label for="cat-internal" class="ml-3 cursor-pointer">
                                        <span class="block text-sm font-medium text-gray-700">Internal / Co-Worker</span>
                                        <span class="block text-xs text-gray-500 italic">Tamu Internal / Rekan Kerja</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="id-card" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                <span id="label-id-main">ID Card</span> <span class="text-red-500">*</span>
                                <span id="label-id-sub" class="block text-xs text-gray-400 font-normal italic">No. KTP/SIM/Paspor</span>
                            </label>
                            <div class="md:col-span-2 relative flex flex-col">
                                <div class="relative flex-grow">
                                    <input type="password" id="id-card" name="id_card" value="{{ old('id_card') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-3 pr-10 py-2 uppercase placeholder-gray-400 bg-gray-50" placeholder="KTP / SIM No." autocomplete="off" maxlength="20">
                                    <button type="button" onclick="toggleNikVisibility()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" tabindex="-1">
                                        <svg id="eye-icon-open" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg id="eye-icon-closed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('id_card')<p class="text-right text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="name" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Name <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Nama Lengkap</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Name">
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="age" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Age <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Usia</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="number" id="age" name="age" value="{{ old('age') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" min="0" placeholder="Age">
                                @error('age')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="phone" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">No. Telepon</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 text-gray-900 bg-gray-50">
                                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                <input type="hidden" id="full_phone" name="full_phone">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="company-display" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                <span id="label-company-main">Institution Name</span> <span class="text-red-500">*</span>
                                <span id="label-company-sub" class="block text-xs text-gray-400 font-normal italic">Nama Instansi</span>
                            </label>
                            
                            <div class="md:col-span-2">
                                <div class="flex shadow-sm rounded-md">
                                    <span id="company-prefix" class="hidden inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-500 sm:text-sm select-none whitespace-nowrap">
                                        <span class="sm:hidden text-xs font-semibold">PT. CPI -</span>
                                        <span class="hidden sm:inline">PT. Charoen Pokphand Indonesia -</span>
                                    </span>
                                    
                                    <input type="text" id="company-display" 
                                           class="flex-1 block w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50 transition-all min-w-0" 
                                           placeholder="Institution Name">
                                </div>
                                <input type="hidden" id="company-real" name="company" value="{{ old('company') }}">
                                
                                @error('company')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="address" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Address <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Alamat</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="address" name="address" value="{{ old('address') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Address">
                                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="email" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Alamat Email</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="email" id="email" name="email" value="{{ old('email') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Email Address">
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="border-b pb-2 mb-6 hidden lg:block">
                            <h3 class="text-xl font-bold text-gray-800">Visit Info</h3>
                        </div>
                        <div class="border-b pb-2 mb-6 lg:hidden">
                            <h3 class="text-xl font-bold text-gray-800">Visit Info</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Date & Time <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Tanggal & Waktu</span>
                            </label>
                            <div class="md:col-span-2 flex gap-4">
                                <div class="w-2/3">
                                    <input type="date" id="visit-date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50">
                                </div>
                                <div class="w-1/3">
                                    <input type="time" id="visit-time" name="visit_time" value="{{ old('visit_time', date('H:i')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50 text-center">
                                </div>
                            </div>
                        </div>





                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="intended_employee" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Intended Employee <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Karyawan yang dituju</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="intended_employee" name="intended_employee" value="{{ old('intended_employee') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Nama karyawan yang dituju">
                                @error('intended_employee')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                            <label for="necessity" class="md:col-span-1 block text-sm font-medium text-gray-700 pt-2">
                                Necessity <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Kebutuhan</span>
                            </label>
                            <div class="md:col-span-2 space-y-3">
                                <select id="necessity" name="necessity" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50">
                                    <option value="" disabled selected>Select Purpose</option>
                                    @foreach(['Meeting', 'Audit', 'Vendor', 'Lainnya'] as $p)
                                        <option value="{{ $p }}" {{ old('necessity') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                                <textarea id="write-necessity" name="write_necessity" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Write the necessities">{{ old('write_necessity') }}</textarea>
                                @error('necessity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @error('write_necessity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700 pt-1">
                                Entering Production Area? <span class="text-red-500">*</span>
                                 <span class="block text-xs text-gray-400 font-normal italic">Masuk Produksi Area?</span>
                            </label>
                            <div class="flex gap-4">
                                
                                {{-- OPSI: TIDAK (Default) --}}
                                <label class="inline-flex items-center">
                                    {{-- Logic: Jika old('is_production') adalah '0' ATAU belum ada old sama sekali (default), maka checked --}}
                                    <input type="radio" 
                                        name="is_production" 
                                        value="0" 
                                        class="form-radio text-gray-600 cursor-pointer" 
                                        {{ old('is_production', '0') == '0' ? 'checked' : '' }} 
                                        required>
                                    <span class="ml-2 cursor-pointer">Tidak (No)</span>
                                </label>

                                {{-- OPSI: YA --}}
                                <label class="inline-flex items-center">
                                    {{-- Logic: Jika old('is_production') adalah '1', maka checked --}}
                                    <input type="radio" 
                                        name="is_production" 
                                        value="1" 
                                        class="form-radio text-indigo-600 cursor-pointer" 
                                        {{ old('is_production') == '1' ? 'checked' : '' }} 
                                        required>
                                    <span class="ml-2 cursor-pointer">Ya (Yes)</span>
                                </label>
                                
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700 pt-1">
                                Special Category <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Kategori Khusus</span>
                            </label>
                            <div class="md:col-span-2 flex flex-wrap gap-4">
                                <div class="flex items-center"><input type="radio" id="no-special" name="special_needs" value="No Special Category" {{ old('special_needs', 'No Special Category') == 'No Special Category' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="no-special" class="ml-2 text-sm text-gray-700">None</label></div>
                                <div class="flex items-center"><input type="radio" id="special-disability" name="special_needs" value="Disability" {{ old('special_needs') == 'Disability' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="special-disability" class="ml-2 text-sm text-gray-700">Disability</label></div>
                                <div class="flex items-center"><input type="radio" id="special-pregnant" name="special_needs" value="Pregnant" {{ old('special_needs') == 'Pregnant' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="special-pregnant" class="ml-2 text-sm text-gray-700">Pregnant</label></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Group <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Rombongan</span>
                            </label>
                            <div class="md:col-span-2 flex space-x-6">
                                <div class="flex items-center"><input type="radio" id="group-no" name="group_type" value="Tidak Rombongan" {{ old('group_type', 'Tidak Rombongan') == 'Tidak Rombongan' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="group-no" class="ml-2 text-sm text-gray-700">Individual</label></div>
                                <div class="flex items-center"><input type="radio" id="group-yes" name="group_type" value="Rombongan" {{ old('group_type') == 'Rombongan' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="group-yes" class="ml-2 text-sm text-gray-700">Group</label></div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="mt-12 pt-8 border-t border-gray-200">
                    
                    <div id="vehicle-section" class="mb-12">
                        <div class="border-b pb-2 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                Vehicle Information
                            </h3>
                        </div>
                        <div id="vehicle-list" class="space-y-4">
                            @php
                                $oldVehicles = old('vehicle-no') ? old('vehicle-no') : ['']; 
                            @endphp

                            @foreach($oldVehicles as $index => $plate)
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 vehicle-row-item relative shadow-sm">
                                <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">Type<span class="block text-xs text-gray-400 font-normal italic">Jenis</span></label>
                                        <div class="md:col-span-2 flex space-x-4">
                                            <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Roda 2" {{ old("vehicle_type.$index") == 'Roda 2' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Bike (R2)</label></div>
                                            <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Roda 4" {{ old("vehicle_type.$index") == 'Roda 4' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Car (R4)</label></div>
                                            <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Lain-lain" {{ old("vehicle_type.$index") == 'Lain-lain' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Other</label></div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">Plate No.<span class="block text-xs text-gray-400 font-normal italic">Nopol</span></label>
                                        <div class="md:col-span-2"><input type="text" name="vehicle-no[]" value="{{ $plate }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 uppercase bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="ex: L 1234 AB"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-vehicle-btn" class="mt-4 text-blue-600 font-semibold text-sm flex items-center hover:text-blue-800 transition-colors">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Add Vehicle
                        </button>
                    </div>

                    <div id="group-section" class="mb-10" style="display: none;">
                        <div class="border-b pb-2 mb-6">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Group Passengers
                            </h3>
                        </div>
                        <div id="passenger-list" class="space-y-4">
                            @php
                                $oldPassengers = old('passenger-name') ? old('passenger-name') : [''];
                            @endphp

                            @foreach($oldPassengers as $index => $name)
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 passenger-row-item relative shadow-sm">
                                <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                            <span class="passenger-label-main">ID No.</span>
                                            <span class="passenger-label-sub block text-xs text-gray-400 font-normal italic">No. Identitas</span>
                                        </label>
                                        <div class="md:col-span-2 relative"> 
                                            <input type="password" name="passenger-id[]" value="{{ old("passenger-id.$index") }}" class="passenger-input-id block w-full border-gray-300 rounded-md shadow-sm sm:text-sm pl-3 pr-10 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="KTP / Passport" autocomplete="off">
                                            <button type="button" onclick="toggleDynamicId(this)" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" tabindex="-1">
                                                <svg class="h-5 w-5 hidden icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                <svg class="h-5 w-5 icon-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">Name<span class="block text-xs text-gray-400 font-normal italic">Nama</span></label>
                                        <div class="md:col-span-2"><input type="text" name="passenger-name[]" value="{{ $name }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Full Name"></div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">Age<span class="block text-xs text-gray-400 font-normal italic">Usia</span></label>
                                        <div class="md:col-span-2"><input type="number" name="passenger-age[]" value="{{ old("passenger-age.$index") }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" min="0" placeholder="Age"></div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <label class="md:col-span-1 block text-sm font-medium text-gray-700">Phone<span class="block text-xs text-gray-400 font-normal italic">No. Ponsel</span></label>
                                        <div class="md:col-span-2"><input type="tel" name="passenger-phone[]" value="{{ old("passenger-phone.$index") }}" class="passenger-phone-dynamic block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-passenger-btn" class="mt-4 text-blue-600 font-semibold text-sm flex items-center hover:text-blue-800 transition-colors">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Add Passenger
                        </button>
                    </div>

                    <div class="flex flex-col items-center space-y-6 pt-6">
                        <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg border border-gray-100 w-full max-w-lg">
                            <input type="checkbox" id="agreement" name="agreement" required class="h-5 w-5 rounded-sm text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                            <div class="ml-3">
                                <label for="agreement" class="text-gray-700 text-sm block font-medium cursor-pointer">Saya yakin data yang saya isikan sudah benar.</label>
                                <label for="agreement" class="text-gray-400 text-xs italic block cursor-pointer">I confirm that the data provided is correct.</label>
                            </div>
                        </div>
                        

                        
                        <button type="submit" id="btn-submit" class="bg-blue-600 text-white font-bold py-4 px-12 rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all w-full max-w-sm flex justify-center items-center gap-2">
                            Submit Reservation
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="videoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('videoModal')"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex justify-between items-center border-b">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Tutorial Pengisian Reservasi Visitor
                        </h3>
                        <button type="button" onclick="toggleModal('videoModal')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="bg-gray-50 p-6">
                        <div class="aspect-w-16 aspect-h-9 relative" style="padding-bottom: 56.25%; height: 0;">
                            <div class="bg-gray-50 p-6">
                                <div class="aspect-w-16 aspect-h-9 relative" style="padding-bottom: 56.25%; height: 0;">
                                    
                                <video 
                                    id="tutorialVideo" 
                                    class="absolute top-0 left-0 w-full h-full rounded-md shadow-sm bg-black" 
                                    controls 
                                    controlsList="nodownload" 
                                    oncontextmenu="return false;">
                                    
                                    <source src="assets/videos/tutorial-reservasi.mp4" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>

                                </div>
                            </div>
                            
                            </div>
                    </div>
                </div>
            </div>
        </div>



        <div id="loading-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm hidden transition-opacity duration-300">
            <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col items-center max-w-sm w-full mx-4">
                <div class="relative mb-6">
                    <div class="h-16 w-16 rounded-full border-4 border-gray-200"></div>
                    <div class="h-16 w-16 rounded-full border-4 border-blue-600 border-t-transparent animate-spin absolute top-0 left-0"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Mohon Tunggu</h3>
                <p class="text-gray-500 text-center text-sm">Sedang menyimpan data...</p>
            </div>
        </div>

    </main>

    <footer class="p-4 text-center text-sm text-gray-500 bg-white border-t">
        Copyright &copy; 2025 PT. Charoen Pokphand Indonesia. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>



    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            const video = document.getElementById('tutorialVideo');
            
            if (modal.classList.contains('hidden')) {
                // === SAAT MEMBUKA MODAL ===
                modal.classList.remove('hidden');
                // Opsional: Jika ingin video otomatis main saat dibuka, hapus tanda komentar di bawah:
                // if(video) video.play();
            } else {
                // === SAAT MENUTUP MODAL ===
                modal.classList.add('hidden');
                
                // Logika PASTI BERHENTI untuk tag <video>
                if(video) {
                    video.pause();       // Matikan video
                    video.currentTime = 0; // Reset video ke menit 0:00 (awal)
                }
            }
        }

        // Tambahan: Tutup modal jika user menekan tombol ESC di keyboard
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('videoModal');
            if(event.key === "Escape" && !modal.classList.contains('hidden')){
                toggleModal('videoModal');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. PAGE NAVIGATION LOGIC (CLEANED)
            const mainPage = document.getElementById('main-page');
            const peraturanPage = document.getElementById('peraturan-page');
            const formPage = document.getElementById('form-page');
            const btnReservasi = document.getElementById('btn-reservasi');
            const btnSetuju = document.getElementById('btn-setuju');
            const checkPeraturan = document.getElementById('check-peraturan');

            function showPage(pageToShow) {
                const currentPage = document.querySelector('.page-content:not([style*="display: none"])');
                if (currentPage) {
                    currentPage.classList.add('page-hidden');
                    setTimeout(() => {
                        currentPage.style.display = 'none';
                        pageToShow.style.display = 'block';
                        setTimeout(() => {
                            pageToShow.classList.remove('page-hidden');
                        }, 20);
                    }, 400);
                } else {
                    pageToShow.style.display = 'block';
                    pageToShow.classList.remove('page-hidden');
                }
            }

            // Check Error Laravel
            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
            if (hasErrors) {
                mainPage.style.display = 'none';
                formPage.style.display = 'block';
                formPage.classList.remove('page-hidden');
                checkPeraturan.checked = true;
                btnSetuju.disabled = false;
            }

            btnReservasi.addEventListener('click', () => showPage(peraturanPage));
            btnSetuju.addEventListener('click', () => showPage(formPage));
            checkPeraturan.addEventListener('change', (e) => { btnSetuju.disabled = !e.target.checked; });

            // 2. INTL-TEL-INPUT CONFIG
            const commonOptions = {
                initialCountry: "id", 
                separateDialCode: true, 
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js", 
                preferredCountries: ["id", "us", "sg", "my", "th", "vn"],
            };
            function initIti(inputElement) {
                if (inputElement && !inputElement.classList.contains('iti-active')) {
                    window.intlTelInput(inputElement, commonOptions);
                    inputElement.classList.add('iti-active'); 
                }
            }
            const phoneInput = document.querySelector("#phone");
            const emergencyInput = document.querySelector("#emergency-phone");
            if(phoneInput) initIti(phoneInput);
            if(emergencyInput) initIti(emergencyInput);
            document.querySelectorAll('.passenger-phone-dynamic').forEach(input => initIti(input));

            // 3. VEHICLE LOGIC
            const addVehicleBtn = document.getElementById('add-vehicle-btn');
            const vehicleList = document.getElementById('vehicle-list');
            let vehicleCounter = {{ count(old('vehicle-no') ?? ['']) }};
            addVehicleBtn.addEventListener('click', () => {
                const newRowHTML = `
                <div class="bg-gray-50 rounded-lg p-6 border vehicle-row-item relative">
                    <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Type<span class="block text-xs text-gray-400 font-normal italic">Jenis</span></label>
                            <div class="md:col-span-2 flex space-x-4">
                                <div class="flex items-center"><input type="radio" name="vehicle_type[${vehicleCounter}]" value="Roda 2" class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Bike (R2)</label></div>
                                <div class="flex items-center"><input type="radio" name="vehicle_type[${vehicleCounter}]" value="Roda 4" class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Car (R4)</label></div>
                                <div class="flex items-center"><input type="radio" name="vehicle_type[${vehicleCounter}]" value="Lain-lain" class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Other</label></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Plate No.<span class="block text-xs text-gray-400 font-normal italic">Nopol</span></label>
                            <div class="md:col-span-2"><input type="text" name="vehicle-no[]" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 uppercase bg-white" placeholder="ex: L 1234 AB"></div>
                        </div>
                    </div>
                </div>`;
                vehicleList.insertAdjacentHTML('beforeend', newRowHTML);
                vehicleCounter++;
            });

            // 4. PASSENGER LOGIC
            const addPassengerBtn = document.getElementById('add-passenger-btn');
            const passengerList = document.getElementById('passenger-list');
            addPassengerBtn.addEventListener('click', () => {
                const newRowHTML = `
                <div class="bg-gray-50 rounded-lg p-6 border passenger-row-item relative mt-4">
                    <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                <span class="passenger-label-main">ID No.</span>
                                <span class="passenger-label-sub block text-xs text-gray-400 font-normal italic">No. Identitas</span>
                            </label>
                            <div class="md:col-span-2 relative"> 
                                <input type="password" name="passenger-id[]" class="passenger-input-id block w-full border-gray-300 rounded-md shadow-sm sm:text-sm pl-3 pr-10 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="KTP / Passport" autocomplete="off">
                                <button type="button" onclick="toggleDynamicId(this)" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" tabindex="-1">
                                    <svg class="h-5 w-5 hidden icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    <svg class="h-5 w-5 icon-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Name<span class="block text-xs text-gray-400 font-normal italic">Nama</span></label>
                            <div class="md:col-span-2"><input type="text" name="passenger-name[]" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white" placeholder="Full Name"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Age<span class="block text-xs text-gray-400 font-normal italic">Usia</span></label>
                            <div class="md:col-span-2"><input type="number" name="passenger-age[]" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white" min="0" placeholder="Age"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Phone<span class="block text-xs text-gray-400 font-normal italic">No. Ponsel</span></label>
                            <div class="md:col-span-2"><input type="tel" name="passenger-phone[]" class="passenger-phone-dynamic block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white"></div>
                        </div>
                    </div>
                </div>`;
                
                passengerList.insertAdjacentHTML('beforeend', newRowHTML);
                const allDynamicPhones = passengerList.querySelectorAll('.passenger-phone-dynamic');
                const lastPhoneInput = allDynamicPhones[allDynamicPhones.length - 1];
                initIti(lastPhoneInput);

                // PENTING: Update label baris baru agar sesuai status Radio Button saat ini
                updateIdLabel(); 
            });

            // Remove Row Logic
            document.getElementById('form-page').addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-row');
                if (removeBtn && !removeBtn.disabled) {
                    const vehicleRow = e.target.closest('.vehicle-row-item');
                    if (vehicleRow) vehicleRow.remove();
                    const passengerRow = e.target.closest('.passenger-row-item');
                    if (passengerRow) passengerRow.remove();
                }
            });

            // 5. GROUP TOGGLE
            const groupYes = document.getElementById('group-yes');
            const groupNo = document.getElementById('group-no');
            const groupSection = document.getElementById('group-section');
            function toggleGroupSettings() {
                if (groupNo.checked) {
                    groupSection.style.display = 'none';
                } else {
                    groupSection.style.display = 'block';
                }
            }
            groupYes.addEventListener('change', toggleGroupSettings);
            groupNo.addEventListener('change', toggleGroupSettings);
            toggleGroupSettings();

            // 6. NIK TOGGLE
            window.toggleNikVisibility = function() {
                const input = document.getElementById('id-card');
                const iconOpen = document.getElementById('eye-icon-open');
                const iconClosed = document.getElementById('eye-icon-closed');
                if (input.type === "password") {
                    input.type = "text";
                    iconOpen.classList.remove('hidden');
                    iconClosed.classList.add('hidden');
                } else {
                    input.type = "password";
                    iconOpen.classList.add('hidden');
                    iconClosed.classList.remove('hidden');
                }
            }
            const idCardInput = document.getElementById('id-card');
            if(idCardInput) {
                idCardInput.addEventListener('input', function(e) {
                    this.value = this.value.toUpperCase();
                });
            }

            // Fungsi untuk toggle password pada elemen dinamis
            window.toggleDynamicId = function(btn) {
                // Cari parent container terdekat
                const container = btn.closest('.relative');
                const input = container.querySelector('input');
                const iconOpen = btn.querySelector('.icon-open');
                const iconClosed = btn.querySelector('.icon-closed');

                if (input.type === "password") {
                    input.type = "text";
                    iconOpen.classList.remove('hidden');
                    iconClosed.classList.add('hidden');
                } else {
                    input.type = "password";
                    iconOpen.classList.add('hidden');
                    iconClosed.classList.remove('hidden');
                }
            };

            // 7. SUBMIT
            const form = document.querySelector('form'); 
            const loadingOverlay = document.getElementById('loading-overlay');
            const btnSubmit = document.getElementById('btn-submit');
            if(form) {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) return; 
                    const updateValue = (el) => {
                        if (el && window.intlTelInputGlobals) {
                            const instance = window.intlTelInputGlobals.getInstance(el);
                            if (instance && el.value.trim() !== "") {
                                el.value = instance.getNumber(); 
                            }
                        }
                    };
                    updateValue(phoneInput);
                    updateValue(emergencyInput);
                    document.querySelectorAll('.passenger-phone-dynamic').forEach(input => updateValue(input));
                    if (loadingOverlay) {
                        loadingOverlay.classList.remove('hidden');
                        loadingOverlay.classList.add('flex'); 
                    }
                    if (btnSubmit) {
                        btnSubmit.disabled = true;
                        btnSubmit.innerHTML = 'Memproses...';
                        btnSubmit.classList.add('opacity-75', 'cursor-wait');
                    }
                });
            }

            // 8. LOGIC FORM STATE & DATA SYNC
            const catExternal = document.getElementById('cat-external');
            const catInternal = document.getElementById('cat-internal');
            
            // Elemen Label
            const labelIdMain = document.getElementById('label-id-main');
            const labelIdSub = document.getElementById('label-id-sub');
            const labelCompMain = document.getElementById('label-company-main');
            const labelCompSub = document.getElementById('label-company-sub');
            const inputIdCard = document.getElementById('id-card');

            // Elemen Company Special Logic
            const compPrefix = document.getElementById('company-prefix');
            const compDisplay = document.getElementById('company-display'); // Input visual
            const compReal = document.getElementById('company-real');       // Input hidden (submit)
            const prefixText = "PT. Charoen Pokphand Indonesia - ";

            function updateFormState() {
                // Ambil elemen dinamis passenger
                const passLabelsMain = document.querySelectorAll('.passenger-label-main');
                const passLabelsSub = document.querySelectorAll('.passenger-label-sub');
                const passInputs = document.querySelectorAll('.passenger-input-id');

                if (catInternal && catInternal.checked) {
                    // =======================
                    // KONDISI: INTERNAL TAMU
                    // =======================
                    
                    // 1. Label ID -> NIK
                    if(labelIdMain) labelIdMain.textContent = "NIK / Employee ID";
                    if(labelIdSub) labelIdSub.textContent = "NIK atau Nomor Identitas Karyawan";
                    if(inputIdCard) inputIdCard.placeholder = "Masukkan NIK";

                    // 2. Label Company -> Origin Unit
                    if(labelCompMain) labelCompMain.textContent = "Origin Unit / Plant";
                    if(labelCompSub) labelCompSub.textContent = "Asal Unit / Plant (Internal)";

                    // 3. UI Company: Tampilkan Prefix
                    if(compPrefix) {
                        compPrefix.classList.remove('hidden');
                        // Ubah styling input visual agar menyatu dengan prefix
                        compDisplay.classList.remove('rounded-md');
                        compDisplay.classList.add('rounded-r-md');
                    }
                    if(compDisplay) compDisplay.placeholder = "Ex: Plant Salatiga / Head Office";

                    // 4. Update Passenger List
                    passLabelsMain.forEach(el => el.textContent = "NIK / Employee ID");
                    passLabelsSub.forEach(el => el.textContent = "NIK Karyawan");
                    passInputs.forEach(el => el.placeholder = "Masukkan NIK");

                } else {
                    // =======================
                    // KONDISI: EXTERNAL TAMU
                    // =======================

                    // 1. Label ID -> KTP
                    if(labelIdMain) labelIdMain.textContent = "ID Card";
                    if(labelIdSub) labelIdSub.textContent = "No. KTP/SIM/Paspor";
                    if(inputIdCard) inputIdCard.placeholder = "KTP / SIM No.";

                    // 2. Label Company -> Normal
                    if(labelCompMain) labelCompMain.textContent = "Institution Name";
                    if(labelCompSub) labelCompSub.textContent = "Nama Instansi / Perusahaan";

                    // 3. UI Company: Sembunyikan Prefix
                    if(compPrefix) {
                        compPrefix.classList.add('hidden');
                        // Kembalikan styling input visual normal
                        compDisplay.classList.remove('rounded-r-md');
                        compDisplay.classList.add('rounded-md');
                    }
                    if(compDisplay) compDisplay.placeholder = "Institution Name";

                    // 4. Update Passenger List
                    passLabelsMain.forEach(el => el.textContent = "ID No.");
                    passLabelsSub.forEach(el => el.textContent = "No. Identitas");
                    passInputs.forEach(el => el.placeholder = "KTP / Passport");
                }
                
                // Trigger update value saat pindah radio button agar sinkron
                syncCompanyValue();
            }

            // Fungsi Sinkronisasi Input Visual ke Input Hidden
            function syncCompanyValue() {
                if(!compDisplay || !compReal) return;

                let userTyped = compDisplay.value;

                if (catInternal.checked) {
                    // Mode Internal: Gabungkan Prefix + Ketikan User
                    // Cek agar tidak double prefix jika user iseng copas
                    if(userTyped.startsWith(prefixText)) {
                        userTyped = userTyped.replace(prefixText, '');
                    }
                    compReal.value = prefixText + userTyped;
                } else {
                    // Mode External: Isinya murni apa yang diketik
                    compReal.value = userTyped;
                }
            }

            // Fungsi untuk Mengisi Input Visual dari Data Lama (Old Value / Validation Error)
            function initCompanyValue() {
                if(!compReal.value) return;

                // Cek apakah value lama mengandung prefix "PT. Charoen..."
                if (compReal.value.startsWith(prefixText)) {
                    // Jika ya, berarti kemungkinan ini Internal
                    // Kita set visualnya hanya sisa teksnya saja
                    compDisplay.value = compReal.value.replace(prefixText, '');
                    // (Opsional) Paksa radio button ke Internal jika mau otomatis
                    // catInternal.checked = true; 
                } else {
                    // Jika tidak, tampilkan apa adanya
                    compDisplay.value = compReal.value;
                }
            }

            // Event Listeners
            if(catExternal && catInternal) {
                catExternal.addEventListener('change', updateFormState);
                catInternal.addEventListener('change', updateFormState);
                
                // Listen setiap ketikan user di kolom company
                if(compDisplay) {
                    compDisplay.addEventListener('input', syncCompanyValue);
                }

                // Jalankan logika inisialisasi
                initCompanyValue(); // Load old data
                updateFormState();  // Atur tampilan awal
            }

            // Tombol Add Passenger (Update state baris baru)
            const addPassBtn = document.getElementById('add-passenger-btn');
            if(addPassBtn){
                addPassBtn.addEventListener('click', function() {
                    setTimeout(updateFormState, 50); 
                });
            }
        });
    </script>
</body>
</html>