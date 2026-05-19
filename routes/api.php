<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AntreanController;
use App\Http\Controllers\Api\SesiAntreanController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SupirController;
use App\Http\Controllers\Api\LaporanPengisianController;
use App\Http\Controllers\Api\InformasiPerusahaanController;

// Public routes (tanpa auth)
Route::prefix('auth')->group(function () {
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// ✅ Route Supir - TANPA AUTH
Route::prefix('supir')->group(function () {
    Route::post('validasi-barcode', [SupirController::class, 'validasiBarcode']);
    Route::post('daftar-antrean',   [SupirController::class, 'daftarAntrean']);
    Route::get('status-antrean/{kode}', [SupirController::class, 'statusAntrean']);
});

// ✅ Public Laporan - Supir bisa membuat laporan tanpa login
Route::post('laporan', [LaporanController::class, 'store']);

// Protected routes (perlu token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // Sesi Barcode (Satpam)
    Route::post('sesi/generate-barcode', [SesiAntreanController::class, 'generateQR']);
    Route::get('sesi/status-barcode',    [SesiAntreanController::class, 'statusQR']);

    // Validasi Satpam
    Route::get('satpam/antrean-pending',        [AntreanController::class, 'antreanPending']);
    Route::post('satpam/validasi/{id}',         [AntreanController::class, 'validasiSatpam']);

    // Antrean
    Route::get('antrean',                    [AntreanController::class, 'index']);
    Route::get('antrean/panggil-berikutnya', [AntreanController::class, 'panggilBerikutnya']);
    Route::get('antrean/{id}',               [AntreanController::class, 'show']);
    Route::put('antrean/{id}/status',        [AntreanController::class, 'updateStatus']);

    // Laporan (Read & Admin only - perlu auth)
    Route::get('laporan',                        [LaporanController::class, 'index']);
    Route::get('laporan/{id}',                   [LaporanController::class, 'show']);
    Route::post('laporan/{id}/verifikasi',       [LaporanController::class, 'verifikasi']);
    Route::delete('laporan/{id}',                [LaporanController::class, 'destroy']);

    // User Management
    Route::get('users',                          [UserController::class, 'index']);
    Route::post('users',                         [UserController::class, 'store']);
    Route::get('users/{id}',                     [UserController::class, 'show']);
    Route::put('users/{id}',                     [UserController::class, 'update']);
    Route::delete('users/{id}',                  [UserController::class, 'destroy']);
    Route::post('users/{id}/reset-password',     [UserController::class, 'resetPassword']);
    Route::patch('users/{id}/toggle-status',     [UserController::class, 'toggleStatus']);

    // Dashboard
    Route::get('dashboard',       [DashboardController::class, 'index']);
    Route::get('dashboard/rekap', [DashboardController::class, 'rekap']);

  // ... route yang sudah ada ...

    // Antrean - tambahan
    Route::put('antrean/{id}/estimasi',           [AntreanController::class, 'updateEstimasi']);
    Route::put('antrean/{id}/prioritas',          [AntreanController::class, 'updatePrioritas']);
    Route::post('antrean/{id}/selesai-pengisian', [AntreanController::class, 'selesaikanPengisian']);

    // Laporan Pengisian
    Route::get('laporan-pengisian',           [LaporanPengisianController::class, 'index']);
    Route::get('laporan-pengisian/statistik', [LaporanPengisianController::class, 'statistik']);
    Route::get('laporan-pengisian/{id}',      [LaporanPengisianController::class, 'show']);

    // Informasi Perusahaan
    Route::get('perusahaan',         [InformasiPerusahaanController::class, 'index']);
    Route::post('perusahaan',        [InformasiPerusahaanController::class, 'store']);
    Route::get('perusahaan/{id}',    [InformasiPerusahaanController::class, 'show']);
    Route::put('perusahaan/{id}',    [InformasiPerusahaanController::class, 'update']);
    Route::delete('perusahaan/{id}', [InformasiPerusahaanController::class, 'destroy']);
});