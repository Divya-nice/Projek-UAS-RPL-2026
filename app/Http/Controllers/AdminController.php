<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Jumlah pembayaran yang menunggu verifikasi (dipakai untuk kartu
        // pengingat), dihitung terpisah dari daftar yang ditampilkan di
        // tabel supaya tabel bisa dibatasi (mis. 5 terbaru) tanpa membuat
        // angka pengingat ikut terpotong.
        $pesananMenungguVerifikasi = Pesanan::where(
            'status_pembayaran',
            'menunggu_verifikasi'
        )->count();

        $pesanan = Pesanan::with(['user','layanan'])
            ->where('status_pembayaran','menunggu_verifikasi')
            ->latest()
            ->take(5)
            ->get();

        $pendapatanHariIni = Pesanan::whereDate('created_at', today())
            ->where('status_pembayaran','diterima')
            ->sum('total_biaya');

        $pesananHariIni = Pesanan::whereDate('created_at', today())
            ->count();

        $pesananSiapDiambil = Pesanan::where('status','Siap Diambil')
            ->count();

        $totalPendapatan = Pesanan::where('status_pembayaran','diterima')
            ->sum('total_biaya');

        return view('admin.dashboard', compact(
            'pesanan',
            'pesananMenungguVerifikasi',
            'pendapatanHariIni',
            'pesananHariIni',
            'pesananSiapDiambil',
            'totalPendapatan'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function verifikasi(Request $request)
    {
        $semua = Pesanan::count();

        $menungguVerifikasi = Pesanan::where(
            'status_pembayaran',
            'menunggu_verifikasi'
        )->count();

        $diterima = Pesanan::where(
            'status_pembayaran',
            'diterima'
        )->count();

        $ditolak = Pesanan::where(
            'status_pembayaran',
            'ditolak'
        )->count();

        $query = Pesanan::with(['user','layanan'])
            ->where('status_pembayaran','menunggu_verifikasi');


        if($request->filled('search')){

            $search = $request->search;

            $query->where(function($q) use($search){

                $q->where('nomor_pesanan','like',"%{$search}%")
                  ->orWhere('nama','like',"%{$search}%")
                  ->orWhere('nomor_hp','like',"%{$search}%");

            });
        }

        $pending = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $ditolakList = Pesanan::with(['user','layanan'])
            ->where('status_pembayaran','ditolak')
            ->latest()
            ->paginate(10, ['*'], 'ditolak_page')
            ->withQueryString();


        return view('admin.verifikasi.index',compact(
            'pending',
            'ditolakList',
            'semua',
            'menungguVerifikasi',
            'diterima',
            'ditolak'
        ));
    }

    public function detailVerifikasi($kode)
    {
        $pesanan = Pesanan::with(['user','layanan'])
            ->where('nomor_pesanan',$kode)
            ->firstOrFail();


        return view(
            'admin.verifikasi.detail',
            compact('pesanan')
        );
    }

    public function terimaPembayaran($kode)
    {
        $pesanan = Pesanan::where(
            'nomor_pesanan',
            $kode
        )->firstOrFail();

        if ($pesanan->status_pembayaran !== 'menunggu_verifikasi') {
            return redirect()
                ->route('admin.verifikasi')
                ->with(
                    'status',
                    'Pesanan ini sudah diproses sebelumnya.'
                );
        }

        $pesanan->update([
            'status_pembayaran' => 'diterima',
            'status' => 'Diproses',
        ]);

        return redirect()
            ->route('admin.verifikasi')
            ->with(
                'status',
                'Pembayaran berhasil diterima dan pesanan mulai diproses.'
            );
    }

    public function tolakPembayaran($kode)
    {
        $pesanan = Pesanan::where(
            'nomor_pesanan',
            $kode
        )->firstOrFail();

        if ($pesanan->status_pembayaran !== 'menunggu_verifikasi') {
            return redirect()
                ->route('admin.verifikasi')
                ->with(
                    'status',
                    'Pesanan ini sudah diproses sebelumnya.'
                );
        }

        $pesanan->update([
            'status_pembayaran' => 'ditolak',
            'status' => 'Dibatalkan',
        ]);


        return redirect()
            ->route('admin.verifikasi')
            ->with(
                'status',
                'Pembayaran ditolak.'
            );
    }

        /*
    |--------------------------------------------------------------------------
    | KELOLA PESANAN
    |--------------------------------------------------------------------------
    */

    public function pesanan(Request $request)
    {
        $query = Pesanan::with(['user','layanan'])
            ->whereIn('status', [
                'Diproses',
                'Dicuci',
                'Dikeringkan',
                'Siap Diambil',
                'Selesai',
            ]);

        if($request->filled('search')){

            $search = $request->search;

            $query->where(function($q) use($search){

                $q->where('nomor_pesanan','like',"%{$search}%")
                    ->orWhere('nama','like',"%{$search}%")
                    ->orWhereHas('layanan',function($l) use($search){

                        $l->where(
                            'nama_layanan',
                            'like',
                            "%{$search}%"
                        );

                    });

            });

        }

        if($request->filled('status') &&
            $request->status != 'Semua status'){

            $query->where(
                'status',
                $request->status
            );

        }

        if($request->filled('layanan') &&
            $request->layanan != 'Semua Layanan'){

            $query->where(
                'layanan_id',
                $request->layanan
            );

        }

        if($request->filled('tanggal')){

            if($request->tanggal == 'hari_ini'){

                $query->whereDate('created_at', today());

            } elseif($request->tanggal == 'minggu_ini'){

                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);

            } elseif($request->tanggal == 'bulan_ini'){

                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);

            }

        }

        $pesanan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pesanan.index',[

            'pesanan' => $pesanan,

            'layananList' => Layanan::all(),

            'jumlahSemua' => Pesanan::whereIn('status',[
                'Diproses',
                'Dicuci',
                'Dikeringkan',
                'Siap Diambil',
                'Selesai'
            ])->count(),

            'jumlahDiproses' =>
                Pesanan::where('status','Diproses')->count(),

            'jumlahDicuci' =>
                Pesanan::where('status','Dicuci')->count(),

            'jumlahDikeringkan' =>
                Pesanan::where('status','Dikeringkan')->count(),

            'jumlahSiapDiambil' =>
                Pesanan::where('status','Siap Diambil')->count(),

            'jumlahSelesai' =>
                Pesanan::where('status','Selesai')->count(),

        ]);
    }

    public function detailPesanan($kode)
    {
        $pesanan = Pesanan::with([
            'user',
            'layanan'
        ])
        ->where(
            'nomor_pesanan',
            $kode
        )
        ->firstOrFail();

        return view(
            'admin.pesanan.detail',
            compact('pesanan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS PESANAN
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request,$kode)
    {
        $request->validate([
            'status' => [
                'required',
                'in:Diproses,Dicuci,Dikeringkan,Siap Diambil,Selesai'
            ],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $pesanan = Pesanan::where(
            'nomor_pesanan',
            $kode
        )
        ->firstOrFail();

        $pesanan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()
            ->with(
                'status',
                'Status pesanan berhasil diperbarui.'
            );
    }

    /*
|--------------------------------------------------------------------------
| LAYANAN
|--------------------------------------------------------------------------
*/

public function layanan()
{
    $layanan = Layanan::latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.layanan.index', compact('layanan'));
}


public function createLayanan()
{
    return view('admin.layanan.form', [
        'mode' => 'create',
        'layanan' => null
    ]);
}

public function storeLayanan(Request $request)
{
    $data = $request->validate([
        'nama_layanan' => 'required|string|max:100',
        'harga' => 'required|integer|min:0',
        'estimasi' => 'required|string|max:100',
        'status' => 'required|in:aktif,nonaktif',
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('gambar')) {
        $data['gambar'] = $request->file('gambar')
            ->store('layanan', 'public');
    }

    Layanan::create($data);

    return redirect()
        ->route('admin.layanan')
        ->with('status', 'Layanan berhasil ditambahkan.');
}

public function editLayanan(Layanan $layanan)
{
    return view('admin.layanan.form', [
        'mode' => 'edit',
        'layanan' => $layanan
    ]);
}

public function updateLayanan(Request $request, Layanan $layanan)
{
    $data = $request->validate([
        'nama_layanan' => 'required|string|max:100',
        'harga' => 'required|integer|min:0',
        'estimasi' => 'required|string|max:100',
        'status' => 'required|in:aktif,nonaktif',
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('gambar')) {

        if ($layanan->gambar) {
            Storage::disk('public')->delete($layanan->gambar);
        }

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
    if ($layanan->gambar) {
        Storage::disk('public')->delete($layanan->gambar);
    }

    $layanan->delete();

    return redirect()
        ->route('admin.layanan')
        ->with('status', 'Layanan berhasil dihapus.');
}

/*
|--------------------------------------------------------------------------
| LAPORAN PENDAPATAN
|--------------------------------------------------------------------------
*/

public function laporan(Request $request)
{
    $query = Pesanan::with(['user','layanan'])
        ->where('status', 'Selesai');

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('nomor_pesanan', 'like', "%{$search}%")
              ->orWhere('nama', 'like', "%{$search}%");

        });
    }

    if ($request->filled('tanggal') && $request->tanggal != 'semua') {

        if ($request->tanggal == 'hari_ini') {

            $query->whereDate('created_at', today());

        } elseif ($request->tanggal == 'minggu_ini') {

            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);

        } elseif ($request->tanggal == 'bulan_ini') {

            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);

        }
    }

    // Ambil seluruh transaksi yang SUDAH difilter (tanpa dipaginasi)
    // untuk dasar perhitungan ringkasan/statistik, supaya kartu-kartu
    // ringkasan tetap benar walau tabel di bawah hanya menampilkan
    // satu halaman.
    $semuaTransaksi = (clone $query)->latest()->get();

    $totalPendapatan = $semuaTransaksi->sum('total_biaya');

    $totalTransaksi = $semuaTransaksi->count();

    $totalLayananTerjual = $semuaTransaksi->sum('jumlah_sepatu');

    $rataRataTransaksi = $totalTransaksi > 0
        ? $totalPendapatan / $totalTransaksi
        : 0;

    $layananTerlaris = $semuaTransaksi
        ->groupBy('layanan_id')
        ->sortByDesc(function ($item) {
            return $item->count();
        })
        ->first();

    $namaLayananTerlaris = $layananTerlaris?->first()?->layanan?->nama_layanan ?? '-';

    $jumlahLayananTerlaris = $layananTerlaris?->count() ?? 0;

    $persentaseLayananTerlaris = $totalTransaksi > 0
        ? round(($jumlahLayananTerlaris / $totalTransaksi) * 100)
        : 0;

    // Tabel "Daftar Transaksi Selesai" memakai pagination Laravel yang
    // sesungguhnya (paginate + links), bukan angka statis.
    $transaksi = $query->latest()
        ->paginate(10)
        ->withQueryString();

    // --- Data grafik pendapatan, diambil langsung dari database ---
    // Catatan: grafik memakai seluruh transaksi "Selesai" (tidak
    // terpengaruh filter tabel di atas), supaya tren harian/mingguan/
    // bulanan tetap konsisten walau pengguna sedang mencari/memfilter
    // daftar transaksi.

    // Harian: 9 titik terakhir (mengikuti jumlah titik pada tampilan).
    $chartHarian = collect(range(8, 0))->map(function ($i) {

        $tanggal = now()->subDays($i)->startOfDay();

        $total = Pesanan::where('status', 'Selesai')
            ->whereDate('created_at', $tanggal)
            ->sum('total_biaya');

        return [
            'label' => strtoupper($tanggal->translatedFormat('d M')),
            'total' => (float) $total,
        ];
    });

    // Mingguan: 5 minggu terakhir.
    $chartMingguan = collect(range(4, 0))->map(function ($i) {

        $awal = now()->subWeeks($i)->startOfWeek();
        $akhir = now()->subWeeks($i)->endOfWeek();

        $total = Pesanan::where('status', 'Selesai')
            ->whereBetween('created_at', [$awal, $akhir])
            ->sum('total_biaya');

        return [
            'label' => 'MINGGU ' . (5 - $i),
            'total' => (float) $total,
        ];
    });

    // Bulanan: 5 bulan terakhir.
    $chartBulanan = collect(range(4, 0))->map(function ($i) {

        $bulan = now()->subMonths($i);

        $total = Pesanan::where('status', 'Selesai')
            ->whereMonth('created_at', $bulan->month)
            ->whereYear('created_at', $bulan->year)
            ->sum('total_biaya');

        return [
            'label' => strtoupper($bulan->translatedFormat('M')),
            'total' => (float) $total,
        ];
    });

    return view('admin.laporan.index', compact(
        'transaksi',
        'semuaTransaksi',
        'totalPendapatan',
        'totalTransaksi',
        'totalLayananTerjual',
        'rataRataTransaksi',
        'namaLayananTerlaris',
        'jumlahLayananTerlaris',
        'persentaseLayananTerlaris',
        'chartHarian',
        'chartMingguan',
        'chartBulanan'
    ));
}

}