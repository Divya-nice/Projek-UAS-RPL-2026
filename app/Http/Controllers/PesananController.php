<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Layanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Format harga ke Rupiah
     */
    public static function rupiah($nilai)
    {
        return 'Rp' . number_format($nilai, 0, ',', '.');
    }

    /**
     * Halaman utama pelanggan
     */
    public function beranda()
    {
        $layanan = Layanan::all();

        $populer = $layanan->take(4);

        return view('pesanan.beranda', [
            'populer' => $populer,

            'keunggulan' => [
                [
                    'icon' => 'check-circle',
                    'judul' => 'Pembersihan Profesional',
                    'teks' => 'Menggunakan metode pembersihan yang aman untuk berbagai jenis sepatu.'
                ],
                [
                    'icon' => 'shield',
                    'judul' => 'Terpercaya',
                    'teks' => 'Sepatu dirawat dengan tenaga yang berpengalaman.'
                ],
                [
                    'icon' => 'clock',
                    'judul' => 'Pengerjaan Cepat',
                    'teks' => 'Estimasi pengerjaan sesuai jenis layanan.'
                ]
            ],

            'syarat' => [
                'Sepatu harus dalam kondisi yang dapat diproses.',
                'Harga mengikuti jenis layanan yang dipilih.',
                'Pesanan diproses setelah pembayaran dikonfirmasi.'
            ],

            'kontak' => [
                'alamat' => 'Pontianak, Kalimantan Barat',
                'whatsapp' => '081234567890',
                'operasional' => '08.00 - 17.00 WIB',
                'sosial' => '@cucisepatu'
            ]
        ]);
    }

    /**
     * Katalog layanan
     */
    public function katalog()
    {
        $layanan = Layanan::all();

        return view('pesanan.katalog', [
            'layanan' => $layanan
        ]);
    }

    /**
     * Form pemesanan
     */
    public function create()
{
    $layanan = Layanan::all();

    $ongkos = [
        'Pontianak Kota' => 5000,
        'Pontianak Selatan' => 7000,
        'Pontianak Timur' => 8000,
        'Pontianak Barat' => 6000,
        'Pontianak Utara' => 9000,
    ];

    return view('pesanan.form', [
        'layanan' => $layanan,
        'ongkos' => $ongkos,
    ]);
}

    /**
     * Simpan pesanan
     */
    public function store(Request $request)
{
    $request->validate([
        'layanan_id' => 'required|exists:layanans,id',
        'nama' => 'required|string|max:100',
        'telepon' => 'required|string|max:20',
        'alamat' => 'required|string',
        'jumlah' => 'required|integer|min:1',
        'ukuran' => 'required|array',
        'metode' => 'required|in:antar,jemput',
        'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $foto = null;

    if ($request->hasFile('foto')) {
        $foto = $request->file('foto')[0]->store('foto-sepatu', 'public');
    }

    $layanan = Layanan::find($request->layanan_id);
    $totalBiaya = $layanan->harga * $request->jumlah;

$pesanan = Pesanan::create([
    'user_id' => auth()->id(),
    'layanan_id' => $request->layanan_id,
    'nomor_pesanan' => 'PSN-' . time(),
    'nama' => $request->nama,
    'nomor_hp' => $request->telepon,
    'alamat' => $request->alamat,
    'wilayah' => $request->kecamatan,
    'jumlah_sepatu' => $request->jumlah,
    'ukuran_sepatu' => implode(',', $request->ukuran),
    'foto_sepatu' => $foto,
    'metode_pengantaran' => $request->metode,
    'pin_lokasi' => $request->alamat_jemput,
    'total_biaya' => $totalBiaya,
    'status' => 'Menunggu Verifikasi Admin',
]);


    return redirect()
        ->route('pesanan.show', $pesanan->id)
        ->with('success', 'Pesanan berhasil dibuat.');
}

    /**
     * Detail pesanan
     */
    public function show($id)
    {
        if (auth()->check()) {
            $pesanan = Pesanan::with('layanan')
                ->where('user_id', auth()->id())
                ->findOrFail($id);
        } else {
            $pesanan = Pesanan::with('layanan')
                ->findOrFail($id);
        }

        return view('pesanan.show', [
            'pesanan' => $pesanan
        ]);
    }

    /**
     * Semua pesanan
     */
    public function index()
    {
        $pesanan = Pesanan::with('layanan')
            ->latest()
            ->get();

        return view('pesanan.index', [
            'pesanan' => $pesanan
        ]);
    }

    /**
     * Halaman 4 - Upload Bukti Pembayaran (khusus Transfer Bank).
     */
    public function pembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan_sukses');

        if (! $pesanan || ($pesanan['metode_bayar'] ?? null) !== 'transfer') {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.pembayaran', [
            'pesanan'  => $pesanan,
            'rekening' => config('layanan.kontak.rekening', []),
        ]);
    }

    /**
     * Proses upload bukti pembayaran lalu lanjut ke halaman Berhasil.
     */
    public function prosesPembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan_sukses');

        if (! $pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'bukti.required' => 'Silakan unggah bukti pembayaran terlebih dahulu.',
            'bukti.mimes'    => 'Format berkas harus JPG, PNG, atau PDF.',
            'bukti.max'      => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $pesanan['bukti_pembayaran'] = $request->file('bukti')->store('bukti-pembayaran', 'public');
        $request->session()->put('pesanan_sukses', $pesanan);

        return redirect()->route('pesanan.berhasil')
            ->with('sukses', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Halaman 5 - Pesanan Berhasil.
     */
    public function berhasil(Request $request)
    {
        $pesanan = $request->session()->get('pesanan_sukses');

        if (! $pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Riwayat pelanggan
     */
    public function riwayat()
    {
        $riwayat = Pesanan::with('layanan')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('pesanan.riwayat', [
            'riwayat' => $riwayat
        ]);
    }

    /**
     * Akun pelanggan
     */
    public function akun()
    {
        $user = [
            'nama' => auth()->user()->name ?? 'Pelanggan',
            'email' => auth()->user()->email ?? '-',
            'telepon' => '-',
            'alamat' => '-'
        ];

        return view('pesanan.akun', [
            'user' => $user
        ]);
    }
}