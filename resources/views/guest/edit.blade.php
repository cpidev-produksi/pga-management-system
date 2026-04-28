<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi - PT. Charoen Pokphand Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .container-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
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
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
    
    <nav class="bg-white shadow-md py-4 px-8 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center">
            <img src="{{ asset('assets/img/logo-cpi.png') }}" alt="CPI Logo" class="h-10 mr-4">
            <span class="text-xl font-bold hidden md:inline">PT. Charoen Pokphand Indonesia</span>
            <span class="text-xl font-bold md:hidden">PT. CPI</span>
        </div>
        <div class="text-gray-600 font-medium text-sm md:text-base">Plant Salatiga</div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4">
        
        <div id="form-page" class="container-card max-w-7xl p-8" style="display: block;">
            
            <form action="{{ route('reservasi.update', $visitor->uuid) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col mb-8 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Ubah Data / Reschedule
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Edit Reservation Data</p>
                    
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Perhatian!</strong>
                        <span class="block sm:inline">Pastikan data yang Anda ubah sudah benar. QR Code baru akan dikirimkan ke email Anda setelah disimpan.</span>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    
                    <div class="space-y-6">
                        <div class="border-b pb-2 mb-6">
                            <h3 class="text-lg font-semibold text-gray-700">General Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Visitor Type <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Tipe Pengunjung</span>
                            </label>
                            <div class="md:col-span-2 flex space-x-6">
                                <div class="flex items-center">
                                    <input type="radio" id="visitor-domestic" name="visitor_type" value="Domestic" 
                                    {{ old('visitor_type', $visitor->visit_type) == 'Domestic' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="visitor-domestic" class="ml-2 text-sm text-gray-700">Domestic</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="visitor-international" name="visitor_type" value="International" 
                                    {{ old('visitor_type', $visitor->visit_type) == 'International' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <label for="visitor-international" class="ml-2 text-sm text-gray-700">International</label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="id-card" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                ID Card <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">No. KTP/SIM/Paspor</span>
                            </label>
                            <div class="md:col-span-2 relative flex">
                                <div class="relative flex-grow">
                                    <input type="password" id="id-card" name="id_card" 
                                           value="{{ old('id_card', $visitor->identity_number) }}" 
                                           class="block w-full border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm pl-3 pr-10 py-2 uppercase placeholder-gray-400 bg-gray-50" 
                                           placeholder="KTP / SIM No." autocomplete="off" maxlength="20">
                                    <button type="button" onclick="toggleNikVisibility()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" tabindex="-1">
                                        <svg id="eye-icon-open" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg id="eye-icon-closed" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('id_card')<p class="text-right text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="name" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Name <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Nama Lengkap</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="name" name="name" 
                                       value="{{ old('name', $visitor->name) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Name">
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="age" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Age <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Usia</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="number" id="age" name="age" 
                                       value="{{ old('age', $visitor->age) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" min="0" placeholder="Age">
                                @error('age')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="phone" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">No. Telepon</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="tel" id="phone" name="phone" 
                                       value="{{ old('phone', $visitor->phone_number) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 text-gray-900 bg-gray-50">
                                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="company" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Institution Name <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Nama Instansi</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="company" name="company" 
                                       value="{{ old('company', $visitor->company_name) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Institution Name">
                                @error('company')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="address" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Address <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Alamat</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="address" name="address" 
                                       value="{{ old('address', $visitor->address) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Address">
                                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="email" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Alamat Email</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="email" id="email" name="email" 
                                       value="{{ old('email', $visitor->email) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Email Address">
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="border-b pb-2 mb-6 hidden md:block opacity-0">
                            <h3 class="text-lg font-semibold text-gray-700">Visit Info</h3>
                        </div>



                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label for="intended_employee" class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Intended Employee <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Karyawan yang dituju</span>
                            </label>
                            <div class="md:col-span-2">
                                <input type="text" id="intended_employee" name="intended_employee" value="{{ old('intended_employee', $visitor->intended_employee) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Nama karyawan yang dituju">
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
                                    @php $purposes = ['Meeting', 'Audit', 'Vendor', 'Lainnya']; @endphp
                                    @foreach($purposes as $p)
                                        <option value="{{ $p }}" {{ (old('necessity', $visitor->purpose) == $p) ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                                <textarea id="write-necessity" name="write_necessity" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50" placeholder="Write the necessities">{{ old('write_necessity', $visitor->purpose_note) }}</textarea>
                                @error('necessity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @error('write_necessity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Visit Type <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Tipe Kunjungan</span>
                            </label>
                            <div class="md:col-span-2 flex space-x-6">
                                <div class="flex items-center">
                                    <input type="radio" id="special-normal" name="special_category" value="Normal" 
                                    {{ old('special_category', $visitor->special_category) == 'Normal' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="special-normal" class="ml-2 text-sm text-gray-700">Normal</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="special-project" name="special_category" value="Project" 
                                    {{ old('special_category', $visitor->special_category) == 'Project' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="special-project" class="ml-2 text-sm text-gray-700">Project</label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700 pt-1">
                                Special Category <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Kategori Khusus</span>
                            </label>
                            <div class="md:col-span-2 flex flex-wrap gap-4">
                                <div class="flex items-center"><input type="radio" id="no-special" name="special_needs" value="No Special Category" {{ old('special_needs', $visitor->special_needs) == 'No Special Category' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="no-special" class="ml-2 text-sm text-gray-700">None</label></div>
                                <div class="flex items-center"><input type="radio" id="special-disability" name="special_needs" value="Disability" {{ old('special_needs', $visitor->special_needs) == 'Disability' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="special-disability" class="ml-2 text-sm text-gray-700">Disability</label></div>
                                <div class="flex items-center"><input type="radio" id="special-pregnant" name="special_needs" value="Pregnant" {{ old('special_needs', $visitor->special_needs) == 'Pregnant' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label for="special-pregnant" class="ml-2 text-sm text-gray-700">Pregnant</label></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Date & Time <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Tanggal & Waktu</span>
                            </label>
                            <div class="md:col-span-2 flex gap-4">
                                <input type="date" id="visit-date" name="visit_date" 
                                       value="{{ old('visit_date', $visitDate) }}" 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50">
                                <input type="time" id="visit-time" name="visit_time" 
                                       value="{{ old('visit_time', $visitTime) }}" 
                                       class="block w-24 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 bg-gray-50 text-center">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">
                                Group <span class="text-red-500">*</span>
                                <span class="block text-xs text-gray-400 font-normal italic">Rombongan</span>
                            </label>
                            <div class="md:col-span-2 flex space-x-6">
                                <div class="flex items-center">
                                    <input type="radio" id="group-no" name="group_type" value="Tidak Rombongan" 
                                    {{ old('group_type', $visitor->group_type) == 'Tidak Rombongan' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="group-no" class="ml-2 text-sm text-gray-700">Individual</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="group-yes" name="group_type" value="Rombongan" 
                                    {{ old('group_type', $visitor->group_type) == 'Rombongan' ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="group-yes" class="ml-2 text-sm text-gray-700">Group</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t">
                    
                    {{-- VEHICLE SECTION --}}
                    <div id="vehicle-section" class="mb-10">
                         <div class="border-b pb-2 mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                Vehicle Information
                            </h3>
                        </div>
                        <div id="vehicle-list" class="space-y-4">
                            @php
                                // Logic: Prioritaskan Old Input (jika validasi gagal) > Database > Kosong
                                $vehiclesSource = old('vehicle-no') 
                                    ? array_map(null, old('vehicle-no'), old('vehicle_type')) 
                                    : ($visitor->vehicles ?? []);
                            @endphp

                            @forelse($vehiclesSource as $index => $v)
                                @php
                                    // Normalisasi data (karena struktur old() dan DB sedikit beda)
                                    $num = is_array($v) ? ($v['number'] ?? $v[0]) : ''; 
                                    $typ = is_array($v) ? ($v['type'] ?? $v[1]) : '';
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-6 border vehicle-row-item relative">
                                    <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Type<span class="block text-xs text-gray-400 font-normal italic">Jenis</span></label>
                                            <div class="md:col-span-2 flex space-x-4">
                                                <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Roda 2" {{ $typ == 'Roda 2' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Bike (R2)</label></div>
                                                <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Roda 4" {{ $typ == 'Roda 4' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Car (R4)</label></div>
                                                <div class="flex items-center"><input type="radio" name="vehicle_type[{{ $index }}]" value="Lain-lain" {{ $typ == 'Lain-lain' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300"><label class="ml-2 text-sm text-gray-600">Other</label></div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Plate No.<span class="block text-xs text-gray-400 font-normal italic">Nopol</span></label>
                                            <div class="md:col-span-2"><input type="text" name="vehicle-no[]" value="{{ $num }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 uppercase bg-white" placeholder="ex: L 1234 AB"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                {{-- Jika kosong, tidak menampilkan row default (user bisa tambah sendiri) atau tampilkan 1 kosong --}}
                            @endforelse
                        </div>
                        <button type="button" id="add-vehicle-btn" class="mt-4 text-blue-600 font-semibold text-sm flex items-center hover:text-blue-800">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Add Vehicle
                        </button>
                    </div>

                    {{-- PASSENGER SECTION --}}
                    <div id="group-section" class="mb-8" style="display: none;">
                        <div class="border-b pb-2 mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Group Passengers
                            </h3>
                        </div>
                        <div id="passenger-list" class="space-y-4">
                            @php
                                $membersSource = old('passenger-name') 
                                    ? array_map(null, old('passenger-id'), old('passenger-name'), old('passenger-age'), old('passenger-phone')) 
                                    : ($visitor->group_members ?? []);
                            @endphp

                            @forelse($membersSource as $m)
                                @php
                                    // Normalisasi
                                    $pId    = is_array($m) ? ($m['id'] ?? $m[0]) : '';
                                    $pName  = is_array($m) ? ($m['name'] ?? $m[1]) : '';
                                    $pAge   = is_array($m) ? ($m['age'] ?? $m[2]) : '';
                                    $pPhone = is_array($m) ? ($m['phone'] ?? $m[3]) : '';
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-6 border passenger-row-item relative">
                                    <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">ID No.<span class="block text-xs text-gray-400 font-normal italic">No. Identitas</span></label>
                                            <div class="md:col-span-2 relative"> 
                                                <input type="password" name="passenger-id[]" value="{{ $pId }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm pl-3 pr-10 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="KTP / Passport" autocomplete="off">
                                                <button type="button" onclick="toggleDynamicId(this)" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none" tabindex="-1">
                                                    <svg class="h-5 w-5 hidden icon-open" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    <svg class="h-5 w-5 icon-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Name<span class="block text-xs text-gray-400 font-normal italic">Nama</span></label>
                                            <div class="md:col-span-2"><input type="text" name="passenger-name[]" value="{{ $pName }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white" placeholder="Full Name"></div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Age<span class="block text-xs text-gray-400 font-normal italic">Usia</span></label>
                                            <div class="md:col-span-2"><input type="number" name="passenger-age[]" value="{{ $pAge }}" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white" min="0" placeholder="Age"></div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">Phone<span class="block text-xs text-gray-400 font-normal italic">No. Ponsel</span></label>
                                            <div class="md:col-span-2"><input type="tel" name="passenger-phone[]" value="{{ $pPhone }}" class="passenger-phone-dynamic block w-full border-gray-300 rounded-md shadow-sm sm:text-sm px-3 py-2 bg-white"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                        <button type="button" id="add-passenger-btn" class="mt-4 text-blue-600 font-semibold text-sm flex items-center hover:text-blue-800">
                            <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Add Passenger
                        </button>
                    </div>

                    <div class="flex flex-col items-center space-y-4 pt-4">
                        <div class="flex items-center justify-center">
                            <input type="checkbox" id="agreement" name="agreement" required checked class="h-5 w-5 rounded-sm text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3">
                                <label for="agreement" class="text-gray-700 text-sm block">Saya mengkonfirmasi perubahan data di atas adalah benar.</label>
                                <label for="agreement" class="text-gray-400 text-xs italic block">I confirm that the data provided is correct.</label>
                            </div>
                        </div>
                        

                        
                        <button type="submit" id="btn-submit" class="bg-yellow-500 text-white font-semibold py-3 px-8 rounded-lg hover:bg-yellow-600 transition-colors w-full max-w-sm shadow-lg">
                            Update Reservasi & Reschedule
                        </button>
                        <a href="{{ route('reservasi.success', $visitor->uuid) }}" class="text-gray-500 text-sm hover:underline">Batal / Kembali</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="loading-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm hidden transition-opacity duration-300">
            <div class="bg-white p-8 rounded-2xl shadow-2xl flex flex-col items-center max-w-sm w-full mx-4">
                <div class="relative mb-6">
                    <div class="h-16 w-16 rounded-full border-4 border-gray-200"></div>
                    <div class="h-16 w-16 rounded-full border-4 border-blue-600 border-t-transparent animate-spin absolute top-0 left-0"></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Mohon Tunggu</h3>
                <p class="text-gray-500 text-center text-sm">Menyimpan perubahan...</p>
            </div>
        </div>

    </main>

    <footer class="p-4 text-center text-sm text-gray-500 bg-white border-t">
        Copyright &copy; 2025 PT. Charoen Pokphand Indonesia. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. INTL-TEL-INPUT CONFIG
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

            // 2. VEHICLE LOGIC (JS APPEND)
            const addVehicleBtn = document.getElementById('add-vehicle-btn');
            const vehicleList = document.getElementById('vehicle-list');
            // Counter start based on existing items to avoid ID collisions
            let vehicleCounter = {{ count($vehiclesSource ?? []) + 1 }};

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

            // 3. PASSENGER LOGIC (JS APPEND)
            const addPassengerBtn = document.getElementById('add-passenger-btn');
            const passengerList = document.getElementById('passenger-list');
            addPassengerBtn.addEventListener('click', () => {
                const newRowHTML = `
                <div class="bg-gray-50 rounded-lg p-6 border passenger-row-item relative mt-4">
                    <button type="button" class="remove-row absolute top-4 right-4 text-red-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="md:col-span-1 block text-sm font-medium text-gray-700">ID No.<span class="block text-xs text-gray-400 font-normal italic">No. Identitas</span></label>
                            <div class="md:col-span-2 relative"> <input type="password" name="passenger-id[]" class="block w-full border-gray-300 rounded-md shadow-sm sm:text-sm pl-3 pr-10 py-2 bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="KTP / Passport" autocomplete="off">
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
            });

            // Remove Row
            document.getElementById('form-page').addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-row');
                if (removeBtn) {
                    const vehicleRow = e.target.closest('.vehicle-row-item');
                    if (vehicleRow) vehicleRow.remove();
                    const passengerRow = e.target.closest('.passenger-row-item');
                    if (passengerRow) passengerRow.remove();
                }
            });

            // 4. GROUP TOGGLE
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
            toggleGroupSettings(); // Run on init

            // 5. NIK TOGGLE
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

            // Dynamic ID Toggle
            window.toggleDynamicId = function(btn) {
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

            // 6. SUBMIT
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
                        btnSubmit.innerHTML = 'Menyimpan...';
                        btnSubmit.classList.add('opacity-75', 'cursor-wait');
                    }
                });
            }
        });
    </script>
</body>
</html>