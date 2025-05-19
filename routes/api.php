<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MobilController;
use App\Http\Controllers\API\TransaksiController;
use App\Http\Controllers\API\AddOnController;
use Illuminate\Http\Request;

// Routes Publik (tanpa autentikasi)
Route::get('/test', function () {
    return response()->json(['message' => 'Test berhassil!']);
});
Route::post('/test-post', function () {
    return response()->json(['message' => 'Post berhasil!']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-alt', function (Request $request) {
    // Implementasi login sederhana
    $username = $request->input('username');
    $password = $request->input('password');

    $user = \App\Models\User::where('username', $username)->first();

    if (!$user || $user->password != $password) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'user' => $user,
        'token' => $username,
    ]);
});
// Routes yang membutuhkan autentikasi
// Auth Routes
Route::get('/user', [AuthController::class, 'user']);
Route::post('/update-face', [AuthController::class, 'updateFace']);

// Mobil Routes
Route::get('/mobil', [MobilController::class, 'index']);
Route::get('/mobil/available', [MobilController::class, 'available']);
Route::get('/mobil/{id}', [MobilController::class, 'show']);
Route::post('/mobil', [MobilController::class, 'store']);
Route::put('/mobil/{id}', [MobilController::class, 'update']);
Route::delete('/mobil/{id}', [MobilController::class, 'destroy']);

// Transaksi Routes
Route::get('/transaksi', [TransaksiController::class, 'index']);
Route::get('/transaksi/security', [TransaksiController::class, 'listTransaksiSatpam']);
Route::post('/transaksi/mobil-keluar/{id}', [TransaksiController::class, 'mobilKeluar']);
Route::post('/transaksi/mobil-masuk/{id}', [TransaksiController::class, 'mobilMasuk']);
Route::post('/transaksi/cancel/{id}', [TransaksiController::class, 'cancelTransaksi']);

Route::get('/transaksi/by-penyewa/{penyewa}', [TransaksiController::class, 'independent_transaksi_list']);
Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
Route::post('/transaksi/creates', [TransaksiController::class, 'stores']); // Ubah ini dari /transaksi/create


Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);
Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan']);
Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan']);
Route::post('/transaksi/{id}/verifikasi-wajah-masuk', [TransaksiController::class, 'verifikasiWajahMasuk']);
Route::post('/transaksi/{id}/verifikasi-wajah-keluar', [TransaksiController::class, 'verifikasiWajahKeluar']);

// AddOn Routes
Route::get('/add-on', [AddOnController::class, 'index']);
Route::get('/add-on/{id}', [AddOnController::class, 'show']);
Route::post('/add-on', [AddOnController::class, 'store']);
Route::put('/add-on/{id}', [AddOnController::class, 'update']);
Route::delete('/add-on/{id}', [AddOnController::class, 'destroy']);
