<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Halaman daftar verifikasi
    public function verifikasi(Request $request)
    {
        $pending = collect($request->session()->get('pesanan_list', []))
            ->where('status', 'Menunggu Verifikasi')
            ->values();

        return view('admin.verifikasi.index', compact('pending'));
    }

   // Halaman detail verifikasi
public function detailVerifikasi($kode, Request $request)
{
    $pesanan = collect($request->session()->get('pesanan_list', []))
        ->firstWhere('no_pesanan', $kode);

    return view('admin.verifikasi.detail', compact('pesanan'));
}
    // Terima pembayaran
public function terimaPembayaran($kode, Request $request)
{
    $pesananList = $request->session()->get('pesanan_list', []);

    foreach ($pesananList as &$pesanan) {
        if ($pesanan['kode'] === $kode) {
            $pesanan['status'] = 'Diproses';
        }
    }

    $request->session()->put('pesanan_list', $pesananList);

    return redirect()
        ->route('admin.verifikasi')
        ->with('success', 'Pembayaran berhasil diterima.');
}

// Tolak pembayaran
public function tolakPembayaran($kode, Request $request)
{
    $pesananList = $request->session()->get('pesanan_list', []);

    foreach ($pesananList as &$pesanan) {
        if ($pesanan['kode'] === $kode) {
            $pesanan['status'] = 'Ditolak';
        }
    }

    $request->session()->put('pesanan_list', $pesananList);

    return redirect()
        ->route('admin.verifikasi')
        ->with('success', 'Pembayaran ditolak.');
}
}