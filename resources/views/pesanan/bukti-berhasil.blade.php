@extends('layouts.app')

@section('title', 'Pembayaran Berhasil — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
@endphp

<section class="bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-6 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.riwayat') }}" class="hover:text-[#1E7BC8]">Pembayaran</a>
            <span>&rsaquo;</span>
            <span>Upload Bukti Pembayaran</span>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#1566AD]">Pembayaran Berhasil</span>
        </nav>

        <div class="text-center animate-[fadeInUp_0.5s_ease-out]">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 ring-8 ring-emerald-50">
                <x-icon name="check-circle" class="h-9 w-9" />
            </span>
            <h1 class="mt-5 text-3xl font-bold text-[#1E293B]">Pembayaran Berhasil!</h1>
            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">Bukti pembayaran Anda telah kami terima. Pesanan sedang dalam proses verifikasi oleh admin. Status pesanan akan berubah setelah pembayaran berhasil diverifikasi.</p>
            <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600"><x-icon name="clock" class="h-4 w-4" /> Estimasi verifikasi: 1 &times; 24 jam kerja</p>
        </div>

        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8">
            <div class="grid gap-6 lg:grid-cols-5">
                {{-- Detail --}}
                <div class="lg:col-span-3">
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Informasi Pesanan</p>
                    <p class="mt-1 text-xl font-bold tracking-wide text-[#1566AD]">{{ $pesanan->nomor_pesanan }}</p>
                    <p class="text-xs text-slate-400">{{ $pesanan->created_at->format('d M Y') }} • {{ $pesanan->created_at->format('H:i') }}</p>

                    <dl class="mt-5 grid grid-cols-2 gap-x-4 gap-y-5 text-sm">
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Jenis Layanan</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan->layanan->nama_layanan }}</dd></div>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Jumlah Sepatu</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan->jumlah_sepatu }} Pasang</dd></div>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Metode Pengantaran</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ ucfirst($pesanan->metode_pengantaran) }}</dd></div>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Ongkos Jemput</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $rp($pesanan->ongkos_jemput ?? 0) }}</dd></div>
                        <div class="col-span-2"><dt class="text-[11px] uppercase tracking-wide text-slate-400">Alamat Penjemputan</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan->alamat }}</dd></div>
                    </dl>
                </div>

                {{-- Total + status --}}
                <div class="space-y-4 rounded-2xl bg-[#F2F7FD] p-5 lg:col-span-2">
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">Total Pembayaran</p>
                        <p class="mt-1 text-2xl font-bold text-[#1566AD]">{{ $rp($pesanan->total_biaya) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">Status Pesanan</p>
                        <div class="mt-1.5 rounded-xl border border-amber-200 bg-amber-50 p-3">
                            <p class="flex items-center gap-1.5 text-sm font-bold text-amber-600"><x-icon name="clock" class="h-4 w-4" /> Menunggu Verifikasi Admin</p>
                            <p class="mt-1 text-xs text-amber-600/80">Kami sedang memeriksa bukti transfer Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('pesanan.beranda') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-[#1566AD] shadow-sm transition hover:bg-slate-50">
                <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke Beranda
            </a>
            <a href="{{ route('pesanan.riwayat') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1566AD]">
                Lihat Riwayat Pesanan <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>
@endsection
