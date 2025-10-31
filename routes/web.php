<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAbsensiController;
use App\Http\Controllers\LaporanController;


Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // ✅ Route resource sudah otomatis mencakup semua route absensi
    Route::resource('absensi', AdminAbsensiController::class);
   
    // ✅ Route resource laporan juga otomatis mencakup CRUD
    Route::resource('laporan', LaporanController::class);

    // ✅ Route tambahan untuk export (boleh dipertahankan)
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', [UserController::class, 'index'])->name('user.dashboard');

    // Halaman absensi user
    Route::get('/absensi', [UserAbsensiController::class, 'index'])->name('user.absensi');

    // Check In
    Route::post('/absensi/masuk', [UserAbsensiController::class, 'store'])->name('user.absensi.masuk');

    // Check Out
    Route::post('/absensi/keluar', [UserAbsensiController::class, 'keluar'])->name('user.absensi.keluar');
});