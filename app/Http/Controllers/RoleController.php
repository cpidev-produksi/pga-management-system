<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Halaman List Role
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Halaman Edit Permission (Tempat Anda centang-centang)
    public function edit(Role $role)
    {
        // Ambil semua permission yang tersedia di database
        $permissions = Permission::all();
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    // Proses Simpan
    public function update(Request $request, Role $role)
    {
        // Validasi input array
        $request->validate([
            'permissions' => 'array'
        ]);

        // Sync permission (Hapus yang lama, pasang yang baru sesuai centangan)
        // Jika tidak ada yang dicentang, kita kirim array kosong []
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'Hak akses role berhasil diperbarui!');
    }
}