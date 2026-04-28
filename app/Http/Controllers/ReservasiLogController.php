<?php

namespace App\Http\Controllers;

use App\Models\ReservasiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservasiLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Dasar
        $query = ReservasiLog::latest();

        // 2. Fitur Search (Cari IP atau Kota)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country_name', 'like', "%{$search}%");
            });
        }

        // 3. Ambil Data (Pagination)
        $logs = $query->paginate(10)->withQueryString();

        // --- STATISTIK ---
        
        // A. Total Kunjungan
        $totalVisits = ReservasiLog::count();

        // B. Kunjungan Hari Ini
        $todayVisits = ReservasiLog::whereDate('created_at', now())->count();

        // C. Kota Terbanyak (Top City)
        $topCity = ReservasiLog::select('city', DB::raw('count(*) as total'))
                    ->whereNotNull('city')
                    ->groupBy('city')
                    ->orderByDesc('total')
                    ->first();

        return view('reservasi_logs.index', compact('logs', 'totalVisits', 'todayVisits', 'topCity'));
    }
}