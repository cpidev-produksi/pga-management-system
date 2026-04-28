@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-3xl mx-auto">
    
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                    <i class="fa-solid fa-user-shield text-xl"></i>
                </span>
                <span>Atur Akses Role</span>
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Menentukan fitur apa saja yang bisa diakses oleh role <span class="font-bold text-indigo-600">"{{ $role->name }}"</span>.
            </p>
        </div>
        
        <a href="{{ route('roles.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <form action="{{ route('roles.update', $role->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-indigo-50/50 px-6 py-4 border-b border-indigo-100 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-indigo-500 mt-0.5"></i>
                <div class="text-sm text-indigo-800">
                    <p class="font-medium">Panduan Pengaturan</p>
                    <p class="mt-1 opacity-80">Centang kotak di sebelah kanan untuk memberikan izin akses. Perubahan akan langsung berlaku setelah Anda menekan tombol Simpan.</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @foreach($permissions as $permission)
                    
                    <label for="perm_{{ $permission->id }}" 
                           class="relative flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:border-indigo-400 hover:bg-indigo-50/30 cursor-pointer transition-all duration-200 group {{ $role->hasPermissionTo($permission->name) ? 'bg-indigo-50/20 border-indigo-200' : '' }}">
                        
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $role->hasPermissionTo($permission->name) ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-400 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} transition-colors">
                                <i class="fa-solid fa-lock-open text-sm"></i>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </span>
                                <span class="text-xs text-gray-400 font-mono mt-0.5">
                                    code: {{ $permission->name }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input id="perm_{{ $permission->id }}" 
                                   name="permissions[]" 
                                   value="{{ $permission->name }}" 
                                   type="checkbox" 
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-offset-2 transition cursor-pointer"
                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('roles.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all hover:shadow-md">
                    <i class="fa-solid fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection