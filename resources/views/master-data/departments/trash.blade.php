@extends('layouts.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Tong Sampah Departemen 🗑️</h1>
            <p class="text-sm text-gray-500 mt-1">Mengelola departemen yang telah dihapus sementara.</p>
        </div>
        
        <a href="{{ route('departments.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded shadow-sm relative flex justify-between items-center" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-green-700 font-bold hover:text-green-900">×</button>
        </div>
    @endif
    
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-sm relative flex justify-between items-center" role="alert">
             <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-700 font-bold hover:text-red-900">×</button>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Nama Departemen</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-red-600">Dihapus Pada</th>
                        <th scope="col" class="px-6 py-4 font-bold tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($departments as $dept)
                    <tr class="bg-white hover:bg-red-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold border-2 border-white shadow-sm">
                                        {{ substr($dept->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $dept->name }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ Str::limit($dept->description, 50) ?? '-' }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                {{ $dept->deleted_at->format('d M Y H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-3">
                                
                                <form action="{{ route('departments.restore', $dept->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="group p-2 rounded-lg border border-transparent hover:bg-green-50 hover:border-green-200 transition-all duration-200 flex items-center gap-1 text-green-600 font-medium text-xs uppercase tracking-wider" title="Kembalikan Departemen">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Restore
                                    </button>
                                </form>

                                <span class="text-gray-300">|</span>

                                <form action="{{ route('departments.force-delete', $dept->uuid) }}" method="POST" onsubmit="return confirm('PERINGATAN: Departemen akan dihapus SELAMANYA dan tidak bisa dikembalikan. Lanjutkan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="group p-2 rounded-lg border border-transparent hover:bg-red-50 hover:border-red-200 transition-all duration-200 flex items-center gap-1 text-red-600 font-medium text-xs uppercase tracking-wider" title="Hapus Permanen">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Permanent
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="text-base font-medium">Tong sampah kosong</p>
                                <p class="text-sm mt-1">Tidak ada departemen yang dihapus sementara.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($departments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $departments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection