<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservasiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_uuid',   // <--- Tambahkan ini agar tidak error MassAssignment
        'plant_uuid',
        'ip_address', 
        'url', 
        'method',
        'country_name', 
        'city', 
        'latitude', 
        'longitude', 
        'user_agent'
    ];

    // Relasi ini tetap boleh ada (optional), tidak mengganggu jika datanya null
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_uuid', 'uuid');
    }
}