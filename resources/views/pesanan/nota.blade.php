@extends('layouts.app')

@section('title', 'Nota Pesanan — Cuci Sepatu PTK')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $badgeNota = match($pesanan['status']) {
        'Selesai'  => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        'Diproses' => 'bg-sky-50 text-sky-600 ring-sky-100',
        default    => 'bg-amber-50 text-amber-600 ring-amber-100',
    };
    $statusIcon = match($pesanan['status']) {
        'Selesai'  => 'check-circle',
        'Diproses' => 'sparkles',
        default    => 'clock',
    };
    $labelOngkos = $pesanan['pengiriman'] === 'Dijemput Pemilik' ? 'Biaya Jemput' : 'Biaya Antar';
@endphp

<section class="min-h-screen bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>/</span>
            <a href="{{ route('pesanan.riwayat') }}" class="hover:text-[#1E7BC8]">Riwayat</a>
            <span>/</span>
            <span class="font-medium text-[#1566AD]">Nota Pesanan</span>
        </nav>

        {{-- Header --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#1E293B] sm:text-4xl">Nota Pesanan</h1>
                <p class="mt-1.5 text-sm text-slate-500">Berikut adalah detail nota dan status pesanan Anda.</p>
            </div>
            <div class="flex items-start gap-3 rounded-2xl bg-[#DCEBFB] p-4 lg:max-w-md">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/70 text-[#1566AD]">
                    <x-icon name="{{ $statusIcon }}" class="h-6 w-6" />
                </span>
                <div>
                    <p class="text-sm font-bold text-[#1566AD]">{{ $statusLabel }}</p>
                    <p class="mt-0.5 text-xs leading-relaxed text-[#1566AD]/80">{{ $statusDesc }}</p>
                </div>
            </div>
        </div>

        <hr class="my-6 border-slate-200">

        {{-- Kembali --}}
        <div class="flex justify-center">
            <a href="{{ route('pesanan.riwayat') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1566AD]">
                <x-icon name="arrow-left" class="h-4 w-4" /> Kembali ke Riwayat
            </a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            {{-- Kolom kiri --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Data Pelanggan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="user" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Data Pelanggan</h2>
                    </div>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Nama Lengkap</p>
                            <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['nama'] }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Telepon</p>
                            <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['telepon'] }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Alamat Pengiriman</p>
                            <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['alamat'] }}</p>
                        </div>
                    </div>
                </div>

                {{-- Detail Pesanan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="clipboard" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Detail Pesanan</h2>
                    </div>
                    <div class="mt-5 flex flex-col gap-5 sm:flex-row">
                        <x-shoe-thumb :slug="$pesanan['slug'] ?? null" class="aspect-square w-full shrink-0 rounded-xl sm:w-32" />
                        <div class="grid flex-1 gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Layanan</p>
                                <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['layanan'] }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Jumlah</p>
                                <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['jumlah'] }} Pasang</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Ukuran</p>
                                <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['ukuran'] }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Pengiriman</p>
                                <p class="mt-1 text-sm font-semibold text-[#1E293B]">{{ $pesanan['pengiriman'] }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Catatan</p>
                                <p class="mt-1 rounded-lg bg-[#F2F7FD] px-3 py-2 text-sm italic text-slate-600">“{{ $pesanan['catatan'] }}”</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Nota --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="clipboard" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Informasi Nota</h2>
                    </div>
                    <dl class="mt-4 divide-y divide-slate-100 text-sm">
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-slate-500">No. Nota</dt>
                            <dd class="font-bold text-[#1E293B]">{{ $noNota }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-slate-500">No. Pesanan</dt>
                            <dd class="font-semibold text-[#1E293B]">{{ $noPesanan }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-slate-500">Tanggal</dt>
                            <dd class="font-semibold text-[#1E293B]">{{ $pesanan['tanggal'] }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <dt class="text-slate-500">Status</dt>
                            <dd><span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 {{ $badgeNota }}">{{ $statusLabel }}</span></dd>
                        </div>
                    </dl>
                </div>

                {{-- Rincian Pembayaran --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="wallet" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Rincian Pembayaran</h2>
                    </div>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">{{ $pesanan['layanan'] }} ({{ $pesanan['jumlah'] }}x)</span>
                            <span class="font-medium text-slate-700">{{ $rp($pesanan['subtotal']) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">{{ $labelOngkos }}</span>
                            <span class="font-medium text-slate-700">{{ $rp($pesanan['ongkos']) }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-3">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-medium text-slate-700">{{ $rp($pesanan['subtotal'] + $pesanan['ongkos']) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between rounded-xl bg-[#0F2A4A] px-5 py-4">
                        <span class="text-sm font-semibold text-white">Total Pembayaran</span>
                        <span class="text-lg font-bold text-white">{{ $rp($pesanan['total']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan --}}
            <div class="space-y-4">
                {{-- Status Pengerjaan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <h2 class="text-base font-bold text-[#1E293B]">Status Pengerjaan</h2>
                    <ol class="mt-5 space-y-1">
                        @foreach($timeline as $step)
                            <li class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    @if($step['state'] === 'pending')
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-200 bg-white"></span>
                                    @else
                                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#1E7BC8] text-white"><x-icon name="check" class="h-3.5 w-3.5" /></span>
                                    @endif
                                    @unless($loop->last)
                                        <span class="my-1 h-8 w-0.5 {{ $step['state'] === 'done' ? 'bg-[#1E7BC8]' : 'bg-slate-200' }}"></span>
                                    @endunless
                                </div>
                                <div class="pb-2">
                                    <p class="text-sm font-semibold {{ $step['state'] === 'pending' ? 'text-slate-400' : 'text-[#1E293B]' }}">{{ $step['label'] }}</p>
                                    @if($step['waktu'])
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $step['waktu'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Info --}}
                <div class="flex items-start gap-2.5 rounded-2xl border-l-4 border-[#1E7BC8] bg-[#EAF3FC] p-4 text-xs leading-relaxed text-[#1566AD]">
                    <x-icon name="shield-check" class="mt-0.5 h-4 w-4 shrink-0" />
                    <span>Status pesanan akan diperbarui secara berkala oleh pemilik usaha hingga pesanan selesai.</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
