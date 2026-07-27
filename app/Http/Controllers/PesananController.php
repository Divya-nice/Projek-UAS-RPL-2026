<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\Layanan;

class PesananController extends Controller
{
    /**
     * Ambil seluruh data layanan dari config (katalog statis untuk
     * halaman Beranda / Katalog / Form pemesanan).
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
                'leather-care',
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

        // Mulai wizard pesanan dari awal setiap kali step 1 disubmit, agar
        // sisa data dari layanan/isian sebelumnya tidak ikut terbawa.
        $pesanan = array_merge(
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
        $pesanan['kecamatan'] = $data['metode'] === 'jemput' ? $data['kecamatan'] : null;
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
     * Proses Step 4 - Simpan Pesanan ke database.
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
            ? 'jemput'
            : 'antar';

        // Katalog di halaman pelanggan bersumber dari config (single source
        // of truth untuk teks/harga tampilan), sedangkan tabel `layanans`
        // dikelola terpisah oleh admin. Untuk menjaga relasi layanan_id
        // tetap valid tanpa memaksa admin menyamakan nama persis, kita
        // cari-atau-buat baris layanan yang sesuai berdasarkan nama.
        $layanan = Layanan::firstOrCreate(
            ['nama_layanan' => $pesanan['layanan_nama']],
            [
                'harga'    => $pesanan['layanan_harga'],
                'estimasi' => $pesanan['estimasi'] ?? null,
                'status'   => 'aktif',
            ]
        );

        $pesananBaru = Pesanan::create([
            'user_id'            => Auth::id(),
            'layanan_id'         => $layanan->id,
            'nomor_pesanan'      => $kode,
            'nama'               => $pesanan['nama'],
            'nomor_hp'           => $pesanan['telepon'],
            'alamat'             => $pesanan['alamat'],
            'wilayah'            => $pesanan['kecamatan'] ?? null,
            'jumlah_sepatu'      => $pesanan['jumlah'],
            'ukuran_sepatu'      => implode(', ', $pesanan['ukuran']),
            'foto_sepatu'        => null,
            'metode_pengantaran' => $pengiriman,
            'pin_lokasi'         => null,
            'total_biaya'        => $pesanan['total'],
            'status'             => 'Menunggu Pembayaran',
            'status_pembayaran'  => 'menunggu_upload',
        ]);

        $request->session()->put('pesanan_sukses', $pesananBaru->nomor_pesanan);
        $request->session()->forget('pesanan');

        return redirect()->route('pesanan.berhasil');
    }

    /**
     * Halaman Pesanan Berhasil Dibuat.
     */
    public function berhasil(Request $request)
    {
        $kode = $request->session()->get('pesanan_sukses');

        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->first();

        if (! $pesanan) {
            return redirect()->route('pesanan.beranda');
        }

        return view('pesanan.berhasil', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Halaman Pembayaran (instruksi transfer) untuk pesanan tertentu.
     */
    public function bayar(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

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
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.bukti', [
            'pesanan' => $pesanan,
        ]);
    }

    /**
     * Proses Upload Bukti Pembayaran.
     */
    public function prosesBukti(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'bukti' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        if (! empty($pesanan->bukti_pembayaran)) {
            Storage::disk('public')->delete($pesanan->bukti_pembayaran);
        }

        $path = $request->file('bukti')->store('bukti-pembayaran', 'public');

        $pesanan->update([
            'bukti_pembayaran'  => $path,
            'status_pembayaran' => 'menunggu_verifikasi',
            'status'            => 'Menunggu Verifikasi',
        ]);

        $request->session()->put('pesanan_sukses', $pesanan->nomor_pesanan);

        return redirect()->route('pesanan.bukti.berhasil');
    }

    /**
     * Halaman bukti pembayaran berhasil dikirim.
     */
    public function buktiBerhasil(Request $request)
    {
        $kode = $request->session()->get('pesanan_sukses');

        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->first();

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
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (in_array($pesanan->status, ['Selesai', 'Dibatalkan'], true)) {
            return redirect()
                ->route('pesanan.riwayat')
                ->with('info', 'Pesanan tidak dapat dibatalkan.');
        }

        $pesanan->update([
            'status' => 'Dibatalkan',
        ]);

        return redirect()
            ->route('pesanan.riwayat')
            ->with('info', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Menampilkan riwayat pesanan.
     */
    public function riwayat(Request $request)
    {
        $riwayat = Pesanan::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pesanan.riwayat', compact('riwayat'));
    }

    /**
     * Menampilkan nota pesanan.
     */
    public function nota(Request $request, string $kode)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $kode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.nota', compact('pesanan'));
    }

    /**
     * Halaman akun.
     */
    public function akun(Request $request)
    {
        return view('pesanan.akun', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update data akun.
     */
    public function updateAkun(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'telepon'  => ['required', 'string', 'max:20'],
            'alamat'   => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'foto'     => ['nullable', 'image', 'max:5120'],
        ]);

        $update = [
            'name'   => $data['nama'],
            'email'  => $data['email'],
            'phone'  => $data['telepon'],
            'alamat' => $data['alamat'] ?? $user->alamat,
        ];

        if ($request->hasFile('foto')) {
            if (! empty($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $update['foto'] = $request->file('foto')->store('foto-profil', 'public');
        }

        if (! empty($data['password'])) {
            $update['password'] = bcrypt($data['password']);
        }

        $user->update($update);

        return back()->with('sukses', 'Profil berhasil diperbarui.');
    }
}