@extends('layouts.app')

@section('title', 'Ringkasan Pesanan — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Ringkasan Pesanan</h1>
        <p class="mt-1.5 text-sm text-slate-500">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran.</p>

        <div class="mt-8">
            <x-step-indicator :current="3" />
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-5">
            {{-- Detail --}}
            <div class="space-y-6 lg:col-span-3">
                {{-- Data Pelanggan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="user" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Data Pelanggan</h2>
                    </div>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Nama</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['nama'] ?? '-' }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Telepon</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['telepon'] ?? '-' }}</dd></div>
                        @if(! empty($pesanan['email']))
                            <div class="flex justify-between gap-4"><dt class="text-slate-500">Email</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['email'] }}</dd></div>
                        @endif
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Alamat</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['alamat'] ?? '-' }}</dd></div>
                    </dl>
                </div>

                {{-- Detail Pesanan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="clipboard" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Detail Pesanan</h2>
                    </div>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Layanan</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['layanan_nama'] ?? '-'}}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Estimasi</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['estimasi']?? '-'}}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Jumlah</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['jumlah'] ?? 0}} pasang</dd></div>
                        @if(! empty($pesanan['ukuran']))
                            <div class="flex justify-between gap-4"><dt class="text-slate-500">Ukuran</dt><dd class="text-right font-medium text-slate-700">{{ implode(', ', $pesanan['ukuran']) }}</dd></div>
                        @endif
                        @if(! empty($pesanan['catatan']))
                            <div class="flex justify-between gap-4"><dt class="text-slate-500">Catatan</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['catatan'] }}</dd></div>
                        @endif
                    </dl>
                </div>

                {{-- Metode Pengantaran --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-2">
                        <x-icon name="truck" class="h-5 w-5 text-[#1E7BC8]" />
                        <h2 class="text-base font-bold text-[#1E293B]">Metode Pengantaran</h2>
                    </div>
                    
                    <dl class="mt-4 space-y-3 text-sm">
                        
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Metode</dt>
                            <dd class="text-right font-medium text-slate-700">
                                {{ ($pesanan['metode'] ?? '') === 'jemput'
                                    ? 'Dijemput oleh Pemilik'
                                    : 'Antar Sendiri' }}
                            </dd>
                        </div>

                        @if(($pesanan['metode'] ?? '') === 'jemput')

                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Kecamatan</dt>
                                <dd class="text-right font-medium text-slate-700">
                                    {{ $pesanan['kecamatan'] ?? '-' }}
                                </dd>
                            </div>

                            <div class="flex justify-between gap-4">
                                <dt class="text-slate-500">Alamat Jemput</dt>
                                <dd class="text-right font-medium text-slate-700">
                                    {{ $pesanan['alamat_jemput'] ?? '-' }}
                                </dd>
                            </div>

                            @if(!empty($pesanan['alamat_jemput']))
                                <div class="mt-4 overflow-hidden rounded-xl border">
                                    <iframe
                                        class="h-56 w-full"
                                        loading="lazy"
                                        style="border:0"
                                        src="https://maps.google.com/maps?q={{ urlencode(($pesanan['alamat_jemput'] ?? '').', '.($pesanan['kecamatan'] ?? '').', Pontianak') }}&z=16&output=embed">
                                    </iframe>
                                </div>
                            @endif

                        @endif

                    </dl>
                </div>
            </div>

            {{-- Rincian biaya --}}
            <div class="lg:col-span-2">
                <div class="sticky top-24 space-y-4">
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Rincian Biaya</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between"><dt class="text-slate-500">Subtotal ({{ $pesanan['jumlah'] ?? 0 }} × {{ $pesanan['layanan_nama'] ?? '-' }})</dt><dd class="font-medium text-slate-700">{{ $rp($pesanan['subtotal'])}}</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Ongkos Jemput</dt><dd class="font-medium text-slate-700">{{ $rp($pesanan['ongkos_jemput'] ?? 0) }}</dd></div>
                            <div class="mt-2 flex justify-between border-t border-dashed border-slate-200 pt-3">
                                <dt class="text-base font-bold text-[#1E293B]">Total</dt>
                                <dd class="text-base font-bold text-[#1566AD]">{{ $rp($pesanan['total'] ?? $pesanan['subtotal']) }}</dd>
                            </div>
                        </dl>

                        <a href="{{ route('pesanan.pembayaran') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2">
                            Lanjut ke Pembayaran <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('pesanan.pengantaran') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
