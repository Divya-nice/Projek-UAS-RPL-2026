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

// Halaman utama menampilkan Beranda Pelanggan
Route::get('/', [PesananController::class, 'beranda'])->name('pesanan.beranda');

/*
| Alur Pemesanan (frontend)
*/
Route::controller(PesananController::class)->group(function () {
    Route::get('/beranda', 'beranda')->name('pesanan.beranda.alt');
    Route::get('/layanan', 'katalog')->name('pesanan.katalog');
    Route::get('/pesanan/buat', 'form')->name('pesanan.form');
    Route::post('/pesanan/buat', 'prosesForm')->name('pesanan.form.proses');
    Route::get('/pesanan/ringkasan', 'ringkasan')->name('pesanan.ringkasan');
    Route::post('/pesanan/konfirmasi', 'konfirmasi')->name('pesanan.konfirmasi');
    Route::get('/pesanan/berhasil', 'berhasil')->name('pesanan.berhasil');
    Route::get('/riwayat', 'riwayat')->name('pesanan.riwayat');
    Route::get('/akun', 'akun')->name('pesanan.akun');
});

// Halaman autentikasi (tampilan) — UI login/register TIDAK diubah.
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

/*
| Handler autentikasi (POST) — logika ditaruh langsung di sini
| supaya tidak butuh controller terpisah / autoload tambahan.
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

    return redirect()->intended(route('pesanan.beranda'));
});

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
        'password' => Hash::make($data['password']),
    ]);

    Auth::login($user);

    return redirect()->route('pesanan.beranda');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('pesanan.beranda');
})->name('logout');
