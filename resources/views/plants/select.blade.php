@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-gray-800">Pilih Plant</h1>
        <p class="text-sm text-gray-500 mt-1">
            Anda login sebagai <span class="font-semibold">Super Admin</span>.
            Pilih plant untuk menyelami datanya, atau lihat gabungan semua plant.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Kartu: Semua Plant (mode gabungan) --}}
        <form method="POST" action="{{ route('plants.all') }}">
            @csrf
            <button type="submit"
                class="w-full h-full text-left bg-white rounded-2xl border-2 border-dashed border-blue-200 hover:border-blue-400 hover:bg-blue-50/40 p-6 transition group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <i class="fa-solid fa-layer-group text-xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-gray-800">Semua Plant</h3>
                <p class="text-xs text-gray-500 mt-1">Lihat data gabungan dari seluruh plant.</p>
            </button>
        </form>

        {{-- Kartu per plant --}}
        @foreach ($plants as $plant)
            <form method="POST" action="{{ route('plants.switch', $plant->uuid) }}">
                @csrf
                <button type="submit"
                    class="w-full h-full text-left bg-white rounded-2xl border border-gray-200 hover:border-red-300 hover:shadow-md p-6 transition group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center group-hover:scale-105 transition">
                            <i class="fa-solid fa-industry text-xl text-red-600"></i>
                        </div>
                        <span class="text-[11px] font-bold tracking-wider text-gray-400 bg-gray-50 px-2 py-1 rounded-md">
                            {{ $plant->code }}
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-800">{{ $plant->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fa-regular fa-user mr-1"></i>
                        {{ $todayCounts[$plant->uuid] ?? 0 }} visitor hari ini
                    </p>
                </button>
            </form>
        @endforeach
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('plants.index') }}" class="text-sm text-gray-500 hover:text-gray-800">
            <i class="fa-solid fa-gear mr-1"></i> Kelola data plant
        </a>
    </div>
</div>
@endsection
