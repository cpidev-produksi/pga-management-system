<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;

    // Tambahkan properti ini agar bisa disimpan ke database
    protected $fillable = [
        'user_uuid',
        'ip_address',
        'country_name',
        'city',
        'region_name',
        'latitude',
        'longitude',
        'user_agent',
    ];

    // Opsional: Jika ingin relasi balik ke User
    public function user()
    {
        // PERBAIKAN: Definisikan 'user_uuid' sebagai foreign key
        // Karena defaultnya Laravel mencari 'user_id'
        return $this->belongsTo(User::class, 'user_uuid', 'uuid'); 
        // Note: Parameter ke-3 ('uuid') asumsikan nama kolom di tabel users adalah 'uuid' juga.
        // Jika di tabel users primary key-nya 'id', ganti 'uuid' jadi 'id'.
    }
}