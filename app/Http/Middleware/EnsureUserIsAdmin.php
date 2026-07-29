<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Pastikan user yang login berstatus admin sebelum masuk ke area /admin.
     * Sebelumnya seluruh route admin (dashboard, verifikasi pembayaran,
     * kelola layanan, laporan) tidak punya proteksi otorisasi sama sekali.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login')
                ->with('info', 'Silakan login sebagai admin terlebih dahulu.');
        }

        if (! Auth::user()->is_admin) {
            abort(403, 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}