<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('APP_NAME', 'E-PGA SYSTEM'),
        'short_name' => 'E-PGA',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        
        // --- BAGIAN ICONS (Menggunakan file dari folder 'android') ---
        // Sesuai screenshot: image_9de228.png
        'icons' => [
            '48x48' => [
                'path' => '/images/icons/android/android-launchericon-48-48.png',
                'purpose' => 'any'
            ],
            '72x72' => [
                'path' => '/images/icons/android/android-launchericon-72-72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/images/icons/android/android-launchericon-96-96.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/images/icons/android/android-launchericon-144-144.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/images/icons/android/android-launchericon-192-192.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/android/android-launchericon-512-512.png',
                'purpose' => 'any'
            ],
        ],

        // --- BAGIAN SPLASH SCREEN ---
        // PENTING: Berdasarkan gambar image_9e445e.png, file splash screen Anda
        // ADA DI LUAR folder ios/android. (Folder 'ios' Anda isinya hanya icon kecil).
        // Jadi path ini TETAP mengarah ke folder icons utama agar tidak error (gambar blank).
        'splash' => [
            '640x1136' => '/images/icons/splash-640x1136.png',
            '750x1334' => '/images/icons/splash-750x1334.png',
            '828x1792' => '/images/icons/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash-1125x2436.png',
            '1242x2208' => '/images/icons/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash-1242x2688.png',
            '1536x2048' => '/images/icons/splash-1536x2048.png',
            '1668x2224' => '/images/icons/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash-1668x2388.png',
            '2048x2732' => '/images/icons/splash-2048x2732.png',
        ],

        'shortcuts' => [], 
        
        // --- BAGIAN CUSTOM (Untuk Windows / iOS Icon spesifik) ---
        // Kita bisa menambahkan meta tag manual untuk Windows & iOS Icon di sini
        'custom' => [
            'ios' => [
                '<link rel="apple-touch-icon" href="/images/icons/ios/180.png">', // Mengambil dari folder ios
            ],
            'windows' => [
                // Mengambil dari folder windows11
                '<meta name="msapplication-TileImage" content="/images/icons/windows11/SmallTile.scale-100.png">',
                '<meta name="msapplication-TileColor" content="#000000">',
            ]
        ]
    ]
];