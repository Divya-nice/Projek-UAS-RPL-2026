<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pesanan;

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

    public function verifikasi()
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

    $pending = Pesanan::with(['user', 'layanan'])
        ->where('status_pembayaran', 'menunggu_verifikasi')
        ->get();

    return view('admin.verifikasi.index', compact(
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


    public function pesanan()
{
    $pesanan = Pesanan::with(['user', 'layanan'])
        ->where('status_pembayaran', 'diterima')
        ->get();

    $jumlahSemua = $pesanan->count();

    $jumlahDiproses = $pesanan
        ->where('status', 'Diproses')
        ->count();

    $jumlahDicuci = $pesanan
        ->where('status', 'Dicuci')
        ->count();

    $jumlahDikeringkan = $pesanan
        ->where('status', 'Dikeringkan')
        ->count();

    $jumlahSiapDiambil = $pesanan
        ->where('status', 'Siap Diambil')
        ->count();

    $jumlahSelesai = $pesanan
        ->where('status', 'Selesai')
        ->count();

    return view('admin.pesanan.index', compact(
        'pesanan',
        'jumlahSemua',
        'jumlahDiproses',
        'jumlahDicuci',
        'jumlahDikeringkan',
        'jumlahSiapDiambil',
        'jumlahSelesai'
    ));
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
            'status' => ['required', 'in:Menunggu Pembayaran,Menunggu Verifikasi,Diproses,Dicuci,Dikeringkan,Siap Diambil,Selesai,Dibatalkan,Ditolak'],
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

            'nama_layanan'=>'required|string|max:150',
            'harga'=>'required|integer|min:0',
            'estimasi'=>'required|string|max:50',
            'status'=>'required|in:aktif,nonaktif',
            'deskripsi'=>'nullable|string',
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

            'nama_layanan'=>'required|string|max:150',
            'harga'=>'required|integer|min:0',
            'estimasi'=>'required|string|max:50',
            'status'=>'required|in:aktif,nonaktif',
            'deskripsi'=>'nullable|string',
            'gambar'=>'nullable|image|max:2048'

        ]);



        if($request->hasFile('gambar')){

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