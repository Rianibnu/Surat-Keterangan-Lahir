<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SklPdfController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/verify-skl/{uuid}', [\App\Http\Controllers\PublicSklController::class, 'verify'])->name('skl.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - semua yang login bisa akses
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware('can:dashboard.view')
        ->name('dashboard');

    // ============================================
    // BIRTH RECORDS - dengan permission per aksi
    // ============================================
    
    // View Index - semua role yang punya permission
    Route::get('birth-records', [\App\Http\Controllers\BirthRecordController::class, 'index'])
        ->middleware('can:birth-records.view')
        ->name('birth-records.index');

    // Create - staf, admin (HARUS SEBELUM route dengan parameter!)
    Route::get('birth-records/create', [\App\Http\Controllers\BirthRecordController::class, 'create'])
        ->middleware('can:birth-records.create')
        ->name('birth-records.create');
    
    Route::post('birth-records', [\App\Http\Controllers\BirthRecordController::class, 'store'])
        ->middleware('can:birth-records.create')
        ->name('birth-records.store');
    
    // View Detail - staf, dokter, viewer, admin
    Route::get('birth-records/{birth_record}', [\App\Http\Controllers\BirthRecordController::class, 'show'])
        ->middleware('can:birth-records.view')
        ->name('birth-records.show');

    // Edit - staf, admin
    Route::get('birth-records/{birth_record}/edit', [\App\Http\Controllers\BirthRecordController::class, 'edit'])
        ->middleware('can:birth-records.edit')
        ->name('birth-records.edit');
    
    Route::put('birth-records/{birth_record}', [\App\Http\Controllers\BirthRecordController::class, 'update'])
        ->middleware('can:birth-records.edit')
        ->name('birth-records.update');
    
    Route::patch('birth-records/{birth_record}', [\App\Http\Controllers\BirthRecordController::class, 'update'])
        ->middleware('can:birth-records.edit');

    // Delete - admin only
    Route::delete('birth-records/{birth_record}', [\App\Http\Controllers\BirthRecordController::class, 'destroy'])
        ->middleware('can:birth-records.delete')
        ->name('birth-records.destroy');

    // Export - staf, admin
    Route::get('birth-records-export', [\App\Http\Controllers\BirthRecordController::class, 'export'])
        ->middleware('can:birth-records.export')
        ->name('birth-records.export');

    // Generate SKL - staf, admin
    Route::post('birth-records/{id}/generate-skl', [\App\Http\Controllers\BirthRecordController::class, 'generateSkl'])
        ->middleware('can:skl.create')
        ->name('birth-records.generate-skl');

    // ============================================
    // DOCTORS - dengan permission per aksi
    // ============================================
    
    // View Index - semua role
    Route::get('doctors', [\App\Http\Controllers\DoctorController::class, 'index'])
        ->middleware('can:doctors.view')
        ->name('doctors.index');

    // Create - admin only (HARUS SEBELUM route dengan parameter!)
    Route::get('doctors/create', [\App\Http\Controllers\DoctorController::class, 'create'])
        ->middleware('can:doctors.create')
        ->name('doctors.create');
    
    Route::post('doctors', [\App\Http\Controllers\DoctorController::class, 'store'])
        ->middleware('can:doctors.create')
        ->name('doctors.store');
    
    // View Detail - semua role
    Route::get('doctors/{doctor}', [\App\Http\Controllers\DoctorController::class, 'show'])
        ->middleware('can:doctors.view')
        ->name('doctors.show');

    // Edit - admin only
    Route::get('doctors/{doctor}/edit', [\App\Http\Controllers\DoctorController::class, 'edit'])
        ->middleware('can:doctors.edit')
        ->name('doctors.edit');
    
    Route::put('doctors/{doctor}', [\App\Http\Controllers\DoctorController::class, 'update'])
        ->middleware('can:doctors.edit')
        ->name('doctors.update');
    
    Route::patch('doctors/{doctor}', [\App\Http\Controllers\DoctorController::class, 'update'])
        ->middleware('can:doctors.edit');

    // Delete - admin only
    Route::delete('doctors/{doctor}', [\App\Http\Controllers\DoctorController::class, 'destroy'])
        ->middleware('can:doctors.delete')
        ->name('doctors.destroy');

    // ============================================
    // SKL PDF Export
    // ============================================
    Route::get('birth-records/{birthRecord}/skl/download', [SklPdfController::class, 'download'])
        ->middleware('can:skl.download-pdf')
        ->name('skl.download');
    
    Route::get('birth-records/{birthRecord}/skl/preview', [SklPdfController::class, 'stream'])
        ->middleware('can:skl.view')
        ->name('skl.preview');

    // ============================================
    // ACTIVITY LOG (Admin only)
    // ============================================
    Route::middleware(['can:activity-log.view'])->group(function () {
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('activity-logs/{activity}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

    // ============================================
    // USER MANAGEMENT (Admin only)
    // ============================================
    Route::middleware(['can:users.view'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->middleware('can:users.create')->name('users.create');
        Route::post('users', [UserController::class, 'store'])->middleware('can:users.create')->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('can:users.edit')->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:users.edit')->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('can:users.delete')->name('users.destroy');
    });
});

require __DIR__.'/settings.php';