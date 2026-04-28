@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ isSidebarOpen: false }">


    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <!-- Tombol toggle sidebar untuk mobile -->
            <button @click="isSidebarOpen = !isSidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 lg:hidden">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Data Visitor</h1>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm relative" role="alert">
            <div class="flex justify-between">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button @click="show = false" class="text-green-700 font-bold">×</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-sm relative" role="alert">
             <div class="flex justify-between">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button @click="show = false" class="text-red-700 font-bold">×</button>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
        
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('visitors.index') }}">
                <div class="flex flex-col xl:flex-row justify-between xl:items-end gap-4">
                    
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">Show</span>
                        <select name="per_page" onchange="this.form.submit()" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm py-1.5 pl-3 pr-8">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-gray-500 hidden sm:inline">entries</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto flex-wrap items-center sm:items-start">
                        
                        <div class="flex flex-row gap-2 w-full sm:w-auto items-center">
                            <div class="w-1/2 sm:w-auto">
                                <label for="start_date" class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="block w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md text-sm shadow-sm py-2"
                                    onchange="this.form.submit()">
                            </div>
                            <div class="pt-5 text-gray-400">-</div>
                            <div class="w-1/2 sm:w-auto">
                                <label for="end_date" class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="block w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md text-sm shadow-sm py-2"
                                    onchange="this.form.submit()">
                            </div>
                        </div>

                        <div class="w-full sm:w-auto">
                            <label class="block text-xs text-gray-500 mb-1">Status</label>
                            <select name="status" onchange="this.form.submit()" class="block w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md text-sm shadow-sm py-2 pl-3 pr-8">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Sudah Scan</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Belum Scan</option>
                            </select>
                        </div>

                        <div class="relative w-full sm:w-64">
                            <label class="block text-xs text-gray-500 mb-1">Pencarian</label>
                            <div class="absolute inset-y-0 left-0 pl-3 pt-6 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="pl-10 block w-full border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 rounded-md text-sm shadow-sm py-2"
                                placeholder="Nama, Perusahaan, ID..." onchange="this.form.submit()">
                        </div>

                        <div class="w-full sm:w-auto">
                            @can('export_excel')
                                <label class="block text-xs text-gray-500 mb-1 opacity-0">Export</label>
                                
                                <a href="{{ route('visitors.export_excel', request()->query()) }}" 
                                class="block w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-md shadow-sm text-center transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-green-500 border border-green-600"
                                title="Export Data ke Excel">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fa fa-file-excel"></i>
                                        <span>Excel</span>
                                    </div>
                                </a>
                            @endcan
                        </div>

                    </div>

                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">No. ID</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Bertemu Dengan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Keperluan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Tanggal Kunjungan</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($visitors as $visitor)
                    <tr class="bg-white hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ \Illuminate\Support\Str::mask($visitor->identity_number, '*', -5) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $visitor->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-600">{{ $visitor->company_name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-600">{{ $visitor->intended_employee }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-600">{{ Str::limit($visitor->purpose_note, 30) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-medium">{{ \Carbon\Carbon::parse($visitor->visit_datetime)->format('d M Y') }}</span>
                                <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($visitor->visit_datetime)->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($visitor->status == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-600 rounded-full"></span>
                                    Sudah Scan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-gray-500 rounded-full"></span>
                                    Belum Scan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-3">
                                
                                {{-- Detail --}}
                                <a href="{{route('visitors.show', $visitor->uuid)}}" class="text-indigo-600 hover:text-indigo-900 font-medium transition-colors" title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- PDF --}}
                                @can('export_pdf')
                                    <a href="{{ route('visitors.export_pdf', $visitor->uuid) }}" target="_blank" class="text-gray-600 hover:text-gray-900 font-medium transition-colors" title="Download PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v15a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                @endcan
                                {{-- Checkout --}}
                                @if(is_null($visitor->checkout_at))
                                    @can('check_out_visitor')
                                        <form action="{{ route('visitors.checkout', $visitor->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin checkout pengunjung {{ $visitor->name }}?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium transition-colors" title="Checkout Pengunjung">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                @else
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-semibold text-gray-400">OUT</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($visitor->checkout_at)->format('H:i') }}</span>
                                    </div>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-base font-medium">Data pengunjung tidak ditemukan</p>
                                <p class="text-sm mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visitors->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $visitors->appends(request()->query())->links() }}
        </div>
        @endif
        
    </div>
</div>
@endsection