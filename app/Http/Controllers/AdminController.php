<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class AdminController extends Controller
{
    // Daftar pembayaran yang menunggu verifikasi
    public function verifikasi(Request $request)
    {
        $pending = collect(
            $request->session()->get('pesanan_list', [])
        )
        ->where('status', 'Menunggu Verifikasi')
        ->values();

        return view('admin.verifikasi.index', compact('pending'));
    }

    // Detail verifikasi
    public function detailVerifikasi(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan) {
            return redirect()
                ->route('admin.verifikasi')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('admin.verifikasi.detail', compact('pesanan'));
    }

    public function pesanan()
{
    return view('admin.pesanan.index');
}

    // Terima pembayaran
    public function terimaPembayaran(Request $request, string $kode)
    {
        $this->ubahStatus(
            $request,
            $kode,
            'Diproses'
        );

        return redirect()
            ->route('admin.verifikasi')
            ->with('status', 'Pembayaran berhasil diterima.');
    }

    // Tolak pembayaran
    public function tolakPembayaran(Request $request, string $kode)
    {
        $this->ubahStatus(
            $request,
            $kode,
            'Ditolak'
        );

        return redirect()
            ->route('admin.verifikasi')
            ->with('status', 'Pembayaran ditolak.');
    }

    // Cari pesanan
    private function cariPesanan(
        Request $request,
        string $kode
    ): ?array {
        $list = $request->session()->get('pesanan_list', []);

        foreach ($list as $item) {
            if (
                ltrim($item['kode'], '#')
                ===
                ltrim($kode, '#')
            ) {
                return $item;
            }
        }

        return null;
    }

    public function layanan()
{
    $layanan = Layanan::all();

    return view('admin.layanan.index', compact('layanan'));
}

public function createLayanan()
{
    return view('admin.layanan.form', [
        'mode' => 'create',
        'layanan' => null,
    ]);
}

public function storeLayanan(Request $request)
{
    $data = $request->validate([
        'nama_layanan' => 'required',
        'harga' => 'required|integer',
        'estimasi' => 'required',
        'status' => 'required|in:aktif,nonaktif',
        'deskripsi' => 'nullable',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('gambar')) {
        $data['gambar'] = $request->file('gambar')
            ->store('layanan', 'public');
    }

    Layanan::create($data);

    return redirect()
        ->route('admin.layanan')
        ->with('status', 'Layanan baru berhasil ditambahkan.');
}

public function editLayanan(Layanan $layanan)
{
    return view('admin.layanan.form', [
        'mode' => 'edit',
        'layanan' => $layanan,
    ]);
}

public function updateLayanan(Request $request, Layanan $layanan)
{
    $data = $request->validate([
        'nama_layanan' => 'required',
        'harga' => 'required|integer',
        'estimasi' => 'required',
        'status' => 'required|in:aktif,nonaktif',
        'deskripsi' => 'nullable',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('gambar')) {
        $data['gambar'] = $request->file('gambar')
            ->store('layanan', 'public');
    }

    $layanan->update($data);

    return redirect()
        ->route('admin.layanan')
        ->with('status', 'Layanan berhasil diperbarui.');
}

public function destroyLayanan(Layanan $layanan)
{
    $layanan->delete();

    return redirect()
        ->route('admin.layanan')
        ->with('status', 'Layanan berhasil dihapus.');
}

public function detailPesanan(Request $request, string $kode)
{
    $pesanan = $this->cariPesanan($request, $kode);

    if (! $pesanan) {
        return redirect()
            ->route('admin.pesanan')
            ->with('error', 'Pesanan tidak ditemukan.');
    }

    return view('admin.pesanan.detail', compact('pesanan'));
}
    // Ubah status pesanan
    private function ubahStatus(
        Request $request,
        string $kode,
        string $status,
        array $tambahan = []
    ): void {
        $list = $request->session()->get('pesanan_list', []);

        foreach ($list as &$item) {
            if (
                ltrim($item['kode'], '#')
                ===
                ltrim($kode, '#')
            ) {
                $item['status'] = $status;

                foreach ($tambahan as $key => $value) {
                    $item[$key] = $value;
                }

                break;
            }
        }

        

        $request->session()->put(
            'pesanan_list',
            $list
        );
    }
}