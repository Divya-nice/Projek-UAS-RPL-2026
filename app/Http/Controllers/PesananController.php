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

        return view('pesanan.form', [
            'layanan' => $layanan
        ]);
    }

    /**
     * Simpan pesanan
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'layanan_id' => [
                'required',
                'exists:layanans,id'
            ],

            'nama' => [
                'required',
                'string',
                'max:100'
            ],

            'nomor_hp' => [
                'required',
                'string',
                'max:20'
            ],

            'alamat' => [
                'required',
                'string'
            ],

            'jumlah_sepatu' => [
                'required',
                'integer',
                'min:1'
            ],

            'ukuran_sepatu' => [
                'required',
                'string'
            ],

            'foto_sepatu' => [
                'nullable',
                'image',
                'max:2048'
            ],

            'metode_pengantaran' => [
                'required',
                'in:antar,jemput'
            ],

            'pin_lokasi' => [
                'required_if:metode_pengantaran,jemput',
                'nullable',
                'string'
            ],

        ]);

        if ($request->hasFile('foto_sepatu')) {
            $data['foto_sepatu'] = $request
                ->file('foto_sepatu')
                ->store('foto-sepatu', 'public');
        }

        $data['status'] = 'Menunggu Pembayaran';

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        $pesanan = Pesanan::create($data);

        return redirect()
            ->route('pesanan.show', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat');
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