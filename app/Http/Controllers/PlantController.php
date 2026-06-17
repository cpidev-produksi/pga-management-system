<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Services\PlantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * PlantController
 * ---------------
 * - Pemilih plant & perpindahan konteks plant untuk Super Admin.
 * - Mode "Semua Plant" (gabungan).
 * - CRUD pengelolaan plant (khusus Super Admin).
 *
 * Catatan: semua route controller ini diproteksi middleware 'super.admin'
 */
class PlantController extends Controller
{
    public function __construct(private PlantService $plantService)
    {
    }

    public function select(): View
    {
        $plants = Plant::where('is_active', true)->orderBy('name')->get();

        // Jumlah visitor hari ini per plant (lewati global scope agar akurat).
        $todayCounts = \App\Models\Visitor::withoutGlobalScope('plant')
            ->whereDate('visit_datetime', today())
            ->selectRaw('plant_uuid, COUNT(*) as total')
            ->groupBy('plant_uuid')
            ->pluck('total', 'plant_uuid');

        return view('plants.select', compact('plants', 'todayCounts'));
    }

    /**
     * Pilih satu plant -> set konteks plant aktif.
     */
    public function switch(Plant $plant): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->plantService->setCurrentPlant($plant->uuid, $user)) {
            return back()->withErrors(['plant' => 'Anda tidak memiliki akses ke plant tersebut.']);
        }

        session()->forget('all_plants_mode');

        return redirect()->route('dashboard')
            ->with('success', 'Beralih ke plant: ' . $plant->name);
    }

    /**
     * Mode "Semua Plant" (gabungan) -> kosongkan plant aktif.
     */
    public function allPlants(): RedirectResponse
    {
        // Hanya super admin yang boleh melihat semua plant.
        session()->forget('current_plant_uuid');
        session()->forget('current_plant_name');
        session(['all_plants_mode' => true]);

        return redirect()->route('dashboard')
            ->with('success', 'Menampilkan data gabungan: Semua Plant.');
    }

    // =========================================================
    // CRUD PENGELOLAAN PLANT (Super Admin)
    // =========================================================

    public function index(): View
    {
        $plants = Plant::withCount([
            'users',
            'visitors' => fn ($q) => $q->withoutGlobalScope('plant'),
        ])->orderBy('name')->get();

        return view('plants.index', compact('plants'));
    }

    public function create(): View
    {
        return view('plants.form', ['plant' => new Plant()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $plant = Plant::create($data);
        $this->handlePosterUpload($request, $plant);

        return redirect()->route('plants.index')
            ->with('success', 'Plant baru berhasil ditambahkan.');
    }

    public function edit(Plant $plant): View
    {
        return view('plants.form', compact('plant'));
    }

    public function update(Request $request, Plant $plant): RedirectResponse
    {
        $data = $this->validateData($request, $plant);

        $plant->update($data);
        $this->handlePosterUpload($request, $plant);

        return redirect()->route('plants.index')
            ->with('success', 'Data plant berhasil diperbarui.');
    }

    public function destroy(Plant $plant): RedirectResponse
    {
        // Cegah hapus bila masih ada data terkait, demi keamanan data audit.
        if ($plant->visitors()->withoutGlobalScope('plant')->exists() || $plant->users()->exists()) {
            return back()->withErrors([
                'plant' => 'Plant tidak bisa dihapus karena masih memiliki data user/visitor.',
            ]);
        }

        $plant->delete();

        return redirect()->route('plants.index')
            ->with('success', 'Plant berhasil dihapus.');
    }

    /**
     * Validasi data plant (create & update).
     */
    private function validateData(Request $request, ?Plant $plant = null): array
    {
        $uniqueCode = Rule::unique('plants', 'code')
            ->whereNull('deleted_at');

        if ($plant && $plant->exists) {
            $uniqueCode = $uniqueCode->ignore($plant->uuid, 'uuid');
        }

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'code'         => ['required', 'string', 'max:20', 'alpha_num', $uniqueCode],
            'location'     => ['nullable', 'string'],
            'description'  => ['nullable', 'string'],
            'is_active'    => ['nullable', 'boolean'],
            'poster_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'poster_image.image' => 'Poster harus berupa file gambar.',
            'poster_image.mimes' => 'Format poster harus JPG, PNG, atau WEBP.',
            'poster_image.max'   => 'Ukuran poster maksimal 4 MB.',
        ]);

        // File poster ditangani terpisah (handlePosterUpload), jangan ikut mass-assign.
        unset($validated['poster_image']);
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    /**
     * Simpan poster yang di-upload (jika ada) ke disk 'public' dan catat path-nya.
     * File lama hasil upload akan dihapus saat diganti.
     */
    private function handlePosterUpload(Request $request, Plant $plant): void
    {
        if (! $request->hasFile('poster_image')) {
            return;
        }

        // Hapus file lama bila sebelumnya hasil upload (path diawali 'plants/').
        if ($plant->poster_image && Str::startsWith($plant->poster_image, 'plants/')) {
            Storage::disk('public')->delete($plant->poster_image);
        }

        // Simpan ke storage/app/public/plants -> mengembalikan 'plants/namafile.ext'
        $path = $request->file('poster_image')->store('plants', 'public');

        $plant->update(['poster_image' => $path]);
    }
}