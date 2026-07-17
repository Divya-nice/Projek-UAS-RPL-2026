@extends('layouts.app')

@section('title', 'Pesanan Berhasil Dibuat — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $kodeUrl = ltrim($pesanan['kode'], '#');
    $transfer = ($pesanan['metode_bayar'] ?? '') === 'transfer';
@endphp

<section class="bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <nav class="mb-6 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.katalog') }}" class="hover:text-[#1E7BC8]">Pesan Layanan</a>
            <span>&rsaquo;</span>
            <span>Ringkasan Pesanan</span>
            <span>&rsaquo;</span>
            <span>Pembayaran</span>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#1566AD]">Pesanan berhasil</span>
        </nav>

        <div class="text-center animate-[fadeInUp_0.5s_ease-out]">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 ring-8 ring-emerald-50">
                <x-icon name="check-circle" class="h-9 w-9" />
            </span>
            <h1 class="mt-5 text-3xl font-bold text-[#1E293B]">Pesanan Berhasil Dibuat!</h1>
            <p class="mx-auto mt-2 max-w-lg text-sm text-slate-500">Terima kasih telah melakukan pesanan layanan. Silahkan selesaikan pembayaran agar pesanan dapat segera diproses oleh pemilik usaha.</p>
            <p class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600"><x-icon name="clock" class="h-4 w-4" /> Estimasi verifikasi: 1 &times; 24 jam kerja</p>
        </div>

        <div class="mt-8 grid gap-5 lg:grid-cols-5">
            {{-- Ringkasan Pesanan --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 lg:col-span-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-[#1E293B]">Ringkasan Pesanan</h2>
                        <p class="mt-0.5 text-[11px] uppercase tracking-wide text-slate-400">Transaction Details</p>
                    </div>
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-600 ring-1 ring-amber-100">{{ $pesanan['status'] }}</span>
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-x-4 gap-y-5 text-sm">
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="clipboard" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">No. Pesanan</dt><dd class="mt-0.5 font-semibold text-[#1566AD]">{{ $pesanan['kode'] }}</dd></div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="clock" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Tanggal</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan['tanggal'] }}</dd></div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="sparkles" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Layanan</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan['layanan'] }}</dd></div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="clipboard" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Jumlah</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan['jumlah'] }} Pasang</dd></div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="truck" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Metode Pengantaran</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $pesanan['pengiriman'] }}</dd></div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="wallet" class="h-4 w-4" /></span>
                        <div><dt class="text-[11px] uppercase tracking-wide text-slate-400">Metode Pembayaran</dt><dd class="mt-0.5 font-semibold text-[#1E293B]">{{ $transfer ? 'Transfer Bank' : 'Tunai (COD)' }}</dd></div>
                    </div>
                </dl>

                <div class="mt-6 flex items-center justify-between border-t border-dashed border-slate-200 pt-5">
                    <span class="text-sm font-semibold text-[#1E293B]">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-[#1566AD]">{{ $rp($pesanan['total']) }}</span>
                </div>
            </div>

            {{-- Langkah Selanjutnya + tombol --}}
            <div class="space-y-4 lg:col-span-2">
                <div class="rounded-2xl bg-[#EAF3FC] p-6 ring-1 ring-[#D6E7F8]">
                    <div class="flex items-center gap-2">
                        <x-icon name="clock" class="h-5 w-5 text-[#1566AD]" />
                        <h2 class="text-sm font-bold leading-tight text-[#1566AD]">Langkah<br>Selanjutnya</h2>
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-[#1566AD]/90">
                        @if($transfer)
                            Silakan lakukan pembayaran sesuai informasi yang tersedia. Setelah bukti pembayaran berhasil diunggah, status pesanan akan berubah menjadi <strong>Menunggu Verifikasi</strong>.
                        @else
                            Pesanan tunai (COD) akan dibayar saat sepatu dijemput atau diantar. Status pesanan akan diperbarui oleh pemilik usaha setelah pembayaran diterima.
                        @endif
                    </p>
                </div>

                @if($transfer)
                    <a href="{{ route('pesanan.bayar', ['kode' => $kodeUrl]) }}" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95">
                        <x-icon name="wallet" class="h-4 w-4" /> Lakukan Pembayaran
                    </a>
                @endif
                <a href="{{ route('pesanan.riwayat') }}" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-[#1566AD] shadow-sm transition hover:bg-slate-50">
                    <x-icon name="clipboard" class="h-4 w-4" /> Lihat Pesanan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
