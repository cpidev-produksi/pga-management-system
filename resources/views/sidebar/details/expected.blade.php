@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="p-4 sm:p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        {{-- KIRI: Tombol Kembali & Judul --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-orange-600">Akan Datang</h1>
                <p class="text-sm text-gray-500">Jadwal tamu yang belum hadir hari ini</p>
            </div>
        </div>

        {{-- KANAN: Wrapper untuk Tanggal & Counter (Agar menempel di kanan) --}}
        <div class="flex items-center gap-3">
            {{-- Widget Tanggal --}}
            <div class="hidden sm:block text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-100 shadow-sm">
                <i class="fa-regular fa-calendar mr-2 text-gray-400"></i>
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>

            {{-- Widget Counter --}}
            <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-100 shadow-sm">
                Tersisa: <strong>{{ $visitors->count() }}</strong>
            </div>
        </div>
    </div>
    {{-- Tabel Data --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-orange-50 border-b border-orange-100">
                    <tr>
                        <th class="px-6 py-4">Jam Jadwal</th>
                        <th class="px-6 py-4">Nama Pengunjung</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4 w-64">Detail Tambahan</th> 
                        <th class="px-6 py-4">Bertemu Dengan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                    {{-- LOGIKA WARNA BARIS: Merah pudar jika telat, Putih jika belum jamnya --}}
                    <tr class="border-b transition-colors align-top {{ $visitor->visit_datetime->isPast() ? 'bg-red-50 border-red-100 hover:bg-red-100' : 'bg-white hover:bg-gray-50' }}">
                        
                        {{-- 1. Jam --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-bold {{ $visitor->visit_datetime->isPast() ? 'text-red-600' : 'text-orange-600' }}">
                                {{ $visitor->visit_datetime->format('H:i') }}
                            </span>
                            
                            {{-- Tampilkan durasi telat jika waktu sudah lewat --}}
                            @if($visitor->visit_datetime->isPast())
                                <span class="block text-[10px] font-semibold text-red-500 mt-1">
                                    Late {{ $visitor->visit_datetime->diffForHumans(\Carbon\Carbon::now(), true) }}
                                </span>
                            @endif
                        </td>

                        {{-- 2. Nama Utama --}}
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $visitor->name }}
                            <div class="text-xs font-normal text-gray-400 mt-1">
                                <i class="fa-solid fa-phone text-[10px] mr-1"></i>{{ $visitor->phone_number ?? '-' }}
                            </div>
                        </td>

                        {{-- 3. Instansi --}}
                        <td class="px-6 py-4">
                            {{ $visitor->company_name ?? '-' }}
                            <div class="text-xs text-gray-400 mt-1 truncate max-w-[150px]" title="{{ $visitor->purpose }}">
                                {{ $visitor->purpose ?? '-' }}
                            </div>
                        </td>

                        {{-- 4. KOLOM DETAIL TAMBAHAN --}}
                        <td class="px-6 py-4 text-xs">
                            @if(!empty($visitor->vehicles) && is_array($visitor->vehicles))
                                <div class="mb-3">
                                    <span class="font-bold text-gray-700 block mb-1">
                                        <i class="fa-solid fa-car {{ $visitor->visit_datetime->isPast() ? 'text-red-400' : 'text-orange-500' }} mr-1"></i> Kendaraan:
                                    </span>
                                    <ul class="space-y-1">
                                        @foreach($visitor->vehicles as $vehicle)
                                            <li class="flex items-center gap-2">
                                                <span class="text-gray-500">{{ $vehicle['type'] ?? 'Kendaraan' }}</span>
                                                <span class="{{ $visitor->visit_datetime->isPast() ? 'bg-white border-red-200' : 'bg-gray-100 border-gray-200' }} px-1.5 py-0.5 rounded border font-mono text-gray-700 font-bold">
                                                    {{ $vehicle['number'] ?? '-' }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(!empty($visitor->group_members) && is_array($visitor->group_members))
                                <div>
                                    <span class="font-bold text-gray-700 block mb-1">
                                        <i class="fa-solid fa-users {{ $visitor->visit_datetime->isPast() ? 'text-red-400' : 'text-orange-500' }} mr-1"></i> 
                                        Rombongan ({{ count($visitor->group_members) }}):
                                    </span>
                                    <div class="max-h-24 overflow-y-auto pr-1 custom-scrollbar">
                                        <ul class="list-decimal list-inside text-gray-500 ml-1">
                                            @foreach($visitor->group_members as $member)
                                                <li class="truncate" title="{{ $member['name'] ?? '' }}">
                                                    {{ $member['name'] ?? 'Tanpa Nama' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if(empty($visitor->vehicles) && empty($visitor->group_members))
                                <span class="text-gray-300 italic">-</span>
                            @endif
                        </td>

                        {{-- 5. Bertemu Dengan --}}
                        <td class="px-6 py-4">
                            {{ $visitor->intended_employee }}
                        </td>

                        {{-- 6. Status --}}
                        <td class="px-6 py-4">
                            @if($visitor->visit_datetime->isPast())
                                {{-- STATUS MERAH JIKA TELAT --}}
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200 flex items-center w-fit gap-1 animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Terlambat
                                </span>
                            @else
                                {{-- STATUS ORANYE JIKA NORMAL --}}
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded border border-orange-200 flex items-center w-fit gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> Menunggu
                                </span>
                            @endif
                        </td>

                        {{-- 7. Aksi --}}
                        <td class="px-6 py-4">
                            <a href="{{route('visitors.show', $visitor->uuid)}}" class="text-indigo-600 hover:text-indigo-900 font-medium transition-colors" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-calendar-check text-4xl mb-3 text-orange-200"></i>
                            <p>Tidak ada lagi jadwal kedatangan untuk hari ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection