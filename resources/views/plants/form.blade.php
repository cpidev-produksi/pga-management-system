@extends('layouts.app')

@section('content')
@php($isEdit = $plant->exists)
<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('plants.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">
            {{ $isEdit ? 'Ubah Plant' : 'Tambah Plant' }}
        </h1>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $isEdit ? route('plants.update', $plant->uuid) : route('plants.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Plant</label>
            <input type="text" name="name" value="{{ old('name', $plant->name) }}"
                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm px-3 py-2"
                   placeholder="mis. Salatiga" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Plant</label>
            <input type="text" name="code" value="{{ old('code', $plant->code) }}"
                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm px-3 py-2 uppercase"
                   placeholder="mis. SLT" required>
            <p class="text-xs text-gray-400 mt-1">Dipakai pada link reservasi (mis. /reservasi/SLT) dan QR. Huruf/angka saja.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <textarea name="location" rows="2"
                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm px-3 py-2"
                      placeholder="Alamat plant">{{ old('location', $plant->location) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2"
                      class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm px-3 py-2">{{ old('description', $plant->description) }}</textarea>
        </div>

        <div x-data="{ previewUrl: '' }">
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Poster Peraturan</label>

            {{-- Preview poster saat ini (mode edit) atau pratinjau file yang dipilih --}}
            <div class="mb-3">
                @if ($plant->exists && $plant->poster_image)
                    <img x-show="!previewUrl" src="{{ $plant->poster_url }}" alt="Poster saat ini"
                         class="h-40 w-auto rounded-lg border border-gray-200 object-contain bg-gray-50">
                @endif
                <img x-show="previewUrl" :src="previewUrl" alt="Pratinjau poster"
                     class="h-40 w-auto rounded-lg border border-blue-200 object-contain bg-gray-50" style="display:none">
            </div>

            <input type="file" name="poster_image" accept="image/png,image/jpeg,image/webp"
                   @change="previewUrl = $event.target.files.length ? URL.createObjectURL($event.target.files[0]) : ''"
                   class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
            <p class="text-xs text-gray-400 mt-1">
                Unggah gambar (JPG, PNG, atau WEBP, maks 4 MB).
                @if ($plant->exists && $plant->poster_image)
                    Biarkan kosong untuk mempertahankan poster saat ini.
                @else
                    Kosongkan untuk memakai poster default.
                @endif
            </p>
            @error('poster_image')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1"
                   {{ old('is_active', $plant->exists ? $plant->is_active : true) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-red-600 focus:ring-red-500">
            <span class="text-sm text-gray-700">Plant aktif</span>
        </label>

        <div class="pt-2 flex justify-end gap-3">
            <a href="{{ route('plants.index') }}"
               class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
            <button type="submit"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 transition">
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Plant' }}
            </button>
        </div>
    </form>
</div>
@endsection