<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = Auth::user();

        if (! $user->is_super_admin) {
            $plant = $user->plant; // relasi belongsTo Plant

            if (! $user->plant_uuid || ! $plant || ! $plant->is_active) {
                // Tolak login & beri keterangan jelas.
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = ! $user->plant_uuid || ! $plant
                    ? 'Akun Anda belum terdaftar pada plant manapun. Hubungi administrator.'
                    : 'Plant Anda (' . $plant->name . ') sedang non-aktif. Hubungi administrator.';

                return back()->withErrors(['email' => $message])->onlyInput('email');
            }
        }

        $request->session()->regenerate();

        // AUTO-SET PLANT UNTUK REGULAR USER
        if (! $user->is_super_admin && $user->plant_uuid) {
            session(['current_plant_uuid' => $user->plant_uuid]);
            session(['current_plant_name' => $user->plant?->name ?? 'Unknown']);
        }

        // Super admin: pilih plant dulu
        if ($user->is_super_admin) {
            return redirect()->route('plants.select');
        }

        // Regular user: langsung ke dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
