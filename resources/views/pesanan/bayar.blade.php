@extends('layouts.app')

@section('title', 'Pembayaran — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $kodeUrl = ltrim($pesanan['kode'], '#');
    $noRek = $rekening['nomor'] ?? '1234 5678 9012';
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        <nav class="mb-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.riwayat') }}" class="hover:text-[#1E7BC8]">Riwayat</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#1566AD]">Pembayaran</span>
        </nav>

        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Pembayaran</h1>
        <p class="mt-1.5 text-sm text-slate-500">Selesaikan pembayaran Anda untuk memproses pesanan cuci sepatu.</p>

        <div class="mt-8">
            <x-step-indicator :current="4" />
        </div>

        {{-- Ringkasan Tagihan --}}
        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-[#1E293B]">Ringkasan Tagihan</h2>
                <span class="rounded-full bg-[#EAF3FC] px-2.5 py-0.5 text-[11px] font-semibold text-[#1566AD]">{{ $pesanan['kode'] }}</span>
            </div>
            <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                <div><dt class="text-xs text-slate-400">Tanggal Pesanan</dt><dd class="mt-0.5 font-medium text-slate-700">{{ $pesanan['tanggal'] }}</dd></div>
                <div><dt class="text-xs text-slate-400">Metode Pengantaran</dt><dd class="mt-0.5 font-medium text-slate-700">{{ $pesanan['pengiriman'] }}</dd></div>
                <div><dt class="text-xs text-slate-400">Jenis Layanan</dt><dd class="mt-0.5 font-medium text-slate-700">{{ $pesanan['layanan'] }}</dd></div>
                <div><dt class="text-xs text-slate-400">Jumlah Sepatu</dt><dd class="mt-0.5 font-medium text-slate-700">{{ $pesanan['jumlah'] }} Pasang</dd></div>
                <div><dt class="text-xs text-slate-400">Ongkos Jemput</dt><dd class="mt-0.5 font-medium text-slate-700">{{ $rp($pesanan['ongkos'] ?? 0) }}</dd></div>
                <div><dt class="text-xs text-slate-400">Total Pembayaran</dt><dd class="mt-0.5 text-lg font-bold text-[#1566AD]">{{ $rp($pesanan['total']) }}</dd></div>
            </dl>
        </div>

        {{-- Rekening Tujuan Transfer --}}
        <div class="mt-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
            <h2 class="text-base font-bold text-[#1E293B]">Rekening Tujuan Transfer</h2>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-[#F2F7FD] p-5">
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Informasi Rekening</p>
                    <div class="mt-1.5 flex items-center gap-2">
                        <span class="rounded-md bg-[#1566AD] px-2 py-0.5 text-xs font-bold text-white">{{ $rekening['bank'] ?? 'BCA' }}</span>
                        <span id="nomor-rekening" class="text-lg font-bold tracking-wide text-[#1E293B]">{{ $noRek }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">a.n. {{ $rekening['nama'] ?? 'Cuci Sepatu PTK' }}</p>
                </div>
                <button type="button" id="btn-salin" data-nomor="{{ $noRek }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#0F2A4A] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1566AD]">
                    <x-icon name="clipboard" class="h-4 w-4" /> <span id="salin-label">Salin</span>
                </button>
            </div>
            <div class="mt-4 flex items-start gap-2.5 rounded-xl bg-[#EAF3FC] p-4 text-xs leading-relaxed text-[#1566AD]">
                <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0" />
                <span>Transfer sesuai nominal <strong>{{ $rp($pesanan['total']) }}</strong>. Setelah transfer, klik <strong>Sudah Transfer</strong> untuk mengunggah bukti pembayaran.</span>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="mt-6 flex items-center justify-between gap-4">
            <a href="{{ route('pesanan.riwayat') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke Riwayat
            </a>
            <a href="{{ route('pesanan.bukti', ['kode' => $kodeUrl]) }}" class="inline-flex items-center gap-2 rounded-lg bg-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1566AD]">
                Sudah Transfer <x-icon name="arrow-right" class="h-4 w-4" />
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        var btn = document.getElementById('btn-salin');
        if (! btn) return;
        btn.addEventListener('click', function () {
            var nomor = (btn.getAttribute('data-nomor') || '').replace(/\s+/g, '');
            navigator.clipboard.writeText(nomor).then(function () {
                var label = document.getElementById('salin-label');
                label.textContent = 'Tersalin';
                setTimeout(function () { label.textContent = 'Salin'; }, 1800);
            });
        });
    })();
</script>
@endpush
@endsection
