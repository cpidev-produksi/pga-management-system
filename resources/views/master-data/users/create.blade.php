@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar User
        </a>
    </div>

    <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-100">
        
        <div class="bg-indigo-600 px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Tambah User Baru</h1>
                <p class="text-indigo-100 text-sm mt-1">Lengkapi informasi di bawah untuk mendaftarkan pengguna.</p>
            </div>
            <div class="opacity-20 hidden md:block">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="p-8 md:p-10">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('name') border-red-500 bg-red-50 @else border-gray-300 bg-gray-50 focus:bg-white @enderror" 
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                            class="w-full pl-10 pr-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 bg-red-50 @else border-gray-300 bg-gray-50 focus:bg-white @enderror" 
                            placeholder="nama@cp.co.id" required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                        <label for="role_uuid" class="block text-sm font-semibold text-gray-700 mb-2">
                            Role Akses <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="role_uuid" id="role_uuid" required
                                class="w-full pl-4 pr-10 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 border-gray-300 bg-white @error('role_uuid') border-red-500 @enderror">
                                <option value="" disabled selected>-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->uuid }}" data-super="{{ $role->name === 'Super Admin' ? '1' : '0' }}" {{ old('role_uuid') == $role->uuid ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('role_uuid')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="department_uuid" class="block text-sm font-semibold text-gray-700 mb-2">
                            Departemen
                        </label>
                        <div class="relative">
                            <select name="department_uuid" id="department_uuid"
                                class="w-full pl-4 pr-10 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 border-gray-300 bg-white">
                                <option value="">-- Tidak Ada Departemen --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->uuid }}" {{ old('department_uuid') == $dept->uuid ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        @error('department_uuid')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PLANT: menentukan plant tujuan akun saat login --}}
                    <div class="col-span-1 md:col-span-2" id="plant-field-wrapper">
                        <label for="plant_uuid" class="block text-sm font-semibold text-gray-700 mb-2">
                            Plant <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="plant_uuid" id="plant_uuid"
                                class="w-full pl-4 pr-10 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 border-gray-300 bg-white @error('plant_uuid') border-red-500 @enderror">
                                <option value="" disabled selected>-- Pilih Plant --</option>
                                @foreach($plants as $plant)
                                    <option value="{{ $plant->uuid }}" {{ old('plant_uuid') == $plant->uuid ? 'selected' : '' }}>
                                        {{ $plant->name }} ({{ $plant->code }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p id="plant-help" class="mt-1 text-xs text-gray-400">Akun akan langsung masuk ke plant ini saat login. Super Admin tidak perlu plant.</p>
                        @error('plant_uuid')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-start p-4 border border-gray-200 rounded-lg bg-gray-50 hover:bg-white hover:border-indigo-300 transition-colors">
                        <div class="flex items-center h-5">
                            <input id="is_contactable" name="is_contactable" type="checkbox" value="1" {{ old('is_contactable') ? 'checked' : '' }}
                                class="w-5 h-5 border border-gray-300 rounded focus:ring-3 focus:ring-indigo-300 text-indigo-600 cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_contactable" class="font-bold text-gray-700 cursor-pointer select-none">Tampilkan di Daftar Tamu?</label>
                            <p class="text-gray-500 text-xs mt-1">Jika dicentang, nama user ini akan muncul di dropdown saat tamu memilih karyawan yang dituju.</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 py-2">
                    <hr class="border-gray-200">
                    <p class="text-xs text-gray-500 mt-2 uppercase tracking-wider font-bold">Pengaturan Keamanan</p>
                </div>

                <div class="col-span-1 md:col-span-2" x-data="{ 
                        showPass: false, 
                        showConfirm: false, 
                        password: '', 
                        password_confirmation: '',
                        generatePassword() {
                            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
                            let result = '';
                            for (let i = 0; i < 16; i++) {
                                result += chars.charAt(Math.floor(Math.random() * chars.length));
                            }
                            this.password = result;
                            this.password_confirmation = result;
                            this.showPass = true; 
                        }
                    }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password" class="block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                                <button type="button" @click="generatePassword()" class="text-xs flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-semibold transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Generate
                                </button>
                            </div>
                            
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                
                                <input x-model="password" :type="showPass ? 'text' : 'password'" name="password" id="password" 
                                    class="w-full pl-10 pr-12 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('password') border-red-500 @else border-gray-300 bg-gray-50 focus:bg-white @enderror" 
                                    placeholder="Min. 8 karakter" required>
                                
                                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!showPass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showPass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.907m.966-1.506a14.482 14.482 0 011.756-1.745M10 5a9.96 9.96 0 012-1c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.357 3.375m-1.782 2.115a14.476 14.476 0 01-2.905 1.545M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                
                                <input x-model="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" 
                                    class="w-full pl-10 pr-12 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 border-gray-300 bg-gray-50 focus:bg-white" 
                                    placeholder="Ulangi password" required>

                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.574-2.907m.966-1.506a14.482 14.482 0 011.756-1.745M10 5a9.96 9.96 0 012-1c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.357 3.375m-1.782 2.115a14.476 14.476 0 01-2.905 1.545M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                <a href="{{ route('users.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition duration-150 transform hover:-translate-y-0.5">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const role = document.getElementById('role_uuid');
        const plantSel = document.getElementById('plant_uuid');
        const wrapper = document.getElementById('plant-field-wrapper');
        const help = document.getElementById('plant-help');
        if (!role || !plantSel) return;

        function sync() {
            const opt = role.options[role.selectedIndex];
            const isSuper = opt && opt.dataset.super === '1';
            plantSel.disabled = isSuper;
            if (wrapper) wrapper.style.opacity = isSuper ? '0.5' : '1';
            if (isSuper) {
                plantSel.value = '';
                if (help) help.textContent = 'Super Admin lintas plant — plant tidak diperlukan.';
            } else if (help) {
                help.textContent = 'Akun akan langsung masuk ke plant ini saat login.';
            }
        }
        role.addEventListener('change', sync);
        sync();
    })();
</script>
@endsection