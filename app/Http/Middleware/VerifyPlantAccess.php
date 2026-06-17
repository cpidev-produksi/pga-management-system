<?php

namespace App\Http\Middleware;

use App\Models\Plant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyPlantAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(401, 'Unauthorized');
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Super admin: bypass
        if ($user->is_super_admin) {
            return $next($request);
        }

        // Get plant dari route parameter
        $plant = $request->route('plant') ?? Plant::find($request->route('plant_uuid'));

        if (!$plant) {
            abort(404, 'Plant not found');
        }

        // Check user bisa akses plant ini
        if (!$user->canAccessPlant($plant->uuid)) {
            abort(403, 'Unauthorized access to this plant');
        }

        return $next($request);
    }
}
