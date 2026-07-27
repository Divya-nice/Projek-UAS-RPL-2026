<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Proses login
   // Proses login
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        if ($request->email === 'admin1@gmail.com') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('pesanan.beranda');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}

// Login Admin
public function adminLogin(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {

        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password admin salah.',
    ]);
}

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    public function updateAdminProfile(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'nullable',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = Auth::user();

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;

    if ($request->hasFile('foto')) {
    $foto = $request->file('foto')->store('foto-profil', 'public');
    $user->foto = $foto;
}
$user->foto = $foto;

dd($user->getAttributes());
$user->save();

$user->refresh();
dd($user->foto);

    return back()->with('success', 'Profil berhasil diperbarui.');
}
public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Password lama salah.');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Password berhasil diubah.');
}
}
