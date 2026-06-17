<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse; 
use App\Models\Visitor; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // ==========================================
        // 1. SETUP & FILTER INPUT
        // ==========================================
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $now = Carbon::now(); // Tambahkan ini untuk logika waktu realtime
        $currentYear = date('Y');
        
        // Ambil filter dari request (jika ada), default ke tahun ini & mode harian
        $selectedYear = $request->input('year', $currentYear);
        $period = $request->input('period', 'daily');

        // ==========================================
        // 2. LOGIKA TRAFFIC CHART (GRAFIK)
        // ==========================================
        $labels = [];
        $guestData = []; 
        $contractorData = []; 
        $vipData = [];
        
        // Base Query untuk Chart
        $chartQuery = Visitor::whereYear('visit_datetime', $selectedYear);

        if ($period == 'monthly') {
            // Mode Bulanan: Tampilkan data per tanggal dalam bulan ini
            $currentMonthIdx = date('m'); 
            $daysInMonth = Carbon::createFromDate($selectedYear, $currentMonthIdx, 1)->daysInMonth;
            
            // Siapkan array kosong untuk setiap tanggal
            for ($i = 1; $i <= $daysInMonth; $i++) $labels[] = (string)$i;
            $guestData = array_fill(0, $daysInMonth, 0);
            $contractorData = array_fill(0, $daysInMonth, 0);
            $vipData = array_fill(0, $daysInMonth, 0);

            $visitors = $chartQuery->whereMonth('visit_datetime', $currentMonthIdx)->get();

            foreach ($visitors as $v) {
                $idx = (int)$v->visit_datetime->format('d') - 1; // Index array mulai dari 0
                $type = strtolower($v->visit_type);
                
                if (in_array($type, ['guest', 'domestic'])) $guestData[$idx]++;
                elseif ($type == 'contractor') $contractorData[$idx]++;
                elseif ($type == 'vip') $vipData[$idx]++;
            }

        } elseif ($period == 'yearly') {
            // Mode Tahunan: Tampilkan data per bulan (Jan - Des)
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $guestData = array_fill(0, 12, 0);
            $contractorData = array_fill(0, 12, 0);
            $vipData = array_fill(0, 12, 0);

            $visitors = $chartQuery->get();

            foreach ($visitors as $v) {
                $idx = (int)$v->visit_datetime->format('m') - 1;
                $type = strtolower($v->visit_type);

                if (in_array($type, ['guest', 'domestic'])) $guestData[$idx]++;
                elseif ($type == 'contractor') $contractorData[$idx]++;
                elseif ($type == 'vip') $vipData[$idx]++;
            }

        } else {
            // Mode Harian (Default): Tampilkan data per jam (00:00 - 23:00)
            for ($i = 0; $i < 24; $i++) $labels[] = sprintf('%02d:00', $i);
            $guestData = array_fill(0, 24, 0);
            $contractorData = array_fill(0, 24, 0);
            $vipData = array_fill(0, 24, 0);

            // Query khusus hari ini
            $visitors = Visitor::whereDate('visit_datetime', $today)->get();

            foreach ($visitors as $v) {
                $idx = (int)$v->visit_datetime->format('H'); // Ambil jamnya saja
                $type = strtolower($v->visit_type);

                if (in_array($type, ['guest', 'domestic'])) $guestData[$idx]++;
                elseif ($type == 'contractor') $contractorData[$idx]++;
                elseif ($type == 'vip') $vipData[$idx]++;
            }
        }

        // ==========================================
        // 3. LOGIKA CHART DEPARTEMEN
        // ==========================================
        $deptQuery = DB::table('visitors')
            ->join('users', 'visitors.intended_employee', '=', 'users.name') // Asumsi relasi via nama
            ->join('departments', 'users.department_uuid', '=', 'departments.uuid')
            ->select('departments.name', DB::raw('count(visitors.id) as total'));

        // ISOLASI PLANT: query mentah ini tidak melewati global scope Eloquent,
        // jadi filter plant aktif harus ditambahkan manual agar data tidak bocor antar-plant.
        if ($activePlantUuid = session('current_plant_uuid')) {
            $deptQuery->where('visitors.plant_uuid', $activePlantUuid);
        }

        // Filter Dept sesuai periode
        if ($period == 'yearly') {
            $deptQuery->whereYear('visitors.visit_datetime', $selectedYear);
        } elseif ($period == 'monthly') {
            $deptQuery->whereYear('visitors.visit_datetime', $selectedYear)
                    ->whereMonth('visitors.visit_datetime', date('m'));
        } else {
            $deptQuery->whereDate('visitors.visit_datetime', $today);
        }

        $deptStats = $deptQuery->groupBy('departments.name')
                            ->orderBy('total', 'desc')
                            ->limit(5)
                            ->get();
                            
        $deptLabels = $deptStats->pluck('name')->toArray();
        $deptValues = $deptStats->pluck('total')->toArray();

        // ==========================================
        // 4. RESPONSE AJAX (JIKA DIPANGGIL OLEH JS CHART)
        // ==========================================
        if ($request->ajax()) {
            return response()->json([
                'labels' => $labels,
                'guestData' => $guestData,
                'contractorData' => $contractorData,
                'vipData' => $vipData,
                'deptLabels' => $deptLabels,
                'deptValues' => $deptValues,
                'periodTitle' => ucfirst($period) . ' ' . $selectedYear
            ]);
        }

        // ==========================================
        // 5. DATA KARTU DASHBOARD (NON-AJAX)
        // ==========================================
        
        // A. Filter Tahun untuk Dropdown
        $availableYears = Visitor::selectRaw('YEAR(visit_datetime) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        if (!in_array($currentYear, $availableYears)) array_unshift($availableYears, $currentYear);

        // B. Total Hari Ini & Trend
        $totalToday = Visitor::whereDate('visit_datetime', $today)->count();
        $totalYesterday = Visitor::whereDate('visit_datetime', $yesterday)->count();
        // Hitung persentase kenaikan/penurunan
        $percentage = ($totalYesterday > 0) 
            ? round(($totalToday - $totalYesterday) / $totalYesterday * 100) 
            : ($totalToday > 0 ? 100 : 0);
        $trend = ($percentage > 0) ? 'up' : (($percentage < 0) ? 'down' : 'neutral');

        // C. Sedang Berkunjung (On Site) -> SUDAH SCAN & BELUM CHECKOUT (TANPA BATAS TANGGAL)
        $onSiteQuery = Visitor::where('status', true)
            ->whereNull('checkout_at')
            ->get();
            
        $onSiteCount = $onSiteQuery->count();
        $guestsOnSite = $onSiteQuery->filter(fn($v) => in_array(strtolower($v->visit_type), ['guest', 'domestic']))->count();
        $contractorsOnSite = $onSiteQuery->filter(fn($v) => strtolower($v->visit_type) === 'contractor')->count();

        // ------------------------------------------------------------------
        // D. Akan Datang (Expected) -> LOGIKA BARU (Pisahkan Telat & Next)
        // ------------------------------------------------------------------
        
        // 1. Hitung yang TELAT (Jadwal < Sekarang, tapi Status masih false)
        $lateCount = Visitor::whereDate('visit_datetime', $today)
            ->where('visit_datetime', '<', $now)
            ->where('status', false)
            ->count();

        // 2. Hitung yang AKAN DATANG (Jadwal > Sekarang, Status false)
        $upcomingCount = Visitor::whereDate('visit_datetime', $today)
            ->where('visit_datetime', '>', $now)
            ->where('status', false)
            ->count();

        // 3. Total untuk angka besar di Dashboard (Total Sisa Antrian Hari Ini)
        $expectedCount = $lateCount + $upcomingCount;

        // 4. Cari Visitor BERIKUTNYA (Strict Future: Hanya ambil yang jamnya > sekarang)
        // Tujuannya agar tulisan "Berikutnya: PT A (Jam 13.00)" tidak menampilkan yang telat jam 08.00
        $nextVisitor = Visitor::whereDate('visit_datetime', $today)
            ->where('visit_datetime', '>', $now)
            ->where('status', false)
            ->orderBy('visit_datetime', 'asc')
            ->first();
        // ------------------------------------------------------------------

        // E. Log Terbaru
        $recentLogs = Visitor::orderBy('updated_at', 'desc')->limit(10)->get();

        // ==========================================
        // 6. RETURN VIEW
        // ==========================================
        return view('sidebar.dashboard', compact(
            'totalToday', 'percentage', 'trend', 
            'onSiteCount', 'guestsOnSite', 'contractorsOnSite',
            'expectedCount', 'lateCount', 'nextVisitor', // <-- Pastikan lateCount masuk sini
            'labels', 'guestData', 'contractorData', 'vipData',
            'period', 'selectedYear', 'availableYears', 
            'recentLogs', 'deptLabels', 'deptValues'
        ));
    }
    /**
     * Menampilkan halaman pemindai QR untuk pengunjung (visitors).
     */
    public function showVisitorScanner()
    {
        return view('sidebar.visitor-scanner');
    }

    /**
     * Memproses hasil pemindaian QR (payload ber-signature) dan mengembalikan JSON.
     *
     * Payload QR: {PLANT_CODE}.{VISITOR_UUID}.{SIGNATURE} (lihat QrCodeService).
     * Aturan: user biasa hanya boleh scan QR plant-nya sendiri.
     */
    public function scanVisitor(Request $request, \App\Services\QrCodeService $qr): JsonResponse
    {
        $payload = (string) $request->input('payload', $request->input('qr', ''));

        // 1. Verifikasi format + signature + resolusi plant/visitor.
        try {
            $result = $qr->verify($payload);
        } catch (\App\Services\InvalidQrException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => '⛔ ' . $e->getMessage(),
            ], 422);
        }

        /** @var \App\Models\Plant $plant */
        $plant = $result['plant'];
        /** @var \App\Models\Visitor $visitor */
        $visitor = $result['visitor'];

        // 2. ISOLASI PLANT: pastikan scanner hanya memproses QR plant-nya.
        $user = Auth::user();

        if (! $user->is_super_admin) {
            if ($user->plant_uuid !== $plant->uuid) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "⛔ QR ini milik plant {$plant->name}, bukan plant Anda.",
                ], 403);
            }
        } else {
            // Super admin yang sedang menyelami satu plant: batasi ke plant itu.
            $active = session('current_plant_uuid');
            if ($active && $active !== $plant->uuid) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "⛔ QR ini milik plant {$plant->name}. Beralih ke plant tersebut untuk men-scan.",
                ], 403);
            }
        }

        // 3. Cek apakah sudah pernah scan.
        Carbon::setLocale('id');
        if ($visitor->status) {
            $lastScan = Carbon::parse($visitor->updated_at);

            return response()->json([
                'status'  => 'warning',
                'message' => "⚠️ Pengunjung a.n {$visitor->name} sudah melakukan scan sebelumnya pada tanggal "
                    . $lastScan->translatedFormat('d F Y') . " pukul " . $lastScan->format('H:i') . ".",
            ]);
        }

        // 4. Update status check-in.
        $visitor->status = true;
        $visitor->scan_by = $user->uuid;
        $visitor->save();

        return response()->json([
            'status'  => 'success',
            'message' => '✅ Check-in Berhasil! Selamat datang, ' . $visitor->name . '.',
            'uuid'    => $visitor->uuid,
        ]);
    }

    /**
     * Halaman Detail: Total Visitor Today
     */
    public function detailTotalToday()
    {
        $visitors = \App\Models\Visitor::whereDate('visit_datetime', \Carbon\Carbon::today())
            ->orderBy('visit_datetime', 'desc')
            ->get();

        return view('sidebar.details.total-today', compact('visitors'));
    }

    /**
     * Halaman Detail: Currently On-Site
     */
    public function detailOnSite()
    {
        $visitors = \App\Models\Visitor::whereDate('visit_datetime', \Carbon\Carbon::today())
            ->where('status', true)
            ->whereNull('checkout_at')
            ->orderBy('visit_datetime', 'desc')
            ->get();

        return view('sidebar.details.on-site', compact('visitors'));
    }

    /**
     * Halaman Detail: Expected Arrival
     */
    public function detailExpected()
    {
        $visitors = \App\Models\Visitor::whereDate('visit_datetime', \Carbon\Carbon::today())
            ->where('status', false)
            ->orderBy('visit_datetime', 'asc') // Urutkan dari yang akan datang paling dekat
            ->get();

        return view('sidebar.details.expected', compact('visitors'));
    }
}