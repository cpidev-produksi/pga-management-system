<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\DataVisitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Middleware\LogReservasiVisit;
use App\Http\Controllers\ReservasiLogController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\PlantController;

/*
|--------------------------------------------------------------------------
| 1. Root Redirection
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));


/*
|--------------------------------------------------------------------------
| 2. Public Routes (Reservasi per-plant via link, mis. /reservasi/SLT)
|--------------------------------------------------------------------------
*/
// Route spesifik (kata kunci tetap) didefinisikan SEBELUM route dinamis {plant:code}
Route::get('/reservasi/reschedule/{uuid}', [VisitController::class, 'edit'])->name('reservasi.edit');
Route::put('/reservasi/update/{uuid}', [VisitController::class, 'update'])->name('reservasi.update');
Route::get('/reservasi/sukses/{uuid}', [VisitController::class, 'success'])->name('reservasi.success');

// Form & submit reservasi (single link; plant dipilih di dalam halaman)
Route::get('/reservasi', [VisitController::class, 'create'])
    ->middleware(LogReservasiVisit::class)
    ->name('reservasi.create');
Route::post('/reservasi', [VisitController::class, 'store'])->name('reservasi.store');

Route::get('/offline', fn () => view('offline'));


/*
|--------------------------------------------------------------------------
| 3. Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| 4. Protected Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ================= Plant Selector & Management (Super Admin) =================
    // Halaman pemilih plant (dibuka setelah super admin login)
    Route::get('/plants/select', [PlantController::class, 'select'])
        ->middleware('super.admin')->name('plants.select');

    Route::middleware('super.admin')->group(function () {
        // Perpindahan konteks plant
        Route::post('/plants/all', [PlantController::class, 'allPlants'])->name('plants.all');
        Route::post('/plants/{plant}/switch', [PlantController::class, 'switch'])->name('plants.switch');

        // CRUD pengelolaan plant (route statis sebelum route dinamis {plant})
        Route::get('/plants', [PlantController::class, 'index'])->name('plants.index');
        Route::get('/plants/create', [PlantController::class, 'create'])->name('plants.create');
        Route::post('/plants', [PlantController::class, 'store'])->name('plants.store');
        Route::get('/plants/{plant}/edit', [PlantController::class, 'edit'])->name('plants.edit');
        Route::put('/plants/{plant}', [PlantController::class, 'update'])->name('plants.update');
        Route::delete('/plants/{plant}', [PlantController::class, 'destroy'])->name('plants.destroy');
    });

    // ================= Dashboard & Main Features =================
    Route::get('/dashboard/details/today', [DashboardController::class, 'detailTotalToday'])->name('dashboard.detail.today');
    Route::get('/dashboard/details/onsite', [DashboardController::class, 'detailOnSite'])->name('dashboard.detail.onsite');
    Route::get('/dashboard/details/expected', [DashboardController::class, 'detailExpected'])->name('dashboard.detail.expected');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activity-logs', [LoginActivityController::class, 'index'])->name('activity-logs.index');
    Route::get('/reservasi-stats', [ReservasiLogController::class, 'index'])->name('reservasi-stats.index');
    
    // Visitor Scanner (Fitur Security/Admin)
    // PERBAIKAN: Hapus '/visitors' di dalam group karena sudah ada prefix
    Route::prefix('visitors')->group(function () {
        Route::get('/', [DataVisitorController::class, 'index'])->name('visitors.index')->middleware('verified');
        
        // Sebelum: '/visitors/export-excel' -> Hasil URL: /visitors/visitors/export-excel (Dobel)
        // Sesudah: '/export-excel' -> Hasil URL: /visitors/export-excel (Benar)
        Route::get('/export-excel', [DataVisitorController::class, 'exportExcel'])->name('visitors.export_excel');
        
        Route::get('/{uuid}/export-pdf', [DataVisitorController::class, 'exportPdf'])->name('visitors.export_pdf');
        
        Route::get('/{uuid}', [DataVisitorController::class, 'show'])->name('visitors.show');
        Route::post('/scan', [DashboardController::class, 'scanVisitor'])->name('visitors.scan');
        Route::post('/{uuid}/checkout', [DataVisitorController::class, 'checkout'])->name('visitors.checkout');
    });
    
    Route::get('/dashboard/visitor-scanner', [DashboardController::class, 'showVisitorScanner'])->name('visitor.scanner');

    // ================= User Management =================
    Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::put('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::resource('users', UserController::class);

    Route::get('departments/trash', [DepartmentController::class, 'trash'])->name('departments.trash');
    Route::put('departments/{department}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('departments/{department}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.force-delete');
    Route::resource('departments', DepartmentController::class);

    // ================= Profile Management (READ ONLY) =================
    Route::prefix('profile')->group(function () {
        // Hanya biarkan route ini agar User bisa melihat halaman profile
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        
        // MATIKAN (Comment Out) route update dan destroy agar benar-benar aman
        // Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        // Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    Route::get('change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('change-password', [PasswordController::class, 'update'])->name('password.update');

    Route::resource('roles', RoleController::class)->only(['index', 'edit', 'update']);
});