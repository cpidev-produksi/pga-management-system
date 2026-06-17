<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentPlant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // SUPER ADMIN: Jangan set plant tertentu
        if ($user->is_super_admin) {
            session(['is_super_admin' => true]);
            return $next($request);
        }

        // REGULAR USER: Set current plant dari user
        if ($user->plant_uuid) {
            session(['current_plant_uuid' => $user->plant_uuid]);
            session(['current_plant_name' => $user->plant?->name ?? 'Unknown']);
        } else {
            // User tidak punya plant assigned - error!
            // Redirect ke error page atau login
            return response()->view('errors.no-plant-assigned', [], 403);
        }

        return $next($request);
    }
}
