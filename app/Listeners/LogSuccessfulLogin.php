<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\LoginActivity;

class LogSuccessfulLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        $ip = $this->request->ip();

        if ($ip == '127.0.0.1' || $ip == '::1') {
            $ip = '125.160.203.205'; 
        }

        $position = Location::get($ip);

        LoginActivity::create([
            // PERBAIKAN: Ganti 'user_id' menjadi 'user_uuid' sesuai database & fillable
            'user_uuid'     => $event->user->uuid, // Pastikan tabel user punya kolom 'uuid'
            
            'ip_address'    => $ip,
            'country_name'  => $position ? $position->countryName : null,
            'city'          => $position ? $position->cityName : null,
            'region_name'   => $position ? $position->regionName : null,
            'latitude'      => $position ? $position->latitude : null,
            'longitude'     => $position ? $position->longitude : null,
            'user_agent'    => $this->request->userAgent(),
        ]);
    }
}