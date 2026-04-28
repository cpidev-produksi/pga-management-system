@extends('layouts.app')

@section('title', 'Detail Data Pengunjung')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header & Tombol Kembali --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Data Pengunjung
            </h2>
            <a href="{{ route('visitors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            {{-- Status Bar Atas --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    @if($visitor->scan_by)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Discan oleh: <strong class="ml-1 text-gray-700">{{ $visitor->scanner->name ?? '-' }}</strong>
                        </span>
                    @endif
                </div>

                <div>
                    @if($visitor->checkout_at)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Status: Berhasil Checkout
                        </span>
                    @elseif($visitor->status == 1)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Status: Active / Checked-in
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            Status: Pending / Inactive
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 bg-white border-b border-gray-200">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    {{-- KOLOM KIRI: Informasi Pribadi --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Pribadi
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</dt>
                                <dd class="mt-1 text-gray-900 font-medium text-lg">{{ $visitor->name }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        @if($visitor->special_category == 'Internal')
                                            NIK / Employee ID
                                        @else
                                            No. Identitas (KTP/SIM)
                                        @endif
                                    </dt>
                                    <dd class="mt-1 text-gray-900">{{ \Illuminate\Support\Str::mask($visitor->identity_number, '*', -5) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Umur</dt>
                                    <dd class="mt-1 text-gray-900">{{ $visitor->age }} Tahun</dd>
                                </div>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    @if($visitor->special_category == 'Internal')
                                        Asal Unit / Plant
                                    @else
                                        Perusahaan / Instansi
                                    @endif
                                </dt>
                                <dd class="mt-1 text-gray-900">{{ $visitor->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Alamat</dt>
                                <dd class="mt-1 text-gray-900">{{ $visitor->address }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- KOLOM KANAN: Detail Kunjungan (Layout Diperbaiki) --}}
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Detail Kunjungan
                        </h3>
                        <dl class="space-y-4">
                            {{-- Waktu --}}
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu Kunjungan</dt>
                                <dd class="mt-1 text-indigo-600 font-bold text-lg">
                                    {{ $visitor->visit_datetime ? \Carbon\Carbon::parse($visitor->visit_datetime)->translatedFormat('d F Y, H:i') : '-' }}
                                </dd>
                            </div>

                            {{-- Tujuan --}}
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan & Keperluan</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ $visitor->visit_type }}
                                    </span>
                                    <div class="mt-1 text-gray-900">{{ $visitor->purpose }}</div>
                                    <div class="text-sm text-gray-500 italic">"{{ $visitor->purpose_note }}"</div>
                                </dd>
                            </div>

                            {{-- Bertemu Dengan (Full Width) --}}
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bertemu Dengan</dt>
                                <dd class="mt-1 text-gray-900 font-medium text-base">{{ $visitor->intended_employee }}</dd>
                            </div>

                            {{-- Grid Fasilitas (Internet & Produksi Sejajar) --}}
                            <div class="grid grid-cols-2 gap-4 pt-3 mt-2 border-t border-dashed border-gray-200">
                                
                                {{-- Kolom Kiri: Internet --}}
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Akses Internet</dt>
                                    <dd class="mt-1">
                                        @if($visitor->internet)
                                            <span class="text-green-600 font-bold flex items-center text-sm"><svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Ya</span>
                                        @else
                                            <span class="text-gray-500 flex items-center text-sm"><svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Tidak</span>
                                        @endif
                                    </dd>
                                </div>

                                {{-- Kolom Kanan: Masuk Produksi --}}
                                <div>
                                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Masuk Area Produksi?</dt>
                                    <dd class="mt-1">
                                        @if($visitor->is_production)
                                            <span class="inline-flex items-center text-sm font-bold text-red-600">
                                                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                                YA (Area Terbatas)
                                            </span>
                                        @else
                                            <span class="flex items-center text-sm text-gray-500">
                                                <svg class="w-5 h-5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Tidak / Area Kantor
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            
                            {{-- Logika Kategori Khusus (Kuning) --}}
                            @php
                                $isInternal = $visitor->special_category === 'Internal';
                                $isExternalSpecial = $visitor->special_category === 'External' && $visitor->special_needs && !in_array($visitor->special_needs, ['No Special Category', 'None', 'Normal']);
                                $showSpecialBox = $isInternal || $isExternalSpecial;
                            @endphp

                            @if($showSpecialBox)
                            <div class="bg-yellow-50 p-3 rounded border border-yellow-200 mt-2">
                                <dt class="text-xs font-bold text-yellow-700 uppercase tracking-wider">Kategori Tamu</dt>
                                <dd class="text-sm text-yellow-800 mt-1 font-medium">
                                    @if($isInternal)
                                        Internal / Co-Worker
                                        @if($visitor->special_needs && !in_array($visitor->special_needs, ['No Special Category', 'None']))
                                            - {{ $visitor->special_needs }}
                                        @endif
                                    @else
                                        {{ $visitor->special_category }} - {{ $visitor->special_needs }}
                                    @endif
                                </dd>
                            </div>
                            @endif

                        </dl>
                    </div>
                </div>

                <div class="border-t border-gray-200 my-6"></div>

                {{-- Informasi Kontak --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Nomor Telepon</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900 tracking-wide">{{ $visitor->phone_number }}</dd>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $visitor->email }}</dd>
                        </div>
                        <div class="p-4 rounded-lg bg-red-50 border border-red-100">
                            <dt class="text-xs font-medium text-red-500 uppercase flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Kontak Darurat
                            </dt>
                            <dd class="mt-1 text-sm font-bold text-red-700 tracking-wide">{{ $visitor->phone_number_emrg }}</dd>
                        </div>
                    </div>
                </div>

                {{-- Kendaraan Bawaan --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kendaraan Bawaan
                    </h3>
                    
                    @if(isset($visitor->vehicles) && is_array($visitor->vehicles) && count($visitor->vehicles) > 0)
                        <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Jenis Kendaraan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Plat Nomor</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($visitor->vehicles as $vehicle)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                {{ $vehicle['type'] ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-gray-800">
                                                {{ $vehicle['number'] ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-sm text-gray-500 bg-gray-50 p-4 rounded border border-dashed border-gray-300 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                            Tidak membawa kendaraan / Data kendaraan kosong.
                        </div>
                    @endif
                </div>

                {{-- Anggota Rombongan --}}
                @if($visitor->group_type !== 'Tidak Rombongan')
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Anggota Rombongan
                        </h3>
                        
                        @if(isset($visitor->group_members) && is_array($visitor->group_members) && count($visitor->group_members) > 0)
                            <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                @if($visitor->special_category == 'Internal')
                                                    NIK (Nomor Induk Karyawan)
                                                @else
                                                    Identitas (KTP/SIM)
                                                @endif
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($visitor->group_members as $index => $member)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $member['name'] ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                                    {{ isset($member['id']) ? \Illuminate\Support\Str::mask($member['id'], '*', -4) : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $member['age'] ?? '-' }} Tahun
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $member['phone'] ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-sm text-gray-500 bg-gray-50 p-4 rounded border border-dashed border-gray-300">
                                Jenis kunjungan terdaftar sebagai rombongan, namun data anggota belum diinput.
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Riwayat Petugas --}}
                <div class="mt-10 pt-6 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Riwayat Petugas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Check-in Oleh</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $visitor->scanner->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $visitor->updated_at && $visitor->status ? $visitor->updated_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Checkout Oleh</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $visitor->checkouter->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $visitor->checkout_at ? \Carbon\Carbon::parse($visitor->checkout_at)->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection