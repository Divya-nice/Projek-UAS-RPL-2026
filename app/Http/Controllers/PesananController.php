<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pesanan;
use App\Models\Layanan;

class PesananController extends Controller
{
    private function layanan()
    {
        return Layanan::where('status', 'aktif')
            ->orderBy('nama_layanan')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    Str::slug($item->nama_layanan) => [
                        'id' => $item->id,
                        'nama' => $item->nama_layanan,
                        'harga' => $item->harga,
                        'estimasi' => $item->estimasi,
                        'deskripsi' => $item->deskripsi,
                        'gambar' => $item->gambar,
                    ]
                ];
            })
            ->toArray();
    }

    public static function rupiah($nilai): string
    {
        return 'Rp ' . number_format((float) ($nilai ?? 0), 0, ',', '.');
    }

    public function beranda()
    {
        $populer = array_slice($this->layanan(), 0, 4, true);

        return view('pesanan.beranda', [
            'populer' => $populer,
            'keunggulan' => config('layanan.keunggulan', []),
            'syarat' => config('layanan.syarat', []),
            'kontak' => config('layanan.kontak', []),
        ]);
    }

    public function katalog()
    {
        return view('pesanan.katalog', [
            'layanan' => $this->layanan(),
        ]);
    }

    public function form(Request $request)
    {
        $layanan = $this->layanan();
        $slug = $request->query('layanan');

        if (!$slug || !isset($layanan[$slug])) {
            $slug = array_key_first($layanan);
        }

        return view('pesanan.form', [
            'layanan' => $layanan,
            'slugTerpilih' => $slug,
            'ongkos' => config('layanan.ongkos_jemput', []),
        ]);
    }

    public function prosesForm(Request $request)
    {
        $layanan = $this->layanan();

        $data = $request->validate([
            'layanan' => ['required','string','in:' . implode(',', array_keys($layanan))],
            'nama' => ['required','string','max:100'],
            'telepon' => ['required','string','max:20'],
            'email' => ['nullable','email','max:100'],
            'alamat' => ['required','string','max:255'],
            'jumlah' => ['required','integer','min:1','max:20'],
            'ukuran' => ['required','array'],
            'ukuran.*' => ['nullable','string','max:10'],
            'foto' => ['nullable','image','mimes:jpg,jpeg,png','max:5120'],
        ]);

        $item = $layanan[$data['layanan']];

        $pesanan = [
            'layanan_id' => $item['id'],
            'layanan_nama' => $item['nama'],
            'estimasi' => $item['estimasi'],
            'nama' => $data['nama'],
            'telepon' => $data['telepon'],
            'email' => $data['email'] ?? null,
            'alamat' => $data['alamat'],
            'jumlah' => $data['jumlah'],
            'ukuran' => array_values(array_filter($data['ukuran'])),
            'subtotal' => $item['harga'] * $data['jumlah'],
        ];

        if ($request->hasFile('foto')) {
            $pesanan['foto_sepatu'] = $request->file('foto')
                ->store('foto-sepatu', 'public');
        }

        $request->session()->put('pesanan', $pesanan);

        return redirect()->route('pesanan.pengantaran');
    }

        public function pengantaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (!$pesanan) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan isi data pesanan terlebih dahulu.');
        }

        return view('pesanan.pengantaran', [
            'pesanan' => $pesanan,
            'ongkos' => config('layanan.ongkos_jemput', []),
        ]);
    }

    public function prosesPengantaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (!$pesanan) {
            return redirect()->route('pesanan.form');
        }

        $ongkos = config('layanan.ongkos_jemput', []);

        $data = $request->validate([
            'metode' => ['required','in:antar,jemput'],
            'kecamatan' => ['nullable','string'],
            'alamat_jemput' => ['nullable','string','max:255'],
        ]);

        $ongkosJemput = 0;

        if ($data['metode'] === 'jemput') {
            $request->validate([
                'kecamatan' => ['required','in:' . implode(',', array_keys($ongkos))],
                'alamat_jemput' => ['required','string','max:255'],
            ]);

            $ongkosJemput = $ongkos[$data['kecamatan']] ?? 0;
        }

        $pesanan['metode'] = $data['metode'];
        $pesanan['kecamatan'] = $data['kecamatan'] ?? null;
        $pesanan['alamat_jemput'] = $data['alamat_jemput'] ?? null;
        $pesanan['ongkos_jemput'] = $ongkosJemput;
        $pesanan['total'] = $pesanan['subtotal'] + $ongkosJemput;

        $request->session()->put('pesanan', $pesanan);

        return redirect()->route('pesanan.ringkasan');
    }


    public function ringkasan(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (!$pesanan) {
            return redirect()->route('pesanan.form');
        }

        return view('pesanan.ringkasan', compact('pesanan'));
    }


    public function pembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (!$pesanan) {
            return redirect()->route('pesanan.form');
        }

        return view('pesanan.pembayaran', [
            'pesanan' => $pesanan,
            'rekening' => config('layanan.kontak.rekening', []),
        ]);
    }


    public function prosesPembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (!$pesanan) {
            return redirect()->route('pesanan.form');
        }

        $data = $request->validate([
            'metode_bayar' => ['required','in:transfer,cod'],
        ]);

        $kode = 'PTK' . now()->format('ymd') . strtoupper(Str::random(3));

        $statusPembayaran = $data['metode_bayar'] === 'cod'
            ? 'menunggu_verifikasi'
            : 'menunggu_upload';

        $status = 'Menunggu Pembayaran';

        Pesanan::create([
            'user_id' => Auth::id(),
            'layanan_id' => $pesanan['layanan_id'],
            'nomor_pesanan' => $kode,

            'nama' => $pesanan['nama'],
            'nomor_hp' => $pesanan['telepon'],
            'alamat' => $pesanan['alamat'],
            'wilayah' => $pesanan['kecamatan'] ?? null,

            'jumlah_sepatu' => $pesanan['jumlah'],
            'ukuran_sepatu' => implode(', ', $pesanan['ukuran']),

            'foto_sepatu' => $pesanan['foto_sepatu'] ?? null,

            'metode_pengantaran' => $pesanan['metode'],

            'pin_lokasi' => $pesanan['metode'] === 'jemput'
                ? ($pesanan['alamat_jemput'] ?? null)
                : null,

            'ongkos_jemput' => $pesanan['ongkos_jemput'] ?? 0,

            'total_biaya' => $pesanan['total'],

            'status' => $status,

            'status_pembayaran' => $statusPembayaran,

            'metode_bayar' => $data['metode_bayar'],
        ]);

        $request->session()->put('pesanan_sukses', $kode);
        $request->session()->forget('pesanan');

        return redirect()->route('pesanan.berhasil');
    }


    public function berhasil(Request $request)
    {
        $kode = $request->session()->get('pesanan_sukses');

        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.berhasil', compact('pesanan'));
    }

        public function bayar(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pesanan->metode_bayar !== 'transfer'
            || $pesanan->status_pembayaran !== 'menunggu_upload') {

            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan ini tidak memerlukan upload bukti pembayaran.');
        }

        return view('pesanan.bayar', [
            'pesanan' => $pesanan,
            'rekening' => config('layanan.kontak.rekening', []),
        ]);
    }


    public function bukti(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pesanan->metode_bayar !== 'transfer'
            || $pesanan->status_pembayaran !== 'menunggu_upload') {

            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan ini tidak memerlukan upload bukti pembayaran.');
        }

        return view('pesanan.bukti', compact('pesanan'));
    }


    public function prosesBukti(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pesanan->metode_bayar !== 'transfer'
            || $pesanan->status_pembayaran !== 'menunggu_upload') {

            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan ini tidak memerlukan upload bukti pembayaran.');
        }

        $request->validate([
            'bukti' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],
        ]);

        if ($pesanan->bukti_pembayaran) {
            Storage::disk('public')
                ->delete($pesanan->bukti_pembayaran);
        }

        $path = $request->file('bukti')
            ->store('bukti-pembayaran', 'public');

        $pesanan->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
            'status' => 'Menunggu Verifikasi',
        ]);

        $request->session()
            ->put('pesanan_sukses', $pesanan->nomor_pesanan);

        return redirect()
            ->route('pesanan.bukti.berhasil');
    }


    public function buktiBerhasil(Request $request)
    {
        $kode = $request->session()
            ->get('pesanan_sukses');

        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        return view('pesanan.bukti-berhasil', compact('pesanan'));
    }


    public function batalkan(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (in_array($pesanan->status, [
            'Diproses',
            'Dicuci',
            'Dikeringkan',
            'Siap Diambil',
            'Selesai',
            'Dibatalkan'
        ])) {
            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan tidak dapat dibatalkan.');
        }

        $pesanan->update([
            'status' => 'Dibatalkan',
        ]);

        return redirect()
            ->route('pesanan.riwayat')
            ->with('sukses', 'Pesanan berhasil dibatalkan.');
    }


    public function riwayat(Request $request)
    {
        $query = Pesanan::with('layanan')
            ->where('user_id', Auth::id());

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nomor_pesanan', 'like', "%{$search}%")
                  ->orWhereHas('layanan', function ($l) use ($search) {
                      $l->where('nama_layanan', 'like', "%{$search}%");
                  });

            });
        }

        $riwayat = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pesanan.riwayat', compact('riwayat'));
    }

        public function nota($kode)
    {
        $pesanan = Pesanan::with('layanan')
            ->where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $status = $pesanan->status;

        $statusLabel = match ($status) {
            'Menunggu Pembayaran' => 'Menunggu Pembayaran',
            'Menunggu Verifikasi' => 'Menunggu Verifikasi',
            'Diproses' => 'Sedang Diproses',
            'Dicuci' => 'Sedang Dicuci',
            'Dikeringkan' => 'Sedang Dikeringkan',
            'Siap Diambil' => 'Siap Diambil',
            'Selesai' => 'Selesai',
            'Dibatalkan' => 'Dibatalkan',
            default => $status,
        };

        $statusDesc = match ($status) {
            'Menunggu Pembayaran'
                => 'Silakan lakukan pembayaran atau tunggu pembayaran COD diterima.',

            'Menunggu Verifikasi'
                => 'Pembayaran sedang diperiksa oleh pemilik usaha.',

            'Diproses'
                => 'Pesanan sudah diterima dan sedang diproses.',

            'Dicuci'
                => 'Sepatu sedang dalam tahap pencucian.',

            'Dikeringkan'
                => 'Sepatu sedang dalam proses pengeringan.',

            'Siap Diambil'
                => 'Pesanan siap diambil atau dikirim.',

            'Selesai'
                => 'Pesanan sudah selesai.',

            'Dibatalkan'
                => 'Pesanan telah dibatalkan.',

            default => 'Status pesanan sedang diperbarui.',
        };


        $timeline = [
            [
                'label' => 'Pesanan Dibuat',
                'waktu' => $pesanan->created_at->format('d M Y H:i'),
                'state' => 'done',
            ],
            [
                'label' => 'Pembayaran Diterima',
                'waktu' => $pesanan->status_pembayaran === 'diterima'
                    ? $pesanan->updated_at->format('d M Y H:i')
                    : null,
                'state' => $pesanan->status_pembayaran === 'diterima'
                    ? 'done'
                    : 'pending',
            ],
            [
                'label' => 'Sedang Diproses',
                'waktu' => in_array($status, [
                    'Diproses',
                    'Dicuci',
                    'Dikeringkan',
                    'Siap Diambil',
                    'Selesai'
                ])
                    ? $pesanan->updated_at->format('d M Y H:i')
                    : null,
                'state' => in_array($status, [
                    'Diproses',
                    'Dicuci',
                    'Dikeringkan',
                    'Siap Diambil',
                    'Selesai'
                ])
                    ? 'done'
                    : 'pending',
            ],
            [
                'label' => 'Selesai',
                'waktu' => $status === 'Selesai'
                    ? $pesanan->updated_at->format('d M Y H:i')
                    : null,
                'state' => $status === 'Selesai'
                    ? 'done'
                    : 'pending',
            ],
        ];


        $detailNota = [
            'telepon' => $pesanan->nomor_hp,
            'alamat' => $pesanan->alamat,
            'layanan' => $pesanan->layanan->nama_layanan,
            'jumlah' => $pesanan->jumlah_sepatu,
            'ukuran' => $pesanan->ukuran_sepatu,

            'pengiriman' => $pesanan->metode_pengantaran === 'jemput'
                ? 'Dijemput'
                : 'Antar Sendiri',

            'subtotal' => $pesanan->layanan->harga * $pesanan->jumlah_sepatu,

            'ongkos' => $pesanan->ongkos_jemput ?? 0,

            'total' => $pesanan->total_biaya,

            'tanggal' => $pesanan->created_at
                ->format('d M Y H:i'),
        ];


        return view('pesanan.nota', [
            'pesanan' => $pesanan,
            'detailNota' => $detailNota,
            'statusLabel' => $statusLabel,
            'statusDesc' => $statusDesc,
            'noNota' => 'NOTA-' . $pesanan->nomor_pesanan,
            'noPesanan' => $pesanan->nomor_pesanan,
            'timeline' => $timeline,
        ]);
    }


    public function akun()
    {
        $user = Auth::user();

        return view('pesanan.akun', [
            'user' => [
                'nama' => $user->name,
                'email' => $user->email,
                'telepon' => $user->phone,
                'alamat' => $user->alamat,
                'foto' => $user->foto,
            ],
        ]);
    }


    public function updateAkun(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama' => ['required','string','max:100'],
            'email' => ['required','email','max:100','unique:users,email,' . $user->id],
            'telepon' => ['required','string','max:20'],
            'alamat' => ['nullable','string','max:255'],
            'password' => ['nullable','string','min:8'],
            'foto' => ['nullable','image','max:5120'],
        ]);

        $update = [
            'name' => $data['nama'],
            'email' => $data['email'],
            'phone' => $data['telepon'],
            'alamat' => $data['alamat'] ?? $user->alamat,
        ];

        if ($request->hasFile('foto')) {

            if ($user->foto) {
                Storage::disk('public')
                    ->delete($user->foto);
            }

            $update['foto'] = $request->file('foto')
                ->store('foto-profil','public');
        }

        if (!empty($data['password'])) {
            $update['password'] = bcrypt($data['password']);
        }

        $user->update($update);

        return back()
            ->with('sukses','Profil berhasil diperbarui.');
    }
}