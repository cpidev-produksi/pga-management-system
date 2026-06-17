<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PlantsSeeder extends Seeder
{
    public function run(): void
    {
        $plants = [
            [
                'name' => 'Salatiga',
                'code' => 'SLT',
                'location' => 'Jl. Pattimura Km. 1, Desa Canden, Kel. Kutowinangun, Kec. Tingkir, Salatiga, Jawa Tengah',
                'description' => 'Plant Salatiga - Food Division',
                'poster_image' => 'assets/img/poster-peraturan-slt.png',
                'is_active' => true,
            ],
            [
                'name' => 'Sragen',
                'code' => 'SRG',
                'location' => 'Jl. Raya Solo - Purwodadi Km. 32, Dusun Pagak, Kec. Sumberlawang, Kab. Sragen, Jawa Tengah',
                'description' => 'Plant Sragen - Food Division',
                'poster_image' => 'assets/img/poster-peraturan-srg.png',
                'is_active' => true,
            ],
        ];

        $hasPoster = Schema::hasColumn('plants', 'poster_image');
 
        foreach ($plants as $data) {
            $poster = $data['poster_image'] ?? null;
 
            // updateOrCreate dikunci pada 'code' (unik) -> idempotent, tanpa duplikasi.
            $plant = Plant::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name'        => $data['name'],
                    'location'    => $data['location'],
                    'description' => $data['description'],
                    'is_active'   => $data['is_active'],
                ]
            );
 
            // Set poster hanya bila kolom tersedia.
            if ($hasPoster && $poster !== null) {
                $plant->poster_image = $poster;
                $plant->save();
            }
        }
    }
}
