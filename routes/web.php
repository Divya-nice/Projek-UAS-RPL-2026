<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\LayananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// Halaman utama -> arahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| HALAMAN PELANGGAN (HARUS LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->controller(PesananController::class)->group(function () {

    // Beranda
    Route::get('/beranda', 'beranda')
        ->name('pesanan.beranda');

    // Katalog layanan
    Route::get('/layanan', 'katalog')
        ->name('pesanan.katalog');

    // Form pemesanan
    Route::get('/pesanan/buat', 'create')
        ->name('pesanan.form');

    // Simpan pesanan
    Route::post('/pesanan', 'store')
        ->name('pesanan.store');

    // Detail pesanan
    Route::get('/pesanan/{id}', 'show')
        ->name('pesanan.show');

    // Riwayat
    Route::get('/riwayat', 'riwayat')
        ->name('pesanan.riwayat');

    // Akun
    Route::get('/akun', 'akun')
        ->name('pesanan.akun');
});


/*
|--------------------------------------------------------------------------
| BACKEND
|--------------------------------------------------------------------------
*/

// Data layanan
Route::get('/layanan-data', [LayananController::class, 'index'])
    ->name('layanan.index');

// Semua pesanan (admin)
Route::get('/pesanan', [PesananController::class, 'index'])
    ->name('pesanan.index');