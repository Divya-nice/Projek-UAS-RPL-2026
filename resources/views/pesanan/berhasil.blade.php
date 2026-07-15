@extends('layouts.app')

@section('title', 'Pembayaran Berhasil — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $metodeLabel = ($pesanan['metode'] ?? '') === 'jemput' ? 'Dijemput Pemilik' : 'Diantar Sendiri';
    $alamatJemput = $pesanan['alamat'] ?? '-';
    if (! empty($pesanan['alamat_jemput'])) {
        $alamatJemput = $pesanan['alamat_jemput'];
    }
    if (($pesanan['metode'] ?? '') === 'jemput' && ! empty($pesanan['kecamatan'])) {
        $alamatJemput .= ', ' . $pesanan['kecamatan'];
    }
@endphp

<section class="bg-[#F2F7FD] py-14 lg:py-20">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        {{-- Header sukses --}}
        <div class="text-center">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-500 shadow-lg shadow-green-500/30 ring-8 ring-green-100 animate-[fadeInUp_0.5s_ease-out]">
                <x-icon name="check" class="h-8 w-8 text-white" />
            </span>
            <h1 class="mt-6 text-3xl font-bold text-[#0F2A4A]">Pembayaran Berhasil!</h1>
            <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-slate-500">
                Bukti pembayaran Anda telah kami terima. Pesanan sedang dalam proses verifikasi oleh admin. Status pesanan akan berubah setelah pembayaran berhasil diverifikasi.
            </p>
            <p class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-green-600">
                <x-icon name="clock" class="h-4 w-4" /> Estimasi verifikasi: 1 &times; 24 jam kerja
            </p>
        </div>

        {{-- Kartu informasi pesanan --}}
        <div class="mt-8 overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
            <div class="flex flex-col gap-6 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Informasi Pesanan</p>
                    <p class="mt-1 text-2xl font-bold text-[#1566AD]">{{ $pesanan['kode'] }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ $pesanan['tanggal'] }}</p>
                </div>
                <div class="rounded-xl bg-[#F2F7FD] p-4 sm:min-w-[220px]">
                    <div class="flex items-center justify-between gap-6">
                        <span class="text-xs text-slate-500">Total Pembayaran</span>
                        <span class="text-lg font-bold text-[#1E293B]">{{ $rp($pesanan->total_biaya) }}</span>
                    </div>
                    <div class="mt-3 flex items-start gap-2 rounded-lg bg-amber-50 p-2.5">
                        <x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" />
                        <div>
                            <p class="text-xs font-semibold text-amber-700">Menunggu Verifikasi Admin</p>
                            <p class="text-[11px] leading-snug text-amber-600">Kami sedang memeriksa bukti transfer Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <dl class="grid gap-x-8 gap-y-5 py-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs text-slate-400">Jenis Layanan</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['layanan_nama'] }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Jumlah Sepatu</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['jumlah'] }} Pasang</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Metode Pengantaran</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $metodeLabel }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">Ongkos Jemput</dt>
                    <dd class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $rp($pesanan['ongkos_jemput']) }}</dd>
                </div>
            </dl>

            <div class="border-t border-slate-100 pt-6">
                <dt class="text-xs text-slate-400">Alamat Penjemputan</dt>
                <dd class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $alamatJemput }}</dd>
            </div>
        </div>

        {{-- Tombol aksi --}}
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('pesanan.beranda') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-[#1E293B] shadow-sm transition hover:bg-slate-50">
                <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke Beranda
            </a>
            <a href="{{ route('pesanan.katalog') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1E293B] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#0F2A4A]">
                Pesan Lagi <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>
@endsection
