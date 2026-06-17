<?php

namespace App\Http\Controllers;

use App\Mail\UserCreatedMail;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        // PERBAIKAN: Gunakan 'roles' (jamak) bawaan Spatie, bukan 'role'
        $users = User::with(['roles', 'department', 'plant'])
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
        $plants = \App\Models\Plant::where('is_active', true)->orderBy('name')->get();

        return view('master-data.users.create', compact('roles', 'departments', 'plants'));
    }

    public function store(Request $request)
    {
        // Tentukan apakah role yang dipilih adalah Super Admin
        $role = Role::where('uuid', $request->role_uuid)->first();
        $isSuperAdmin = $role && $role->name === 'Super Admin';

        // 1. VALIDASI
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'department_uuid' => ['nullable', 'exists:departments,uuid'],
            'role_uuid' => ['required', 'exists:roles,uuid'],

            // Plant WAJIB untuk semua role kecuali Super Admin (lintas plant)
            'plant_uuid' => [Rule::requiredIf(! $isSuperAdmin), 'nullable', 'exists:plants,uuid'],
        ], [
            'plant_uuid.required' => 'Plant wajib dipilih untuk role selain Super Admin.',
        ]);

        // 2. SIMPAN DATA USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_contactable' => $request->boolean('is_contactable'),
            'department_uuid' => $request->department_uuid,
            // Super Admin tidak terikat plant; role lain wajib punya plant
            'plant_uuid' => $isSuperAdmin ? null : $request->plant_uuid,
            'is_super_admin' => $isSuperAdmin,
        ]);

        // 3. ASSIGN ROLE PILIHAN ADMIN
        if ($role) {
            // syncRoles akan menghapus role 'User' (default) dan menggantinya dengan pilihan Admin
            $user->syncRoles([$role->name]);
        }
        try {
        Mail::to($user->email)->send(new UserCreatedMail($user, $request->password));
        } catch (\Exception $e) {
            // Log error silent agar user tidak terganggu jika email gagal
            Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $departments = Department::all();
        $plants = \App\Models\Plant::where('is_active', true)->orderBy('name')->get();

        return view('master-data.users.edit', compact('user', 'roles', 'departments', 'plants'));
    }

    public function update(Request $request, User $user)
    {
        // Tentukan apakah role yang dipilih adalah Super Admin
        $role = Role::where('uuid', $request->role_uuid)->first();
        $isSuperAdmin = $role && $role->name === 'Super Admin';

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
            'plant_uuid' => [Rule::requiredIf(! $isSuperAdmin), 'nullable', 'exists:plants,uuid'],
        ], [
            'plant_uuid.required' => 'Plant wajib dipilih untuk role selain Super Admin.',
        ]);

        // 2. SUSUN DATA UPDATE
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_contactable' => $request->boolean('is_contactable'),
            'department_uuid' => $request->department_uuid,
            'plant_uuid' => $isSuperAdmin ? null : $request->plant_uuid,
            'is_super_admin' => $isSuperAdmin,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update data user (tanpa role)
        $user->update($userData);

        // 3. UPDATE ROLE VIA SPATIE
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->uuid == $user->uuid) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    public function trash()
    {
        // PERBAIKAN: Gunakan 'roles' (jamak)
        $users = User::onlyTrashed()->with(['roles', 'department', 'plant'])->latest()->paginate(10);
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