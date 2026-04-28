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

/*
|--------------------------------------------------------------------------
| 1. Root Redirection
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));


/*
|--------------------------------------------------------------------------
| 2. Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/reservasi/reschedule/{uuid}', [VisitController::class, 'edit'])->name('reservasi.edit');
Route::put('/reservasi/update/{uuid}', [VisitController::class, 'update'])->name('reservasi.update');
Route::resource('reservasi', VisitController::class)->middleware(LogReservasiVisit::class);
Route::get('/reservasi/sukses/{uuid}', [VisitController::class, 'success'])->name('reservasi.success');
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
        Route::post('/{uuid}/scan', [DashboardController::class, 'scanVisitor'])->name('visitors.scan');
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