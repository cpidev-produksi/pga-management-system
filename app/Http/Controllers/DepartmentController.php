<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input jumlah data per halaman (default 10)
        $perPage = $request->input('per_page', 10);
        
        // 2. Ambil input kata kunci pencarian
        $search = $request->input('search');

        // 3. Query dengan Filter
        $departments = Department::query()
            ->when($search, function ($query, $search) {
                // Cari berdasarkan Nama ATAU Deskripsi
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest() // Urutkan dari yang terbaru
            ->paginate($perPage) // Gunakan variabel perPage
            ->withQueryString(); // Agar saat pindah halaman, filter pencarian tidak hilang

        return view('master-data.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('master-data.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string'
        ]);

        Department::create($request->all()); // UUID otomatis dibuat di Model

        return redirect()->route('departments.index')->with('success', 'Departemen ditambahkan');
    }

    public function edit(Department $department)
    {
        return view('master-data.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            // Validasi unique ignore UUID saat ini
            'name' => 'required|string|max:255|unique:departments,name,'.$department->uuid.',uuid',
            'description' => 'nullable|string'
        ]);

        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Departemen diupdate');
    }

    public function destroy(Department $department)
    {
        // Cek apakah ada user di departemen ini sebelum menghapus sementara
        if ($department->users()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Masih ada user aktif di departemen ini. Pindahkan department user terlebih dahulu.');
        }

        $department->delete(); // Soft delete
        return redirect()->route('departments.index')->with('success', 'Departemen dipindahkan ke sampah.');
    }

    // ==========================================
    // FITUR TRASH & RESTORE DEPARTEMEN
    // ==========================================

    public function trash()
    {
        $departments = Department::onlyTrashed()->latest()->paginate(10);
        return view('master-data.departments.trash', compact('departments'));
    }

    public function restore($uuid)
    {
        $department = Department::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $department->restore();

        return redirect()->back()->with('success', 'Departemen berhasil dikembalikan!');
    }

    public function forceDelete($uuid)
    {
        $department = Department::withTrashed()->where('uuid', $uuid)->firstOrFail();

        // Cek lagi relasi (opsional, untuk keamanan ganda)
        if ($department->users()->count() > 0) {
            return back()->with('error', 'Tidak bisa hapus permanen! Masih ada user yang terhubung.');
        }

        $department->forceDelete(); // Hapus selamanya
        return redirect()->back()->with('success', 'Departemen dihapus permanen!');
    }
}