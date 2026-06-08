<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Web\Admin\AntreanController as AdminAntrean;
use App\Http\Controllers\Web\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Web\Admin\PengisianController as AdminPengisian;
use App\Http\Controllers\Web\Admin\PerusahaanController as AdminPerusahaan;
use App\Http\Controllers\Web\Admin\UserController as AdminUser;
use App\Http\Controllers\Web\Operator\DashboardController as OperatorDashboard;
use App\Http\Controllers\Web\Operator\AntreanController as OperatorAntrean;
use App\Http\Controllers\Web\Operator\PengisianController as OperatorPengisian;
use App\Http\Controllers\Web\Operator\LaporanController as OperatorLaporan;
use App\Http\Controllers\ProfileController;

// Ultra-simple test
Route::get('/test', function () {
    return 'TEST OK';
});

// Diagnostic route
Route::get('/diagnostic', function () {
    return response()->json([
        'status' => 'working',
        'time' => now(),
        'env' => config('app.env'),
        'debug' => config('app.debug'),
        'routes_count' => count(\Route::getRoutes()),
        'db_connection' => config('database.default'),
        'db_host' => config('database.connections.mysql.host'),
    ], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
});

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'time' => now()]);
});

// Simple test route
Route::get('/test-text', function () {
    return "HELLO WORLD - " . now();
});

// API test
Route::get('/test-json', function () {
    return ['message' => 'API working', 'timestamp' => now()];
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard',         [AdminDashboard::class,  'index'])->name('dashboard');
        Route::resource('antrean',       AdminAntrean::class);
        Route::resource('laporan',       AdminLaporan::class);
        Route::post('laporan/{id}/verifikasi', [AdminLaporan::class, 'verifikasi'])->name('laporan.verifikasi');
        Route::resource('pengisian',     AdminPengisian::class);
        Route::resource('perusahaan',    AdminPerusahaan::class);
        Route::resource('users',         AdminUser::class);
        Route::patch('users/{id}/toggle-status', [AdminUser::class, 'toggleStatus'])->name('users.toggle-status');

        // Export
        Route::get('export/laporan',  [AdminLaporan::class,   'export'])->name('export.laporan');
        Route::get('export/antrean',  [AdminAntrean::class,   'export'])->name('export.antrean');
        Route::get('export/pengisian',[AdminPengisian::class, 'export'])->name('export.pengisian');
    });

// Operator routes
Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function () {
        Route::get('/dashboard',          [OperatorDashboard::class, 'index'])->name('dashboard');
        Route::resource('antrean',        OperatorAntrean::class);
        Route::post('antrean/{id}/panggil',  [OperatorAntrean::class, 'panggil'])->name('antrean.panggil');
        Route::post('antrean/{id}/status',   [OperatorAntrean::class, 'updateStatus'])->name('antrean.status');
        Route::post('antrean/{id}/prioritas',[OperatorAntrean::class, 'updatePrioritas'])->name('antrean.prioritas');
        Route::resource('pengisian',      OperatorPengisian::class);
        Route::resource('laporan',        OperatorLaporan::class);
        // Informasi Perusahaan (read only untuk operator)
Route::get('perusahaan', function() {
    $perusahaan = \App\Models\InformasiPerusahaan::where('status', 'aktif')
        ->orderBy('is_prioritas', 'desc')
        ->orderBy('nama_perusahaan', 'asc')
        ->get();
    return view('operator.perusahaan', compact('perusahaan'));
})->name('perusahaan');
    });

// Redirect generic dashboard route ke dashboard role yang tepat
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin'))    return redirect()->route('admin.dashboard');
    if ($user->hasRole('operator')) return redirect()->route('operator.dashboard');
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Redirect setelah login berdasarkan role (alias /home ke /dashboard)
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');