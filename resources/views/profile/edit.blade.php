@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Navigasi --}}
            <div class="mb-6 flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Profile Saya') }}
                </h2>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 pb-8">
                
                {{-- Banner Header --}}
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative"></div>

                <div class="px-4 sm:px-8 relative">
                    
                    {{-- Avatar --}}
                    <div class="relative -mt-16 mb-6">
                        <div class="h-32 w-32 rounded-full ring-4 ring-white bg-white flex items-center justify-center shadow-lg overflow-hidden">
                            <span class="text-4xl font-bold text-indigo-600">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                    </div>

                    {{-- Nama & Status --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-gray-500 text-sm flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Bergabung sejak {{ $user->created_at->format('d F Y') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4 sm:mt-0">
                             @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-50 text-red-700 border border-red-200 shadow-sm">
                                    Belum Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                    <svg class="mr-1.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Akun Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- 
                        DETAIL INFORMASI (MENEMPEL)
                        Kita buat 1 kotak besar abu-abu, lalu di dalamnya kita bagi menggunakan grid dan divide.
                    --}}
                    <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                            
                            {{-- KOLOM 1: EMAIL --}}
                            <div class="p-6 flex items-start space-x-4 hover:bg-gray-100 transition duration-150">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-white rounded-lg shadow-sm text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat Email</p>
                                    <p class="mt-1 text-base font-bold text-gray-900 break-all leading-snug">{{ $user->email }}</p>
                                </div>
                            </div>

                            {{-- KOLOM 2: DEPARTEMEN --}}
                            <div class="p-6 flex items-start space-x-4 hover:bg-gray-100 transition duration-150">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-white rounded-lg shadow-sm text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Departemen</p>
                                    <p class="mt-1 text-base font-bold text-gray-900 leading-snug">
                                        {{ $user->department->name ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- KOLOM 3: ROLE --}}
                            <div class="p-6 flex items-start space-x-4 hover:bg-gray-100 transition duration-150">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-white rounded-lg shadow-sm text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884.394 1.706 1 2.292m0-2.292c.606-.586 1-1.408 1-2.292m0 0a2 2 0 012-2 2 2 0 012 2v2m-6 4h8m-8 4h8" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Peran Akses</p>
                                    <p class="mt-1 text-base font-bold text-gray-900 capitalize leading-snug">
                                        {{ $user->getRoleNames()->isNotEmpty() ? $user->getRoleNames()->implode(', ') : 'User' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- End Detail Informasi --}}

                </div>
            </div>
        </div>
    </div>
@endsection