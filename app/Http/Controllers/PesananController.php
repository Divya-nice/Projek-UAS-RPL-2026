<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    /**
     * Ambil seluruh data layanan dari config.
     *
     * @return array<string, mixed>
     */
    private function layanan(): array
    {
        return config('layanan.layanan', []);
    }

    /**
     * Format angka menjadi Rupiah.
     */
    public static function rupiah(int|float $nilai): string
    {
        return 'Rp' . number_format((float) $nilai, 0, ',', '.');
    }

    /**
     * Halaman Beranda.
     */
    public function beranda()
    {
        $semua = $this->layanan();

        $populer = collect($semua)
            ->only([
                'deep-cleaning-regular',
                'one-day-service',
                'repaint',
                'leather-care'
            ])
            ->all();

        return view('pesanan.beranda', [
            'populer'    => $populer,
            'keunggulan' => config('layanan.keunggulan', []),
            'syarat'     => config('layanan.syarat', []),
            'kontak'     => config('layanan.kontak', []),
        ]);
    }

    /**
     * Halaman Katalog.
     */
    public function katalog()
    {
        return view('pesanan.katalog', [
            'layanan' => $this->layanan(),
        ]);
    }

    /**
     * Form Pemesanan (Step 1).
     * Layanan dapat dipilih melalui query ?layanan=slug
     */
    public function form(Request $request)
    {
        $layanan = $this->layanan();
        $slug = $request->query('layanan');

        if (! $slug || ! isset($layanan[$slug])) {
            $slug = array_key_first($layanan);
        }

        return view('pesanan.form', [
            'layanan'      => $layanan,
            'slugTerpilih' => $slug,
            'ongkos'       => config('layanan.ongkos_jemput', []),
        ]);
    }

    /**
     * Proses Step 1.
     */
    public function prosesForm(Request $request)
    {
        $layanan = $this->layanan();

        $data = $request->validate([
            'layanan'  => ['required', 'string', 'in:' . implode(',', array_keys($layanan))],
            'nama'     => ['required', 'string', 'max:100'],
            'telepon'  => ['required', 'string', 'max:20'],
            'email'    => ['nullable', 'email', 'max:100'],
            'alamat'   => ['required', 'string', 'max:255'],
            'jumlah'   => ['required', 'integer', 'min:1', 'max:20'],
            'ukuran'   => ['required', 'array', 'min:1'],
            'ukuran.*' => ['nullable', 'string', 'max:10'],
            'catatan'  => ['nullable', 'string', 'max:500'],
        ], [], [
            'telepon' => 'nomor telepon',
        ]);

        $item = $layanan[$data['layanan']];

        $subtotal = $item['harga'] * (int) $data['jumlah'];

        $pesanan = array_merge(
            $request->session()->get('pesanan', []),
            $data,
            [
                'layanan_nama'  => $item['nama'],
                'layanan_harga' => $item['harga'],
                'estimasi'      => $item['estimasi'],
                'ukuran'        => array_values(
                    array_filter(
                        $data['ukuran'],
                        fn ($u) => $u !== null && $u !== ''
                    )
                ),
                'subtotal'      => $subtotal,
            ]
        );

        $request->session()->put('pesanan', $pesanan);

        return redirect()->route('pesanan.pengantaran');
    }
  
      /**
     * Step 2 - Metode Pengantaran.
     */
    public function pengantaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || empty($pesanan['nama'])) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        return view('pesanan.pengantaran', [
            'pesanan' => $pesanan,
            'ongkos'  => config('layanan.ongkos_jemput', []),
        ]);
    }

    /**
     * Proses Step 2 (Pengantaran).
     */
    public function prosesPengantaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || empty($pesanan['nama'])) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        $ongkos = config('layanan.ongkos_jemput', []);

        $data = $request->validate([
            'metode'        => ['required', 'string', 'in:jemput,antar'],
            'kecamatan'     => ['nullable', 'string', 'in:' . implode(',', array_keys($ongkos))],
            'alamat_jemput' => ['nullable', 'string', 'max:255'],
        ]);

        $ongkosJemput = 0;

        if ($data['metode'] === 'jemput') {
            $request->validate([
                'kecamatan' => [
                    'required',
                    'string',
                    'in:' . implode(',', array_keys($ongkos)),
                ],
            ]);

            $ongkosJemput = $ongkos[$data['kecamatan']] ?? 0;
        }

        $pesanan['metode'] = $data['metode'];
        $pesanan['kecamatan'] = $data['kecamatan'] ?? null;
        $pesanan['alamat_jemput'] = $data['alamat_jemput'] ?? null;
        $pesanan['ongkos_jemput'] = $ongkosJemput;
        $pesanan['total'] = ($pesanan['subtotal'] ?? 0) + $ongkosJemput;

        $request->session()->put('pesanan', $pesanan);

        return redirect()->route('pesanan.ringkasan');
    }
      /**
     * Step 3 - Ringkasan Pesanan.
     */
    public function ringkasan(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || ! isset($pesanan['metode'])) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        return view('pesanan.ringkasan', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Step 4 - Pembayaran.
     */
    public function pembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || ! isset($pesanan['metode'])) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        return view('pesanan.pembayaran', [
            'pesanan'  => $pesanan,
            'rekening' => config('layanan.kontak.rekening', []),
        ]);
    }

    /**
     * Proses Step 4 - Simpan Pesanan.
     */
    public function prosesPembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || ! isset($pesanan['metode'])) {
            return redirect()->route('pesanan.beranda');
        }

        $data = $request->validate([
            'metode_bayar' => ['required', 'string', 'in:transfer,cod'],
        ]);

        $kode = '#PTK' . now()->format('ymd') . strtoupper(Str::random(3));

        $pengiriman = ($pesanan['metode'] === 'jemput')
            ? 'Dijemput Pemilik'
            : 'Diantar Sendiri';

        $order = [
            'kode'           => $kode,
            'tanggal'        => now()->translatedFormat('d F Y'),
            'waktu'          => now()->translatedFormat('H.i') . ' WIB',

            'layanan'        => $pesanan['layanan_nama'],
            'slug'           => $pesanan['layanan'],

            'jumlah'         => (int) $pesanan['jumlah'],
            'ukuran'         => implode(', ', $pesanan['ukuran']),
            'catatan'        => $pesanan['catatan'],

            'nama'           => $pesanan['nama'],
            'telepon'        => $pesanan['telepon'],
            'email'          => $pesanan['email'] ?? null,
            'alamat'         => $pesanan['alamat'],

            'pengiriman'     => $pengiriman,
            'kecamatan'      => $pesanan['kecamatan'] ?? null,

            'subtotal'       => $pesanan['subtotal'],
            'ongkos'         => $pesanan['ongkos_jemput'],
            'total'          => $pesanan['total'],

            'metode_bayar'   => $data['metode_bayar'],
            'status'         => 'Menunggu Pembayaran',

            'bukti'          => null,
        ];

        $list = $request->session()->get('pesanan_list', []);

        $list[] = $order;

        $request->session()->put('pesanan_list', $list);
        $request->session()->put('pesanan_sukses', $kode);

        $request->session()->forget('pesanan');

        return redirect()->route('pesanan.berhasil');
    }
  
      /**
     * Halaman Pesanan Berhasil Dibuat.
     */
    public function berhasil(Request $request)
    {
        $kode = $request->session()->get('pesanan_sukses');

        $pesanan = $kode
            ? $this->cariPesanan($request, $kode)
            : null;

        if (! $pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Halaman Pembayaran (instruksi transfer).
     */
    public function bayar(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan || ($pesanan['metode_bayar'] ?? '') !== 'transfer') {
            return redirect()->route('pesanan.riwayat');
        }

        return view('pesanan.bayar', [
            'pesanan'  => $pesanan,
            'rekening' => config('layanan.kontak.rekening', []),
        ]);
    }

    /**
     * Halaman Upload Bukti Pembayaran.
     */
    public function bukti(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan || ($pesanan['metode_bayar'] ?? '') !== 'transfer') {
            return redirect()->route('pesanan.riwayat');
        }

        return view('pesanan.bukti', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Proses Upload Bukti Pembayaran.
     */
    public function prosesBukti(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        $request->validate([
            'bukti' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $path = $request->file('bukti')
            ->store('bukti-pembayaran', 'public');

        $this->ubahStatus(
            $request,
            $kode,
            'Menunggu Verifikasi',
            [
                'bukti' => $path,
            ]
        );

        $request->session()->put(
            'pesanan_sukses',
            $pesanan['kode']
        );

        return redirect()->route('pesanan.bukti.berhasil');
    }

    /**
     * Halaman bukti pembayaran berhasil dikirim.
     */
    public function buktiBerhasil(Request $request)
    {
        $kode = $request->session()->get('pesanan_sukses');

        $pesanan = $kode
            ? $this->cariPesanan($request, $kode)
            : null;

        if (! $pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        return view('pesanan.bukti-berhasil', [
            'pesanan' => $pesanan,
        ]);
    }
  
      /**
     * Membatalkan pesanan.
     */
    public function batalkan(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        if (in_array($pesanan['status'], ['Selesai', 'Dibatalkan'], true)) {
            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan tidak dapat dibatalkan.');
        }

        $this->ubahStatus($request, $kode, 'Dibatalkan');

        return redirect()
            ->route('pesanan.riwayat')
            ->with('info', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Menampilkan riwayat pesanan.
     */
    public function riwayat(Request $request)
    {
        return view('pesanan.riwayat', [
            'riwayat' => $request->session()->get('pesanan_list', []),
        ]);
    }

    /**
     * Menampilkan nota pesanan.
     */
    public function nota(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan) {
            abort(404);
        }

        return view('pesanan.nota', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Halaman akun.
     */
    public function akun(Request $request)
    {
        return view('pesanan.akun', [
            'user' => $request->session()->get('user', [
                'nama'     => 'Pengguna',
                'email'    => 'user@email.com',
                'telepon'  => '',
                'alamat'   => '',
                'foto'     => null,
            ]),
        ]);
    }

    /**
     * Update data akun.
     */
    public function updateAkun(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100'],
            'telepon'  => ['required', 'string', 'max:20'],
            'alamat'   => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'foto'     => ['nullable', 'image', 'max:5120'],
        ]);

        $user = $request->session()->get('user', []);

        if ($request->hasFile('foto')) {

            if (! empty($user['foto'])) {
                Storage::disk('public')->delete($user['foto']);
            }

            $data['foto'] = $request
                ->file('foto')
                ->store('foto-profil', 'public');
        } else {
            $data['foto'] = $user['foto'] ?? null;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $request->session()->put('user', array_merge($user, $data));

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }

    /**
     * Mencari pesanan berdasarkan kode.
     */
    private function cariPesanan(Request $request, string $kode): ?array
    {
        $list = $request->session()->get('pesanan_list', []);

        foreach ($list as $item) {
            if (ltrim($item['kode'], '#') === ltrim($kode, '#')) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Mengubah status pesanan.
     */
    private function ubahStatus(
        Request $request,
        string $kode,
        string $status,
        array $tambahan = []
    ): void {

        $list = $request->session()->get('pesanan_list', []);

        foreach ($list as &$item) {

            if (ltrim($item['kode'], '#') === ltrim($kode, '#')) {

                $item['status'] = $status;

                foreach ($tambahan as $key => $value) {
                    $item[$key] = $value;
                }

                break;
            }
        }

        $request->session()->put('pesanan_list', $list);
    }
}
    