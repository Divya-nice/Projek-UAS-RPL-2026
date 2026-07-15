<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

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