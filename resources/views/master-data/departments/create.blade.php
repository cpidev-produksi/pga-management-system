@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('departments.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Departemen
        </a>
    </div>

    <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100">
        
        {{-- Header Card dengan Style Indigo --}}
        <div class="bg-indigo-600 px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Tambah Departemen Baru</h1>
                <p class="text-indigo-100 text-sm mt-1">Lengkapi form di bawah ini untuk membuat departemen.</p>
            </div>
            <div class="opacity-20 hidden md:block">
                {{-- Icon Gedung/Office --}}
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        <form action="{{ route('departments.store') }}" method="POST" class="p-8 md:p-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- Input Nama Departemen --}}
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Departemen <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            {{-- Icon Tag/Label --}}
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('name') border-red-500 bg-red-50 @else border-gray-300 bg-gray-50 focus:bg-white @enderror" 
                            placeholder="Contoh: Human Resource, IT, Finance" required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Input Deskripsi --}}
                <div class="col-span-1 md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                    <div class="relative">
                        {{-- Textarea tidak menggunakan icon absolute position agar teks tidak tertutup jika panjang, tapi style border tetap sama --}}
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 border-gray-300 bg-gray-50 focus:bg-white"
                            placeholder="Keterangan singkat tentang departemen ini...">{{ old('description') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- Footer Button Action --}}
            <div class="mt-10 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('departments.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition duration-150 transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Departemen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection