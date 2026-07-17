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
    Route::get('/pesanan/pengantaran', 'pengantaran')->name('pesanan.pengantaran');
    Route::post('/pesanan/pengantaran', 'prosesPengantaran')->name('pesanan.pengantaran.proses');
    Route::get('/pesanan/ringkasan', 'ringkasan')->name('pesanan.ringkasan');
    Route::get('/pesanan/pembayaran', 'pembayaran')->name('pesanan.pembayaran');
    Route::post('/pesanan/pembayaran', 'prosesPembayaran')->name('pesanan.pembayaran.proses');
    Route::get('/pesanan/berhasil', 'berhasil')->name('pesanan.berhasil');
    Route::get('/riwayat', 'riwayat')->name('pesanan.riwayat');
    Route::get('/riwayat/nota/{kode}', 'nota')->name('pesanan.nota');
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

/*
|--------------------------------------------------------------------------
| Admin (UI only) — branch feature/dashboard-admin-ui
| View-only routes; tidak menyentuh controller/model/migration/database.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // Login Admin (halaman terpisah dari login pelanggan)
Route::view('/login', 'auth.login-admin')->name('admin.login');
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);
    // UI-only: langsung arahkan ke dashboard admin.
    return redirect()->route('admin.dashboard');
})->name('admin.login.submit');

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
