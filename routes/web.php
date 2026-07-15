<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

// Halaman utama menampilkan Beranda Pelanggan
Route::get('/', [PesananController::class, 'beranda'])->name('pesanan.beranda');

// Halaman Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses Login
Route::post('/login', [AuthController::class, 'login']);

// Halaman Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Proses Register
Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

// Alur pemesanan
Route::controller(PesananController::class)->group(function () {
    Route::get('/beranda', 'beranda')->name('pesanan.beranda.alt');
    Route::get('/layanan', 'katalog')->name('pesanan.katalog');
    Route::get('/pesanan/buat', 'form')->name('pesanan.form');
    Route::post('/pesanan/buat', 'prosesForm')->name('pesanan.form.proses');
    Route::get('/pesanan/ringkasan', 'ringkasan')->name('pesanan.ringkasan');
    Route::post('/pesanan/konfirmasi', 'konfirmasi')->name('pesanan.konfirmasi');
    Route::get('/pesanan/pembayaran', 'pembayaran')->name('pesanan.pembayaran');
    Route::post('/pesanan/pembayaran', 'prosesPembayaran')->name('pesanan.pembayaran.proses');
    Route::get('/pesanan/berhasil', 'berhasil')->name('pesanan.berhasil');
    Route::get('/riwayat', 'riwayat')->name('pesanan.riwayat');
    Route::get('/akun', 'akun')->name('pesanan.akun');
});
