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
// Forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Reset password
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::put('/admin/absensi/{id}/update', [AdminController::class, 'updateKeterangan'])->name('admin.absensi.updateKeterangan');
    Route::delete('/admin/absensi/{id}/delete', [AdminController::class, 'delete'])->name('admin.absensi.delete');
    Route::get('/admin/absensi/{id}/location', [UserAbsensiController::class, 'showLocation'])->name('absensi.location');

    Route::get('/user/create', [AdminController::class, 'create'])->name('admin.user.create');
    Route::post('/user/store', [AdminController::class, 'store'])->name('admin.user.store');
    Route::get('/user/list', [AdminController::class, 'indexUser'])->name('admin.user.index');
    Route::get('/user/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [AdminController::class, 'destroyUser'])->name('admin.user.destroy');
    // ✅ Route resource sudah otomatis mencakup semua route absensi
    Route::resource('absensi', AdminAbsensiController::class);
   
    // ✅ Route resource laporan juga otomatis mencakup CRUD
    Route::resource('laporan', LaporanController::class);

    // ✅ Route tambahan untuk export (boleh dipertahankan)
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    // dashboard user
    Route::get('/dashboard/user', [UserAbsensiController::class, 'index'])->name('user.dashboard');

    // Halaman absensi user
    Route::get('/absensi', [UserAbsensiController::class, 'index'])->name('user.absensi');

    // Check-in
    Route::post('/absensi/masuk', [UserAbsensiController::class, 'store'])->name('user.absensi.masuk');

    // Check-out
    Route::post('/absensi/keluar', [UserAbsensiController::class, 'keluar'])->name('user.absensi.keluar');

    

});