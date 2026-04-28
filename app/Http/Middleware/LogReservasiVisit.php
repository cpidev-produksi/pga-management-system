<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stevebauman\Location\Facades\Location;
use App\Models\ReservasiLog; // Pastikan Model sudah dibuat

class LogReservasiVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // PENTING: Hanya catat jika user MEMBUKA halaman (GET). 
        // Jangan catat saat user submit form (POST/PUT) agar data tidak duplikat.
        if ($request->isMethod('get')) {
            
            // 1. Deteksi IP (Logic Localhost)
            $ip = $request->ip();
            if ($ip == '127.0.0.1' || $ip == '::1') {
                $ip = '125.160.203.205'; // IP Dummy
            }

            // 2. Cek Lokasi
            $position = Location::get($ip);

            // 3. Simpan Log
            ReservasiLog::create([
                'user_uuid'     => null, // Sengaja NULL karena ini route public
                'ip_address'    => $ip,
                'url'           => $request->fullUrl(),
                'method'        => $request->method(),
                'country_name'  => $position ? $position->countryName : null,
                'city'          => $position ? $position->cityName : null,
                'latitude'      => $position ? $position->latitude : null,
                'longitude'     => $position ? $position->longitude : null,
                'user_agent'    => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}