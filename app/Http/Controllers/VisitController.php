<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ReservationSuccess;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class VisitController extends Controller
{
    /**
     * Menampilkan halaman daftar tamu/reservasi (index).
     */
    public function index(Request $request)
    {
        $employees = \App\Models\User::where('is_contactable', true)
                    ->orderBy('name', 'asc')
                    ->get();

        $query = Visitor::query();
        if ($request->filled('is_production')) {
            $query->where('is_production', $request->is_production);
        }
        $visitors = $query->latest()->paginate(15);
        return view('guest.VisitView', compact('visitors','employees'));
    }

    /**
     * Menampilkan halaman formulir reservasi.
     * Plant dipilih di dalam halaman (single link /reservasi).
     */
    public function create()
    {
        $plants = \App\Models\Plant::where('is_active', true)->orderBy('name')->get();

        return view('guest.VisitView', compact('plants'));
    }

    /**
     * Menyimpan data reservasi baru dari form.
     * Plant ditentukan oleh pilihan pengunjung di halaman (field plant_uuid).
     */
    public function store(Request $request)
    {
        // Validasi & resolusi plant yang dipilih dari dalam halaman.
        $request->validate([
            'plant_uuid' => 'required|string|exists:plants,uuid',
        ], [
            'plant_uuid.required' => 'Silakan pilih plant tujuan terlebih dahulu.',
            'plant_uuid.exists'   => 'Plant yang dipilih tidak valid.',
        ]);

        $plant = \App\Models\Plant::where('uuid', $request->input('plant_uuid'))
            ->where('is_active', true)
            ->first();

        if (! $plant) {
            return back()->withInput()
                ->withErrors(['plant_uuid' => 'Plant yang dipilih tidak aktif atau tidak ditemukan.']);
        }
        // 1. VALIDASI
        $validatedData = $request->validate([
            'visitor_type'      => 'required|string',
            'id_card'           => 'required|string', 
            'name'              => 'required|string|max:255',
            'age'               => 'required|integer|min:1',
            'phone'             => 'required|string|max:20',
            'company'           => 'required|string|max:255',
            'address'           => 'required|string',
            'email'             => 'required|email',
            //'internet_access'   => 'required|string',
            'is_production'     => 'required|in:0,1',
            
            'intended_employee' => 'required|string', 
            'necessity'         => 'required|string', 
            'write_necessity'   => 'required|string', 
            
            'visit_date'        => 'required|date',
            'visit_time'        => 'required',
            'group_type'        => 'required|string',
            'special_category'  => 'nullable|string',
            'special_needs'     => 'nullable|string',
            // 'agreement'         => 'required',

            // KENDARAAN
            'vehicle-no'        => 'nullable|array',
            'vehicle-no.*'      => 'nullable|string',
            'vehicle_type'      => 'nullable|array',
            'vehicle_type.*'    => 'nullable|string',
            
            // PENUMPANG
            'passenger-name'    => 'nullable|array',
            'passenger-name.*'  => 'nullable|string',
            'passenger-id'      => 'nullable|array',
            'passenger-age'     => 'nullable|array',
            'passenger-phone'   => 'nullable|array',

            //'g-recaptcha-response' => 'required',
        ]);


        // 2. OLAH DATA KENDARAAN
        $vehiclesData = [];
        if ($request->has('vehicle-no')) {
            foreach ($request->input('vehicle-no') as $index => $number) {
                if (!empty($number)) {
                    $vehiclesData[] = [
                        'type'   => $request->input('vehicle_type')[$index] ?? 'Lain-lain',
                        'number' => $number,
                    ];
                }
            }
        }
        
        // 3. OLAH DATA ROMBONGAN
        $groupMembersData = [];
        if ($request->input('group_type') === 'Rombongan' && $request->has('passenger-name')) {
            foreach ($request->input('passenger-name') as $index => $name) {
                if (!empty($name)) {
                    $groupMembersData[] = [
                        'id'    => $request->input('passenger-id')[$index] ?? null,
                        'name'  => $name,
                        'age'   => $request->input('passenger-age')[$index] ?? null,
                        'phone' => $request->input('passenger-phone')[$index] ?? null,
                    ];
                }
            }
        }

        // 4. SIMPAN KE DATABASE
        try {
            DB::beginTransaction(); // Mulai transaksi

            $visitor = Visitor::create([
                'plant_uuid'        => $plant->uuid,
                'visit_type'        => $validatedData['visitor_type'],
                'identity_number'   => $validatedData['id_card'],
                'name'              => $validatedData['name'],
                'age'               => $validatedData['age'],
                'phone_number'      => $validatedData['phone'],
                'company_name'      => $validatedData['company'],
                'address'           => $validatedData['address'],
                // emergency_phone dihapus
                'email'             => $validatedData['email'],
                //'internet'          => $validatedData['internet_access'] === 'Ada', 
                'is_production'     => $request->input('is_production'),
                
                'intended_employee' => $validatedData['intended_employee'],
                'purpose'           => $validatedData['necessity'],
                'purpose_note'      => $validatedData['write_necessity'],
                
                'special_category'  => $request->input('special_category'),
                'special_needs'     => $request->input('special_needs'),
                'visit_datetime'    => Carbon::parse($validatedData['visit_date'] . ' ' . $validatedData['visit_time']),
                
                'group_type'        => $validatedData['group_type'],
                
                'vehicles'          => $vehiclesData,      
                'group_members'     => $groupMembersData,  
            ]);

            DB::commit(); // Data tersimpan permanen

            // --- LOGIKA BARU: KIRIM EMAIL SETELAH COMMIT ---
            // Kita taruh dalam try-catch sendiri agar jika email gagal (internet down/smtp error),
            // User TETAP diredirect ke halaman sukses, tidak error 500.
            if ($visitor->email) {
                try {
                    Mail::to($visitor->email)->send(new ReservationSuccess($visitor, false));
                } catch (\Exception $e) {
                    // Catat error di log file (storage/logs/laravel.log) tapi biarkan user lanjut
                    Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
                }
            }
            // -----------------------------------------------

            return redirect()->route('reservasi.success', ['uuid' => $visitor->uuid])
                             ->with('success', 'Reservasi berhasil dikirim! Cek email Anda untuk E-Ticket.');
                            
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan simpan data jika error database
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function success($uuid, \App\Services\QrCodeService $qr)
    {
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // Payload QR ber-signature (plant + uuid + signature)
        $qrPayload = $qr->generatePayload($visitor);

        return view('guest.success', compact('visitor', 'qrPayload'));
    }

    public function edit($uuid)
    {
        // 1. Cari data berdasarkan UUID
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // 2. Cek validasi sederhana (Opsional: Cegah edit jika sudah checkout atau tanggal sudah lewat)
        if ($visitor->checkout_at) {
            return redirect()->route('reservasi.success', $uuid)->with('error', 'Data tidak bisa diubah karena kunjungan telah selesai.');
        }

        // 3. Kita perlu memisahkan datetime menjadi date dan time agar sesuai dengan input HTML
        // Karena di database 'visit_datetime' adalah satu kolom, tapi di form biasanya pisah.
        $visitDate = $visitor->visit_datetime ? $visitor->visit_datetime->format('Y-m-d') : '';
        $visitTime = $visitor->visit_datetime ? $visitor->visit_datetime->format('H:i') : '';

        // 4. Return view edit dengan data visitor
        // Kita bisa gunakan view 'reservasi.form' jika dibuat dinamis, 
        // tapi untuk keamanan dan kerapian, saya sarankan buat file baru 'reservasi.edit'
        // yang isinya copy-paste dari form create namun dengan value terisi.
        return view('guest.edit', compact('visitor', 'visitDate', 'visitTime'));
    }

    public function update(Request $request, $uuid)
    {
        // Hapus dd($request->all());
        $visitor = Visitor::where('uuid', $uuid)->firstOrFail();

        // 1. VALIDASI (Sama seperti store)
        $validatedData = $request->validate([
            'visitor_type'      => 'required|string',
            'id_card'           => 'required|string', 

            'write_necessity'   => 'required|string', 
            'visit_date'        => 'required|date',
            'visit_time'        => 'required',
            'group_type'        => 'required|string',
            'is_production'     => 'required|in:0,1',
            
            
            // Validasi array kendaraan & penumpang
            'vehicle-no'        => 'nullable|array',
            'vehicle-no.*'      => 'nullable|string',
            'vehicle_type'      => 'nullable|array',
            'passenger-name'    => 'nullable|array',
            'passenger-name.*'  => 'nullable|string',
            'passenger-id'      => 'nullable|array',
            'passenger-age'     => 'nullable|array',
            'passenger-phone'   => 'nullable|array',
            //'g-recaptcha-response' => 'required',
        ]);

        // Verifikasi ReCaptcha (Opsional untuk edit, tapi disarankan tetap ada)
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json()['success']) {
            return back()->withInput()->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal.']);
        }

        // 2. OLAH DATA KENDARAAN (Sama dengan store)
        $vehiclesData = [];
        if ($request->has('vehicle-no')) {
            foreach ($request->input('vehicle-no') as $index => $number) {
                if (!empty($number)) {
                    $vehiclesData[] = [
                        'type'   => $request->input('vehicle_type')[$index] ?? 'Lain-lain',
                        'number' => $number,
                    ];
                }
            }
        }
        
        // 3. OLAH DATA ROMBONGAN (Sama dengan store)
        $groupMembersData = [];
        if ($request->input('group_type') === 'Rombongan' && $request->has('passenger-name')) {
            foreach ($request->input('passenger-name') as $index => $name) {
                if (!empty($name)) {
                    $groupMembersData[] = [
                        'id'    => $request->input('passenger-id')[$index] ?? null,
                        'name'  => $name,
                        'age'   => $request->input('passenger-age')[$index] ?? null,
                        'phone' => $request->input('passenger-phone')[$index] ?? null,
                    ];
                }
            }
        }

        // 4. UPDATE DATABASE
        try {
            DB::beginTransaction();

            $visitor->update([
                'visit_type'        => $validatedData['visitor_type'],
                'identity_number'   => $validatedData['id_card'],
                'name'              => $validatedData['name'],
                'age'               => $validatedData['age'],
                'phone_number'      => $validatedData['phone'],
                'company_name'      => $validatedData['company'],
                'address'           => $validatedData['address'],
                // emergency_phone dihapus
                'email'             => $validatedData['email'],
                //'internet'          => $validatedData['internet_access'] === 'Ada',
                'intended_employee' => $validatedData['intended_employee'],
                'purpose'           => $validatedData['necessity'],
                'purpose_note'      => $validatedData['write_necessity'],
                'special_category'  => $request->input('special_category'),
                'special_needs'     => $request->input('special_needs'),
                // Update datetime gabungan
                'visit_datetime'    => Carbon::parse($validatedData['visit_date'] . ' ' . $validatedData['visit_time']),
                'group_type'        => $validatedData['group_type'],
                // Update JSON columns
                'vehicles'          => $vehiclesData,      
                'group_members'     => $groupMembersData,  
                'is_production'   => $request->input('is_production'),
            ]);

            DB::commit();

            // 5. KIRIM EMAIL UPDATE
            if ($visitor->email) {
                try {
                    Mail::to($visitor->email)->send(new ReservationSuccess($visitor, true));
                } catch (\Exception $e) {
                    Log::error('Gagal mengirim email update: ' . $e->getMessage());
                }
            }

            // Redirect ke halaman success (Route name harus sesuai dengan web.php Anda)
            return redirect()->route('reservasi.success', ['uuid' => $visitor->uuid])
                             ->with('success', 'Data reservasi berhasil diperbarui / di-reschedule!');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }
}