<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


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

// Login Admin
Route::get('/admin/login', function () {
    return view('auth.login-admin');
})->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'adminLogin'])
    ->name('admin.login.submit');
/*
|--------------------------------------------------------------------------
| Admin (UI Only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // Kelola Pesanan
Route::get('/pesanan', [AdminController::class, 'pesanan'])
    ->name('admin.pesanan');

    Route::get('/pesanan/{kode}', [AdminController::class,'detailPesanan'])->name('admin.pesanan.detail');

   // Kelola Layanan
Route::get('/layanan', [AdminController::class, 'layanan'])
    ->name('admin.layanan');

Route::get('/layanan/tambah', [AdminController::class, 'createLayanan'])
    ->name('admin.layanan.create');

Route::post('/layanan', [AdminController::class, 'storeLayanan'])
    ->name('admin.layanan.store');

Route::get('/layanan/{layanan}/edit', [AdminController::class, 'editLayanan'])
    ->name('admin.layanan.edit');

Route::put('/layanan/{layanan}', [AdminController::class, 'updateLayanan'])
    ->name('admin.layanan.update');

Route::delete('/layanan/{layanan}', [AdminController::class, 'destroyLayanan'])
    ->name('admin.layanan.destroy');
    
    // Verifikasi Pembayaran

Route::get('/verifikasi', [AdminController::class, 'verifikasi'])
    ->name('admin.verifikasi');

Route::get('/verifikasi/{kode}', [AdminController::class, 'detailVerifikasi'])
    ->name('admin.verifikasi.detail');

Route::post('/verifikasi/{kode}/terima',
    [AdminController::class, 'terimaPembayaran'])
    ->name('admin.verifikasi.terima');

Route::post('/verifikasi/{kode}/tolak',
    [AdminController::class, 'tolakPembayaran'])
    ->name('admin.verifikasi.tolak');

    // Laporan Pendapatan
    Route::view('/laporan', 'admin.laporan.index')->name('admin.laporan');

    // Edit Profil & Ubah Password
Route::view('/profil', 'admin.profil')->name('admin.profil');

// Update Profil
Route::post('/profil/update', [AuthController::class, 'updateAdminProfile'])
    ->name('admin.profile.update');

// Halaman Ubah Password
Route::view('/ubah-password', 'admin.password')
    ->name('admin.password');

// Update Password
Route::post('/ubah-password', [AuthController::class, 'updatePassword'])
    ->name('admin.password.update');

    // Logout admin (UI only)
    Route::get('/logout', function () {
        return redirect('/');
    })->name('admin.logout');
;
});