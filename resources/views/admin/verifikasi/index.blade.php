@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Verifikasi Pembayaran</h1>
        <p class="mt-1 text-slate-500">Verifikasi pembayaran pelanggan sebelum pesanan diproses.</p>
    </div>

    {{-- 4 kartu ringkasan --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        {{-- Semua Pembayaran --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between">
        <p class="text-sm text-slate-500">Semua Pembayaran</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3M3.75 19.5h16.5A2.25 2.25 0 0 0 22.5 17.25V6.75A2.25 2.25 0 0 0 20.25 4.5H3.75A2.25 2.25 0 0 0 1.5 6.75v10.5A2.25 2.25 0 0 0 3.75 19.5Z" /></svg>
        </span>
    </div>
    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $semua }}</p>
</div>

{{-- Menunggu Verifikasi (disorot) --}}
<div class="rounded-2xl border-2 border-[#1E7BC8] bg-[#EAF3FC] p-5 shadow-sm">
    <div class="flex items-start justify-between">
        <p class="text-sm font-semibold text-[#1566AD]">Menunggu Verifikasi</p>
        <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-600">Urgent</span>
    </div>
    <p class="mt-3 text-3xl font-bold text-[#1566AD]">{{ $menungguVerifikasi }}</p>
</div>

{{-- Diterima --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between">
        <p class="text-sm text-slate-500">Diterima</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        </span>
    </div>
    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $diterima }}</p>
</div>

{{-- Ditolak --}}
<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between">
        <p class="text-sm text-slate-500">Ditolak</p>
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-500">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        </span>
    </div>
    <p class="mt-3 text-3xl font-bold text-slate-900">{{ $ditolak }}</p>
</div>

        </div>

    {{-- Daftar pembayaran menunggu verifikasi --}}
    <div class=" mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-bold text-slate-900">Daftar Pembayaran Menunggu Verifikasi</h2>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                </span>
                <form method="GET"
                    action="{{ route('admin.verifikasi') }}"
                    class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </span>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau nomor pesanan..."
                        class="w-full rounded-lg border border-slate-200 py-2 pl-9 pr-10 text-sm sm:w-64">

                    <button
                        type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[#1566AD]">

                        Cari

                    </button>

                </form>
            </div>
        </div>

<div class="divide-y divide-slate-100">

@forelse ($pending as $p)

<a href="{{ route('admin.verifikasi.detail', ['kode' => $p->nomor_pesanan]) }}"
   class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50">

    {{-- Order + pelanggan --}}
    <div class="min-w-0 flex-1">
        <p class="font-semibold text-slate-800">
            {{ $p->nomor_pesanan }}
        </p>

        <p class="mt-0.5 truncate text-sm text-slate-500">
            {{ $p->nama }} · {{ $p->nomor_hp }}
        </p>
    </div>

    {{-- Tanggal --}}
    <div class="hidden text-right text-sm text-slate-500 sm:block">
        <p>{{ $p->created_at->format('d M Y') }}</p>
        <p class="text-xs text-slate-400">
            {{ $p->created_at->format('H:i') }}
        </p>
    </div>

    {{-- Status --}}
    @if($p->status_pembayaran == 'menunggu_upload')

        <span class="inline-flex shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
            Belum Upload Bukti
        </span>

    @else

        <span class="inline-flex shrink-0 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
            Menunggu Verifikasi
        </span>

    @endif

    {{-- Total --}}
    <p class="shrink-0 text-right font-bold text-[#1566AD]">
        Rp{{ number_format($p->total_biaya,0,',','.') }}
    </p>

    {{-- Panah --}}
    <span class="shrink-0 text-slate-300">
        <svg class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke-width="2"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>
    </span>

</a>

@empty

<div class="px-5 py-10 text-center text-slate-500">
    Belum ada pembayaran yang menunggu verifikasi.
</div>

@endforelse

@if($pending->hasPages())

<div class="flex items-center justify-between border-t border-slate-100 px-5 py-4">

    <p class="text-sm text-slate-500">
        Menampilkan
        {{ $pending->firstItem() }}
        -
        {{ $pending->lastItem() }}
        dari
        {{ $pending->total() }}
        pembayaran
    </p>

    {{ $pending->onEachSide(1)->links() }}

</div>

@endif

</div>
@endsection
