<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

// Halaman awal -> login
Route::redirect('/', '/login');

// Halaman autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Halaman logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman Pelanggan
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->controller(PesananController::class)->group(function () {

    Route::get('/beranda', 'beranda')->name('pesanan.beranda');

    Route::get('/layanan', 'katalog')->name('pesanan.katalog');

    Route::get('/pesanan/buat', 'form')->name('pesanan.form');
    Route::post('/pesanan/buat', 'prosesForm')->name('pesanan.form.proses');
  
    Route::get('/pesanan/pengantaran', 'pengantaran')->name('pesanan.pengantaran');
    Route::post('/pesanan/pengantaran', 'prosesPengantaran')->name('pesanan.pengantaran.proses');
    Route::get('/pesanan/ringkasan', 'ringkasan')->name('pesanan.ringkasan');

    Route::get('/pesanan/pembayaran', 'pembayaran')->name('pesanan.pembayaran');
    Route::post('/pesanan/pembayaran', 'prosesPembayaran')->name('pesanan.pembayaran.proses');

    Route::get('/pesanan/berhasil', 'berhasil')->name('pesanan.berhasil');

    Route::get('/pesanan/upload-berhasil', 'buktiBerhasil')->name('pesanan.bukti.berhasil');

    Route::get('/pesanan/{kode}/bayar', 'bayar')->name('pesanan.bayar');

    Route::get('/pesanan/{kode}/bukti', 'bukti')->name('pesanan.bukti');

    Route::post('/pesanan/{kode}/bukti', 'prosesBukti')->name('pesanan.bukti.proses');

    Route::post('/pesanan/{kode}/batalkan', 'batalkan')->name('pesanan.batalkan');

    Route::get('/riwayat', 'riwayat')->name('pesanan.riwayat');  
  
    Route::get('/riwayat/nota/{kode}', 'nota')->name('pesanan.nota');

    // Akun
    Route::get('/akun', 'akun')->name('pesanan.akun');
    Route::put('/akun', 'updateAkun')->name('akun.update');
});

/*
|--------------------------------------------------------------------------
| Admin (UI Only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // Kelola Pesanan
    Route::view('/pesanan', 'admin.pesanan.index')->name('admin.pesanan');
    Route::view('/pesanan/detail', 'admin.pesanan.detail')->name('admin.pesanan.detail');
    Route::post('/pesanan/update-status', function () {
        return back()->with('status', 'Status pesanan berhasil diperbarui.');
    })->name('admin.pesanan.update');

    // Kelola Layanan
    Route::view('/layanan', 'admin.layanan.index')->name('admin.layanan');
    Route::view('/layanan/tambah', 'admin.layanan.form', ['mode' => 'create'])->name('admin.layanan.create');
    Route::view('/layanan/edit', 'admin.layanan.form', ['mode' => 'edit'])->name('admin.layanan.edit');
    Route::post('/layanan', function () {
        return redirect()->route('admin.layanan')->with('status', 'Layanan baru berhasil ditambahkan.');
    })->name('admin.layanan.store');
    Route::post('/layanan/update', function () {
        return redirect()->route('admin.layanan')->with('status', 'Layanan berhasil diperbarui.');
    })->name('admin.layanan.update');
    Route::post('/layanan/hapus', function () {
        return redirect()->route('admin.layanan')->with('status', 'Layanan berhasil dihapus.');
    })->name('admin.layanan.destroy');

    // Verifikasi Pembayaran
    Route::view('/verifikasi', 'admin.verifikasi.index')->name('admin.verifikasi');
    Route::view('/verifikasi/detail', 'admin.verifikasi.detail')->name('admin.verifikasi.detail');
    Route::post('/verifikasi/terima', function () {
        return redirect()->route('admin.verifikasi')->with('status', 'Pembayaran berhasil diverifikasi.');
    })->name('admin.verifikasi.terima');
    Route::post('/verifikasi/tolak', function () {
        return redirect()->route('admin.verifikasi')->with('status', 'Pembayaran telah ditolak.');
    })->name('admin.verifikasi.tolak');

    // Laporan Pendapatan
    Route::view('/laporan', 'admin.laporan.index')->name('admin.laporan');

    // Edit Profil & Ubah Password
    Route::view('/profil', 'admin.profil')->name('admin.profil');
    Route::post('/profil', function () {
        return back()->with('status', 'Profil berhasil diperbarui.');
    })->name('admin.profil.update');
    Route::view('/ubah-password', 'admin.password')->name('admin.password');
    Route::post('/ubah-password', function () {
        return back()->with('status', 'Password berhasil diubah.');
    })->name('admin.password.update');

    // Logout admin (UI only)
    Route::get('/logout', function () {
        return redirect('/');
    })->name('admin.logout');
});
