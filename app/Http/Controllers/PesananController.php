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
     * Format angka menjadi Rupiah (mis. 35000 -> "Rp35.000").
     */
    public static function rupiah(int|float $nilai): string
    {
        return 'Rp' . number_format((float) $nilai, 0, ',', '.');
    }

    /**
     * Halaman 1 - Beranda Pelanggan.
     */
    public function beranda()
    {
        $semua = $this->layanan();

        $populer = collect($semua)
            ->only(['deep-cleaning-regular', 'one-day-service', 'repaint', 'leather-care'])
            ->all();

        return view('pesanan.beranda', [
            'populer'    => $populer,
            'keunggulan' => config('layanan.keunggulan', []),
            'syarat'     => config('layanan.syarat', []),
            'kontak'     => config('layanan.kontak', []),
        ]);
    }

    /**
     * Halaman 2 - Katalog Layanan.
     */
    public function katalog()
    {
        return view('pesanan.katalog', [
            'layanan' => $this->layanan(),
        ]);
    }

    /**
     * Halaman 3 - Form Pemesanan (Step 1: Data Pesanan).
     */
    public function form(Request $request)
    {
        $layanan = $this->layanan();
        $slug    = $request->query('layanan');

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
     * Proses Step 1 (Data Pesanan) -> simpan ke session -> Step 2 (Pengantaran).
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

        $item     = $layanan[$data['layanan']];
        $subtotal = $item['harga'] * (int) $data['jumlah'];

        $pesanan = array_merge($request->session()->get('pesanan', []), $data, [
            'layanan_nama'  => $item['nama'],
            'layanan_harga' => $item['harga'],
            'estimasi'      => $item['estimasi'],
            'ukuran'        => array_values(array_filter($data['ukuran'], fn ($u) => $u !== null && $u !== '')),
            'subtotal'      => $subtotal,
        ]);

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
     * Proses Step 2 (Pengantaran) -> hitung ongkos & total -> Step 3 (Ringkasan).
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
                'kecamatan' => ['required', 'string', 'in:' . implode(',', array_keys($ongkos))],
            ], [], ['kecamatan' => 'kecamatan']);

            $ongkosJemput = $ongkos[$data['kecamatan']] ?? 0;
        }

        $pesanan['metode']        = $data['metode'];
        $pesanan['kecamatan']     = $data['kecamatan'] ?? null;
        $pesanan['alamat_jemput'] = $data['alamat_jemput'] ?? null;
        $pesanan['ongkos_jemput'] = $ongkosJemput;
        $pesanan['total']         = ($pesanan['subtotal'] ?? 0) + $ongkosJemput;

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
     * Step 4 - Pembayaran: pilih metode pembayaran.
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
     * Proses Step 4 -> buat pesanan (status Menunggu Pembayaran) -> halaman Berhasil.
     */
    public function prosesPembayaran(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan || ! isset($pesanan['metode'])) {
            return redirect()->route('pesanan.beranda');
        }

        $data = $request->validate([
            'metode_bayar' => ['required', 'string', 'in:transfer,cash'],
        ]);

        $kode       = '#PTK' . now()->format('ymd') . strtoupper(Str::random(3));
        $pengiriman = ($pesanan['metode'] ?? 'antar') === 'jemput' ? 'Dijemput Pemilik' : 'Diantar Sendiri';

        $order = [
            'kode'         => $kode,
            'tanggal'      => now()->translatedFormat('d F Y'),
            'waktu'        => now()->translatedFormat('H.i') . ' WIB',
            'layanan'      => $pesanan['layanan_nama'] ?? '-',
            'slug'         => $pesanan['layanan'] ?? null,
            'jumlah'       => (int) ($pesanan['jumlah'] ?? 1),
            'ukuran'       => implode(', ', $pesanan['ukuran'] ?? []),
            'catatan'      => $pesanan['catatan'] ?? null,
            'nama'         => $pesanan['nama'] ?? '-',
            'telepon'      => $pesanan['telepon'] ?? '-',
            'alamat'       => $pesanan['alamat'] ?? '-',
            'pengiriman'   => $pengiriman,
            'kecamatan'    => $pesanan['kecamatan'] ?? null,
            'subtotal'     => $pesanan['subtotal'] ?? 0,
            'ongkos'       => $pesanan['ongkos_jemput'] ?? 0,
            'total'        => $pesanan['total'] ?? ($pesanan['subtotal'] ?? 0),
            'metode_bayar' => $data['metode_bayar'],
            'status'       => 'Menunggu Pembayaran',
            'bukti'        => null,
        ];

        $list   = $request->session()->get('pesanan_list', []);
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
        $kode    = $request->session()->get('pesanan_sukses');
        $pesanan = $kode ? $this->cariPesanan($request, $kode) : null;

        if (! $pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Halaman Pembayaran (instruksi transfer) untuk 1 pesanan.
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
     * Proses unggah bukti -> status Menunggu Verifikasi -> halaman berhasil.
     */
    public function prosesBukti(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode);

        if (! $pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'bukti.required' => 'Silakan unggah bukti pembayaran terlebih dahulu.',
            'bukti.mimes'    => 'Format berkas harus JPG, PNG, atau PDF.',
            'bukti.max'      => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');

        $this->ubahStatus($request, $kode, 'Menunggu Verifikasi', ['bukti' => $path]);
        $request->session()->put('pesanan_sukses', $pesanan['kode']);

        return redirect()->route('pesanan.bukti.berhasil');
    }

    /**
     * Halaman bukti pembayaran berhasil dikirim.
     */
    public function buktiBerhasil(Request $request)
    {
        $kode    = $request->session()->get('pesanan_sukses');
        $pesanan = $kode ? $this->cariPesanan($request, $kode) : null;

        if (! $pesanan) {
            return redirect()->route('pesanan.riwayat');
        }

        return view('pesanan.bukti-berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Batalkan pesanan -> status Dibatalkan.
     */
    public function batalkan(Request $request, string $kode)
    {
        $order = $this->ubahStatus($request, $kode, 'Dibatalkan');

        if (! $order) {
            return redirect()->route('pesanan.riwayat');
        }

        return redirect()->route('pesanan.riwayat')
            ->with('info', 'Pesanan ' . $order['kode'] . ' telah dibatalkan.');
    }

    /**
     * Cari 1 pesanan dari session berdasarkan kode (tanpa tanda #).
     *
     * @return array<string, mixed>|null
     */
    private function cariPesanan(Request $request, string $kode): ?array
    {
        $target = ltrim($kode, '#');

        foreach ($request->session()->get('pesanan_list', []) as $item) {
            if (ltrim($item['kode'], '#') === $target) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Ubah status pesanan di session (+ data tambahan).
     *
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>|null
     */
    private function ubahStatus(Request $request, string $kode, string $status, array $extra = []): ?array
    {
        $target  = ltrim($kode, '#');
        $list    = $request->session()->get('pesanan_list', []);
        $updated = null;

        foreach ($list as $i => $item) {
            if (ltrim($item['kode'], '#') === $target) {
                $list[$i]['status'] = $status;

                foreach ($extra as $k => $v) {
                    $list[$i][$k] = $v;
                }

                $updated = $list[$i];
            }
        }

        $request->session()->put('pesanan_list', $list);

        return $updated;
    }

    /**
     * Data contoh riwayat pesanan (dipakai halaman Riwayat & Nota).
     *
     * @return array<int, array<string, mixed>>
     */
    private function daftarRiwayat(): array
    {
        return [
            [
                'kode'         => '#PTK260712ABX',
                'tanggal'      => '12 Juli 2026',
                'waktu'        => '14.30 WIB',
                'layanan'      => 'Deep Cleaning / Regular',
                'slug'         => 'deep-cleaning-regular',
                'jumlah'       => 2,
                'ukuran'       => '42, 40',
                'catatan'      => 'Sepatu sangat kotor, bagian putih menguning.',
                'nama'         => 'Andi Saputra',
                'telepon'      => '0812-3456-7890',
                'alamat'       => 'Jl. Ahmad Yani No.12, Pontianak Selatan',
                'pengiriman'   => 'Diantar Sendiri',
                'subtotal'     => 70000,
                'ongkos'       => 0,
                'total'        => 70000,
                'metode_bayar' => 'transfer',
                'status'       => 'Selesai',
            ],
            [
                'kode'         => '#PTK260710KDL',
                'tanggal'      => '10 Juli 2026',
                'waktu'        => '09.15 WIB',
                'layanan'      => 'Unyellowing / Whitening',
                'slug'         => 'unyellowing',
                'jumlah'       => 1,
                'ukuran'       => '39',
                'catatan'      => 'Bagian midsole menguning.',
                'nama'         => 'Andi Saputra',
                'telepon'      => '0812-3456-7890',
                'alamat'       => 'Jl. Ahmad Yani No.12, Pontianak Selatan',
                'pengiriman'   => 'Dijemput Pemilik',
                'subtotal'     => 40000,
                'ongkos'       => 5000,
                'total'        => 45000,
                'metode_bayar' => 'transfer',
                'status'       => 'Diproses',
            ],
            [
                'kode'         => '#PTK260708QWZ',
                'tanggal'      => '8 Juli 2026',
                'waktu'        => '16.40 WIB',
                'layanan'      => 'Repaint Full',
                'slug'         => 'repaint-full',
                'jumlah'       => 1,
                'ukuran'       => '43',
                'catatan'      => 'Repaint warna putih penuh.',
                'nama'         => 'Andi Saputra',
                'telepon'      => '0812-3456-7890',
                'alamat'       => 'Jl. Ahmad Yani No.12, Pontianak Selatan',
                'pengiriman'   => 'Diantar Sendiri',
                'subtotal'     => 85000,
                'ongkos'       => 0,
                'total'        => 85000,
                'metode_bayar' => 'transfer',
                'status'       => 'Menunggu Verifikasi',
            ],
        ];
    }

    /**
     * Halaman Riwayat Pesanan (session + data contoh).
     */
    public function riwayat(Request $request)
    {
        $sesi    = array_reverse($request->session()->get('pesanan_list', []));
        $riwayat = array_merge($sesi, $this->daftarRiwayat());

        return view('pesanan.riwayat', [
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Halaman Nota Pesanan (detail + status pengerjaan).
     */
    public function nota(Request $request, string $kode)
    {
        $pesanan = $this->cariPesanan($request, $kode)
            ?? collect($this->daftarRiwayat())
                ->firstWhere(fn ($item) => ltrim($item['kode'], '#') === $kode);

        if (! $pesanan) {
            abort(404);
        }

        $langkah = ['Pembayaran Terverifikasi', 'Sedang Dicuci', 'Sedang Dikeringkan', 'Siap Diambil', 'Selesai'];

        $aktif = match ($pesanan['status']) {
            'Selesai'  => 5,
            'Diproses' => 1,
            default    => 0,
        };

        $waktu = [
            'Pembayaran Terverifikasi' => $pesanan['tanggal'] . ', 09.15',
            'Sedang Dicuci'            => $pesanan['tanggal'] . ', 11.30',
            'Sedang Dikeringkan'       => 'Estimasi Selesai',
            'Siap Diambil'             => null,
            'Selesai'                  => null,
        ];

        $timeline = [];
        foreach ($langkah as $i => $label) {
            $timeline[] = [
                'label' => $label,
                'waktu' => $waktu[$label] ?? null,
                'state' => $i < $aktif ? 'done' : ($i === $aktif ? 'current' : 'pending'),
            ];
        }

        [$statusLabel, $statusDesc] = match ($pesanan['status']) {
            'Selesai'  => ['Selesai', 'Pesanan Anda telah selesai. Terima kasih telah mempercayakan sepatu Anda kepada kami.'],
            'Diproses' => ['Sedang Dicuci', 'Sepatu Anda sedang dalam proses pencucian oleh pemilik usaha.'],
            default    => ['Menunggu Verifikasi', 'Pembayaran Anda sedang menunggu verifikasi admin.'],
        };

        return view('pesanan.nota', [
            'pesanan'     => $pesanan,
            'timeline'    => $timeline,
            'statusLabel' => $statusLabel,
            'statusDesc'  => $statusDesc,
            'noNota'      => 'NOTA-' . ltrim($pesanan['kode'], '#'),
            'noPesanan'   => ltrim($pesanan['kode'], '#'),
        ]);
    }

    /**
     * Halaman Akun.
     */
    public function akun()
{
    $user = auth()->user();

    return view('pesanan.akun', [
        'user' => [
            'nama'     => $user->name,
            'email'    => $user->email,
            'telepon'  => $user->phone,
            'alamat'   => '',
            'foto'     => $user->foto,
        ],
    ]);
}
public function updateAkun(Request $request)
{

    $data = $request->validate([
        'nama'     => 'required|string|max:100',
        'email'    => 'required|email|max:100',
        'telepon'  => 'required|string|max:20',
        'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = auth()->user();

    $user->name = $data['nama'];
    $user->email = $data['email'];
    $user->phone = $data['telepon'];

    if ($request->hasFile('foto')) {

        // Hapus foto lama jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Simpan foto baru
        $user->foto = $request->file('foto')->store('foto-profil', 'public');
        $path = $request->file('foto')->store('foto-profil', 'public');

$user->foto = $path;
$user->save();
    }

    $user->save();

    return redirect()->route('pesanan.akun')
        ->with('success', 'Profil berhasil diperbarui.');
}}