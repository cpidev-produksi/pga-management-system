@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Header Navigasi (TOMBOL BACK) --}}
            <div class="mb-6 flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Ubah Password') }}
                </h2>
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 pb-8">
                
                {{-- Banner Header (Sama seperti Profile) --}}
                <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative"></div>

                <div class="px-4 sm:px-8 relative">
                    
                    {{-- Ikon Utama (Style seperti Avatar) --}}
                    <div class="relative -mt-16 mb-6">
                        <div class="h-32 w-32 rounded-full ring-4 ring-white bg-white flex items-center justify-center shadow-lg overflow-hidden">
                            <i class="fa-solid fa-shield-halved text-5xl text-indigo-600"></i>
                        </div>
                    </div>

                    {{-- Judul Halaman --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Keamanan Akun</h1>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-gray-500 text-sm">
                                    Buat password baru yang kuat untuk melindungi akun Anda.
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 
                        FORM AREA 
                        Kita batasi lebarnya (max-w-2xl) agar input field tidak terlalu panjang 
                        karena container utamanya sangat lebar (max-w-7xl).
                    --}}
                    {{-- Perubahan ada di class div pembungkus form ini --}}
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 w-full">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password Baru</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                                    </div>
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        id="password" 
                                        name="password" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 transition ease-in-out duration-150" 
                                        placeholder="Minimal 8 karakter"
                                        autocomplete="new-password"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                        <i class="fa-regular text-gray-400 hover:text-gray-600 transition" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </div>
                                </div>
                                @if($errors->updatePassword->get('password'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $errors->updatePassword->first('password') }}
                                    </p>
                                @endif
                            </div>

                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-check-double text-gray-400 text-sm"></i>
                                    </div>
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 transition ease-in-out duration-150" 
                                        placeholder="Ulangi password baru"
                                        autocomplete="new-password"
                                    >
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                        <i class="fa-regular text-gray-400 hover:text-gray-600 transition" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </div>
                                </div>
                                @if($errors->updatePassword->get('password_confirmation'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $errors->updatePassword->first('password_confirmation') }}
                                    </p>
                                @endif
                            </div>

                            <div class="pt-4 flex items-center justify-between border-t border-gray-200 mt-6">
                                <p class="text-xs text-gray-500">
                                    Jika mengalami kendala, hubungi Administrator.
                                </p>

                                <div class="flex items-center gap-4">
                                    @if (session('status') === 'password-updated')
                                        <div x-data="{ show: true }"
                                                x-show="show"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-2"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                x-init="setTimeout(() => show = false, 3000)"
                                                class="flex items-center text-sm text-green-600 font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Tersimpan') }}
                                        </div>
                                    @endif

                                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                        <i class="fa-solid fa-save mr-2"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection