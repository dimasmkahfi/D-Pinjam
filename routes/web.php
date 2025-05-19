<?php

use App\Http\Controllers\Web\MobilController;
use App\Http\Controllers\Web\PemeriksaanController;
use App\Http\Controllers\Web\PeminjamanController;
use App\Http\Controllers\Web\SatpamController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Default Route
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (menggunakan Laravel UI atau Breeze)
Auth::routes();
// Authentication Routes (manual implementation)
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Hapus atau komentari baris yang memiliki Auth::routes()
// Auth::routes();

// Dashboard
Route::get('/dashboard', function () {
    if (auth()->user()->lvl_users == 'admin') {
        return redirect()->route('admin.users.index');
    } elseif (auth()->user()->lvl_users == 'kepala_bengkel') {
        return redirect()->route('kepalabengkel.pengajuan');
    } elseif (auth()->user()->lvl_users == 'pdi') {
        return redirect()->route('pdi.antrian');
    } elseif (auth()->user()->lvl_users == 'satpam') {
        return redirect()->route('satpam.list');
    } else {
        return view('dashboard');
    }
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Manajemen User
    Route::resource('users', UserController::class);

    // Manajemen Mobil
    Route::resource('mobil', MobilController::class);

    // Laporan
    Route::get('report', [MobilController::class, 'report'])->name('report');
});

// Kepala Bengkel Routes
Route::middleware(['auth', 'role:kepala_bengkel'])->prefix('kepala-bengkel')->name('kepalabengkel.')->group(function () {
    // Antrian Pengajuan
    Route::get('pengajuan', [PeminjamanController::class, 'antrianPengajuan'])->name('pengajuan');
    Route::post('setuju/{id}', [PeminjamanController::class, 'setujuPengajuan'])->name('setuju');
    Route::post('tolak/{id}', [PeminjamanController::class, 'tolakPengajuan'])->name('tolak');

    // Status Peminjaman
    Route::get('status', [PeminjamanController::class, 'statusPeminjaman'])->name('status');

    // Grafik Kendaraan
    Route::get('grafik', [PeminjamanController::class, 'grafikKendaraan'])->name('grafik');

    // Laporan
    Route::get('report', [PeminjamanController::class, 'reportPeminjaman'])->name('report');
    Route::get('export/pdf', [PeminjamanController::class, 'exportPDF'])->name('export.pdf');
    Route::get('export/excel', [PeminjamanController::class, 'exportExcel'])->name('export.excel');
});

// PDI Routes
Route::middleware(['auth', 'role:pdi'])->prefix('pdi')->name('pdi.')->group(function () {
    // Antrian Pemeriksaan
    Route::get('antrian', [PemeriksaanController::class, 'antrianCek'])->name('antrian');
    Route::get('cek/{id}', [PemeriksaanController::class, 'cekKendaraan'])->name('cek');
    Route::post('cek/{id}', [PemeriksaanController::class, 'simpanHasilCek'])->name('cek.simpan');

    // Riwayat Pemeriksaan
    Route::get('riwayat', [PemeriksaanController::class, 'riwayatPemeriksaan'])->name('riwayat');
});

// Satpam Routes
Route::middleware(['auth', 'role:satpam'])->prefix('satpam')->name('satpam.')->group(function () {
    Route::get('list', [SatpamController::class, 'listTransaksi'])->name('list');
    Route::post('mobil-keluar/{id}', [SatpamController::class, 'mobilKeluar'])->name('mobil-keluar');
    Route::post('mobil-masuk/{id}', [SatpamController::class, 'mobilMasuk'])->name('mobil-masuk');
});
