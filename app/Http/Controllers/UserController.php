<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        // PERBAIKAN: Gunakan 'roles' (jamak) bawaan Spatie, bukan 'role'
        $users = User::with(['roles', 'department'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('master-data.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        
        return view('master-data.users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_uuid' => ['nullable', 'exists:departments,uuid'],
            
            // Validasi Role tetap diperlukan untuk memastikan inputnya benar
            'role_uuid' => ['required', 'exists:roles,uuid'],
        ]);

        // 2. SIMPAN DATA USER
        // PERBAIKAN: JANGAN masukkan role_uuid di sini (Kolomnya sudah tidak ada)
        // Model User akan otomatis memberi role 'User' saat created (sesuai setup Model sebelumnya)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'is_contactable' => $request->boolean('is_contactable'),
            'department_uuid' => $request->department_uuid,
        ]);

        // 3. ASSIGN ROLE PILIHAN ADMIN
        // Kita cari role berdasarkan UUID dari form, lalu kita TIMPA role default 'User'
        $role = Role::where('uuid', $request->role_uuid)->first();
        
        if ($role) {
            // syncRoles akan menghapus role 'User' (default) dan menggantinya dengan pilihan Admin
            $user->syncRoles([$role->name]);
        }
        try {
        Mail::to($user->email)->send(new UserCreatedMail($user, $request->password));
        } catch (\Exception $e) {
            // Log error silent agar user tidak terganggu jika email gagal
            \Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();
        return view('master-data.users.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        // 1. VALIDASI UPDATE
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->uuid, 'uuid'),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'department_uuid' => ['nullable', 'exists:departments,uuid'],
            'role_uuid' => ['required', 'exists:roles,uuid'],
        ]);

        // 2. SUSUN DATA UPDATE
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_contactable' => $request->boolean('is_contactable'),
            'department_uuid' => $request->department_uuid,
            // PERBAIKAN: role_uuid DIHAPUS dari sini
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update data user (tanpa role)
        $user->update($userData);

        // 3. UPDATE ROLE VIA SPATIE
        // Cari role baru, lalu sync
        $role = Role::where('uuid', $request->role_uuid)->first();
        
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->uuid == $user->uuid) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    public function trash()
    {
        // PERBAIKAN: Gunakan 'roles' (jamak)
        $users = User::onlyTrashed()->with(['roles', 'department'])->latest()->paginate(10);
        return view('master-data.users.trash', compact('users'));
    }

    public function restore($uuid)
    {
        $user = User::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $user->restore();
        
        return redirect()->back()->with('success', 'User berhasil dikembalikan (Restore)!');
    }

    public function forceDelete($uuid)
    {
        $user = User::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $user->forceDelete();
        
        return redirect()->back()->with('success', 'User dihapus permanen dari database!');
    }
}