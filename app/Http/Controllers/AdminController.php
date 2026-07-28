<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

public function dashboard()
{
    $pesanan = Pesanan::with(['user', 'layanan'])
        ->where('status_pembayaran', 'menunggu_verifikasi')
        ->latest()
        ->get();

    // Pendapatan hari ini
    $pendapatanHariIni = Pesanan::whereDate('created_at', today())
        ->where('status_pembayaran', 'diterima')
        ->sum('total_biaya');

    // Jumlah pesanan hari ini
    $pesananHariIni = Pesanan::whereDate('created_at', today())
        ->count();

    // Pesanan yang siap diambil
    $pesananSiapDiambil = Pesanan::where('status', 'Siap Diambil')
        ->count();

    // Total pendapatan keseluruhan
    $totalPendapatan = Pesanan::where('status_pembayaran', 'diterima')
        ->sum('total_biaya');

    return view('admin.dashboard', compact(
        'pesanan',
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

    $menungguVerifikasi = Pesanan::whereIn(
        'status_pembayaran',
        [
            'menunggu_upload',
            'menunggu_verifikasi'
        ]
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
        ->whereIn('status_pembayaran',[
            'menunggu_upload',
            'menunggu_verifikasi'
        ]);

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

    return view('admin.verifikasi.index',compact(
        'pending',
        'semua',
        'menungguVerifikasi',
        'diterima',
        'ditolak'
    ));
}

    public function detailVerifikasi($kode)
    {
        $pesanan = Pesanan::with(['user', 'layanan'])
            ->where('nomor_pesanan', $kode)
            ->firstOrFail();

        return view('admin.verifikasi.detail', compact('pesanan'));
    }


    public function terimaPembayaran($kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->firstOrFail();


        $pesanan->update([
            'status_pembayaran' => 'diterima',
            'status' => 'Diproses'
        ]);


        return redirect()
            ->route('admin.verifikasi')
            ->with('status','Pembayaran berhasil diterima.');
    }



    public function tolakPembayaran($kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->firstOrFail();


        $pesanan->update([
            'status_pembayaran' => 'ditolak',
            'status' => 'Ditolak'
        ]);


        return redirect()
            ->route('admin.verifikasi')
            ->with('status','Pembayaran ditolak.');
    }



    /*
    |--------------------------------------------------------------------------
    | KELOLA PESANAN
    |--------------------------------------------------------------------------
    */
public function pesanan(Request $request)
{
    $query = Pesanan::with(['user', 'layanan'])
        ->whereIn('status', [
        'Diproses',
        'Dicuci',
        'Dikeringkan',
        'Siap Diambil',
        'Selesai',
    ]);

    // =========================
    // FILTER PENCARIAN
    // =========================
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nomor_pesanan', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('layanan', function ($l) use ($search) {
                  $l->where('nama_layanan', 'like', "%{$search}%");
              });
        });
    }

    // =========================
    // FILTER STATUS
    // =========================
    if ($request->filled('status') && $request->status != 'Semua status') {
        $query->where('status', $request->status);
    }

    // =========================
    // FILTER LAYANAN
    // =========================
    if ($request->filled('layanan') && $request->layanan != 'Semua Layanan') {
        $query->where('layanan_id', $request->layanan);
    }

    // =========================
    // FILTER TANGGAL
    // =========================
    if ($request->filled('tanggal')) {

        switch ($request->tanggal) {

            case 'hari_ini':
                $query->whereDate('created_at', today());
                break;

            case 'minggu_ini':
                $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;

            case 'bulan_ini':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
        }
    }

    // =========================
    // FILTER TAB STATUS
    // =========================
    if ($request->filled('tab') && $request->tab != 'Semua') {
        $query->where('status', $request->tab);
    }

    $pesanan = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('admin.pesanan.index', [
        'pesanan' => $pesanan,

        'layananList' => Layanan::all(),

        'jumlahSemua' => Pesanan::whereIn('status', [
            'Diproses',
            'Dicuci',
            'Dikeringkan',
            'Siap Diambil',
            'Selesai',
        ])->count(),

        'jumlahDiproses' => Pesanan::where('status', 'Diproses')->count(),
        'jumlahDicuci' => Pesanan::where('status', 'Dicuci')->count(),
        'jumlahDikeringkan' => Pesanan::where('status', 'Dikeringkan')->count(),
        'jumlahSiapDiambil' => Pesanan::where('status', 'Siap Diambil')->count(),
        'jumlahSelesai' => Pesanan::where('status', 'Selesai')->count(),
    ]);
}

    public function detailPesanan($kode)
    {
        $pesanan = Pesanan::with(['user','layanan'])
            ->where('nomor_pesanan',$kode)
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
            'status'=>'required|in:aktif,nonaktif'
        ]);


        $pesanan = Pesanan::where(
            'nomor_pesanan',
            $kode
        )->firstOrFail();



        $pesanan->update([
            'status'=>$request->status
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
        $layanan = Layanan::all();

        return view(
            'admin.layanan.index',
            compact('layanan')
        );
    }



    public function createLayanan()
    {
        return view('admin.layanan.form',[
            'mode'=>'create',
            'layanan'=>null
        ]);
    }



    public function storeLayanan(Request $request)
    {

        $data=$request->validate([

            'nama_layanan'=>'required',
            'harga'=>'required|integer',
            'estimasi'=>'required',
            'status'=>'required|in:aktif,nonaktif',
            'deskripsi'=>'nullable',
            'gambar'=>'nullable|image|max:2048'

        ]);



        if($request->hasFile('gambar')){

            $data['gambar']=$request
                ->file('gambar')
                ->store('layanan','public');

        }



        Layanan::create($data);



        return redirect()
            ->route('admin.layanan')
            ->with(
                'status',
                'Layanan berhasil ditambahkan.'
            );

    }



    public function editLayanan(Layanan $layanan)
    {
        return view(
            'admin.layanan.form',
            [
                'mode'=>'edit',
                'layanan'=>$layanan
            ]
        );
    }



    public function updateLayanan(Request $request,Layanan $layanan)
    {

        $data=$request->validate([

            'nama_layanan'=>'required',
            'harga'=>'required|integer',
            'estimasi'=>'required',
            'status'=>'required',
            'deskripsi'=>'nullable',
            'gambar'=>'nullable|image|max:2048'

        ]);



        if($request->hasFile('gambar')){

            if ($layanan->gambar) {
                Storage::disk('public')->delete($layanan->gambar);
            }

            $data['gambar']=$request
                ->file('gambar')
                ->store('layanan','public');

        }



        $layanan->update($data);



        return redirect()
            ->route('admin.layanan')
            ->with(
                'status',
                'Layanan berhasil diperbarui.'
            );

    }



    public function destroyLayanan(Layanan $layanan)
    {
        if ($layanan->gambar) {
            Storage::disk('public')->delete($layanan->gambar);
        }

        $layanan->delete();


        return redirect()
            ->route('admin.layanan')
            ->with(
                'status',
                'Layanan berhasil dihapus.'
            );

    }




    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENDAPATAN
    |--------------------------------------------------------------------------
    */


    public function laporan()
{
    $transaksi = Pesanan::with(['user', 'layanan'])
        ->where('status', 'Selesai')
        ->latest()
        ->get();

    $totalPendapatan = $transaksi->sum('total_biaya');

    $totalTransaksi = $transaksi->count();

    $rataRataTransaksi = $totalTransaksi > 0
        ? $totalPendapatan / $totalTransaksi
        : 0;

    $totalLayananTerjual = $totalTransaksi;

    $layananTerlaris = $transaksi
        ->groupBy('layanan_id')
        ->sortByDesc(function ($items) {
            return $items->count();
        })
        ->first();

    $namaLayananTerlaris = $layananTerlaris?->first()?->layanan?->nama_layanan ?? '-';

    $jumlahLayananTerlaris = $layananTerlaris?->count() ?? 0;

    $persentaseLayananTerlaris = $totalTransaksi > 0
        ? round(($jumlahLayananTerlaris / $totalTransaksi) * 100)
        : 0;

    return view('admin.laporan.index', compact(
        'transaksi',
        'totalPendapatan',
        'totalTransaksi',
        'rataRataTransaksi',
        'totalLayananTerjual',
        'namaLayananTerlaris',
        'jumlahLayananTerlaris',
        'persentaseLayananTerlaris'
    ));
}}