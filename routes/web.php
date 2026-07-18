<?php

use App\Http\Controllers\PesananController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal -> Login
Route::redirect('/', '/login');

// Halaman autentikasi
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    $request->session()->regenerate();

    return redirect()->route('pesanan.beranda');
});

/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/
Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        'phone'    => ['nullable', 'string', 'max:30'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'],
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);

    return redirect()->route('pesanan.beranda');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman Pelanggan (Harus Login)
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

    Route::view('/pesanan', 'admin.pesanan.index')->name('admin.pesanan');

    Route::view('/pesanan/detail', 'admin.pesanan.detail')->name('admin.pesanan.detail');

});