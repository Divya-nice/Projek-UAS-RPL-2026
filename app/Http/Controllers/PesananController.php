<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        // Layanan populer yang ditonjolkan di beranda.
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
     * Halaman 3 - Form Pemesanan.
     * Layanan dapat dipilih lebih dulu lewat query string ?layanan=slug.
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
     * Proses Form Pemesanan -> simpan ke session -> arahkan ke Ringkasan.
     */
    public function prosesForm(Request $request)
    {
        $layanan = $this->layanan();
        $ongkos  = config('layanan.ongkos_jemput', []);

        $data = $request->validate([
            'layanan'      => ['required', 'string', 'in:' . implode(',', array_keys($layanan))],
            'nama'         => ['required', 'string', 'max:100'],
            'telepon'      => ['required', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'max:100'],
            'alamat'       => ['required', 'string', 'max:255'],
            'jumlah'       => ['required', 'integer', 'min:1', 'max:20'],
            'ukuran'       => ['required', 'array', 'min:1'],
            'ukuran.*'     => ['nullable', 'string', 'max:10'],
            'catatan'      => ['nullable', 'string', 'max:500'],
            'metode'       => ['required', 'string', 'in:jemput,antar'],
            'kecamatan'    => ['nullable', 'string', 'in:' . implode(',', array_keys($ongkos))],
            'alamat_jemput'=> ['nullable', 'string', 'max:255'],
        ], [], [
            'telepon' => 'nomor telepon',
        ]);

        $item        = $layanan[$data['layanan']];
        $ongkosJemput = 0;

        if ($data['metode'] === 'jemput') {
            $request->validate([
                'kecamatan' => ['required', 'string', 'in:' . implode(',', array_keys($ongkos))],
            ], [], ['kecamatan' => 'kecamatan']);

            $ongkosJemput = $ongkos[$data['kecamatan']] ?? 0;
        }

        $subtotal = $item['harga'] * (int) $data['jumlah'];
        $total    = $subtotal + $ongkosJemput;

        $pesanan = array_merge($data, [
            'layanan_nama'  => $item['nama'],
            'layanan_harga' => $item['harga'],
            'estimasi'      => $item['estimasi'],
            'ukuran'        => array_values(array_filter($data['ukuran'], fn ($u) => $u !== null && $u !== '')),
            'ongkos_jemput' => $ongkosJemput,
            'subtotal'      => $subtotal,
            'total'         => $total,
        ]);

        $request->session()->put('pesanan', $pesanan);

        return redirect()->route('pesanan.ringkasan');
    }

    /**
     * Halaman 4 - Ringkasan Pesanan.
     */
    public function ringkasan(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        return view('pesanan.ringkasan', [
            'pesanan' => $pesanan,
            'rekening'=> config('layanan.kontak.rekening', []),
        ]);
    }

    /**
     * Proses konfirmasi Ringkasan -> buat nomor pesanan -> halaman Berhasil.
     */
    public function konfirmasi(Request $request)
    {
        $pesanan = $request->session()->get('pesanan');

        if (! $pesanan) {
            return redirect()->route('pesanan.form')
                ->with('info', 'Silakan lengkapi data pesanan terlebih dahulu.');
        }

        $data = $request->validate([
            'metode_bayar' => ['required', 'string', 'in:transfer,cash'],
        ]);

        $pesanan['metode_bayar'] = $data['metode_bayar'];
        $pesanan['kode']         = '#PTK' . now()->format('ymd') . strtoupper(Str::random(3));
        $pesanan['tanggal']      = now()->translatedFormat('d F Y \\• H.i') . ' WIB';

        // Simpan sebagai pesanan terakhir & bersihkan draft.
        $request->session()->forget('pesanan');
        $request->session()->put('pesanan_sukses', $pesanan);

        if (($pesanan['metode_bayar'] ?? null) === 'transfer') {
            return redirect()->route('pesanan.pembayaran');
        }

        return redirect()->route('pesanan.berhasil');
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
     * Halaman Riwayat Pesanan (data contoh untuk tampilan).
     */
    public function riwayat()
    {
        $riwayat = [
            [
                'kode'    => '#PTK260712ABX',
                'tanggal' => '12 Juli 2026',
                'layanan' => 'Deep Cleaning / Regular',
                'jumlah'  => 2,
                'total'   => 70000,
                'status'  => 'Selesai',
            ],
            [
                'kode'    => '#PTK260710KDL',
                'tanggal' => '10 Juli 2026',
                'layanan' => 'Unyellowing / Whitening',
                'jumlah'  => 1,
                'total'   => 45000,
                'status'  => 'Diproses',
            ],
            [
                'kode'    => '#PTK260708QWZ',
                'tanggal' => '8 Juli 2026',
                'layanan' => 'Repaint Full',
                'jumlah'  => 1,
                'total'   => 85000,
                'status'  => 'Menunggu Verifikasi',
            ],
        ];

        return view('pesanan.riwayat', [
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Halaman Akun (tampilan; memakai data user login bila tersedia).
     */
    public function akun()
    {
        $user = [
            'nama'    => optional(auth()->user())->name ?? 'Nabila',
            'email'   => optional(auth()->user())->email ?? 'Taehyung123@gmail.com',
            'telepon' => '081234567890',
            'alamat'  => 'Jl. Merdeka No. 123, Pontianak, Kalimantan Barat',
        ];

        return view('pesanan.akun', [
            'user' => $user,
        ]);
    }
}
