@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">
                <i class="fa-solid fa-user-shield text-indigo-600 mr-2"></i> Management Role & Akses
            </h1>
            <p class="mt-1 text-sm text-gray-500">Atur hak akses pengguna aplikasi di sini.</p>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative shadow-sm" role="alert">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-700">Daftar Role Tersedia</h2>
            <span class="px-3 py-1 text-xs font-semibold text-indigo-600 bg-indigo-50 rounded-full">
                Total: {{ $roles->count() }} Role
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase text-xs font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-48">Nama Role</th>
                        <th class="px-6 py-4">Akses / Permissions</th>
                        <th class="px-6 py-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50/80 transition-colors duration-200">
                        
                        <td class="px-6 py-4 align-top">
                            <div class="font-bold text-gray-800 text-base">{{ $role->name }}</div>
                            <div class="text-xs text-gray-400 mt-1">ID: {{ substr($role->uuid, 0, 8) }}...</div>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <div class="flex flex-wrap gap-2">
                                @forelse($role->permissions as $permission)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{-- Optional: Ganti underscore dengan spasi agar lebih rapi --}}
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        <i class="fa-solid fa-lock mr-1.5"></i> Tidak ada akses khusus
                                    </span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top text-center">
                            <a href="{{ route('roles.edit', $role->uuid) }}" 
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-300 transition-all shadow-sm group">
                                <i class="fa-solid fa-key text-gray-400 group-hover:text-indigo-500 transition-colors"></i>
                                <span>Atur</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center sm:text-left">
                Menampilkan seluruh role dalam sistem.
            </p>
        </div>
    </div>
</div>
@endsection