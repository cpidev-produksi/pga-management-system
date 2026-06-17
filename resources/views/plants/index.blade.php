@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Plant</h1>
            <p class="text-sm text-gray-500 mt-1">Tambah, ubah, atau non-aktifkan plant.</p>
        </div>
        <a href="{{ route('plants.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 transition">
            <i class="fa-solid fa-plus"></i> Tambah Plant
        </a>
    </div>

    @if (session('success'))
        <div class="mb-5 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-left">
                <tr>
                    <th class="px-5 py-3 font-semibold">Plant</th>
                    <th class="px-5 py-3 font-semibold">Kode</th>
                    <th class="px-5 py-3 font-semibold text-center">User</th>
                    <th class="px-5 py-3 font-semibold text-center">Visitor</th>
                    <th class="px-5 py-3 font-semibold text-center">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($plants as $plant)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3">
                            <div class="font-semibold text-gray-800">{{ $plant->name }}</div>
                            <div class="text-xs text-gray-400">{{ $plant->location }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $plant->code }}</td>
                        <td class="px-5 py-3 text-center text-gray-600">{{ $plant->users_count }}</td>
                        <td class="px-5 py-3 text-center text-gray-600">{{ $plant->visitors_count }}</td>
                        <td class="px-5 py-3 text-center">
                            @if ($plant->is_active)
                                <span class="text-xs font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-full">Aktif</span>
                            @else
                                <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Non-aktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('plants.edit', $plant->uuid) }}"
                                   class="px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                                    <i class="fa-solid fa-pen mr-1"></i> Ubah
                                </a>
                                <form method="POST" action="{{ route('plants.destroy', $plant->uuid) }}"
                                      onsubmit="return confirm('Hapus plant {{ $plant->name }}? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada plant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
