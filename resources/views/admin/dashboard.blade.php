@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Dashboard Admin</h1>
        <p class="mt-1 text-slate-500">Selamat datang kembali, Bapak Rudi!</p>
    </div>

    {{-- Kartu statistik --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Pendapatan Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
            </span>
            <p class="mt-4 text-sm text-slate-500">Pendapatan Hari Ini</p>
           <p class="mt-1 text-2xl font-bold text-[#1566AD]">
    Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}
</p>
        </div>
        {{-- Pesanan Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg>
            </span>
            <p class="mt-4 text-sm text-slate-500">Pesanan Hari Ini</p>
            <p class="mt-1 text-2xl font-bold text-slate-800">
    {{ $pesananHariIni }}
</p>
        </div>
        {{-- Pesanan Siap Diambil --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-6m0 0a1.5 1.5 0 0 0-3 0m3 0V5.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v9.375m14.25-9.375h3.375c.621 0 1.125.504 1.125 1.125v9.375" /></svg>
            </span>
            <p class="mt-4 text-sm text-slate-500">Pesanan Siap Diambil</p>
            <p class="mt-1 text-2xl font-bold text-slate-800">
    {{ $pesananSiapDiambil }}
</p>
            <p class="mt-1 text-[11px] font-semibold tracking-wide text-amber-500">PERLU DIAMBIL PELANGGAN</p>
        </div>
        {{-- Total Pendapatan --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </span>
            <p class="mt-1 text-2xl font-bold text-[#1566AD]">
    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
</p>

<p class="mt-1 text-[11px] font-semibold tracking-wide text-slate-400">
    TOTAL SELURUH WAKTU
</p>
        </div>
    </div>

    {{-- Pesanan yang membutuhkan tindakan --}}
    <div class="rounded-2xl border border-[#BFDCF5] bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-slate-900">Pesanan yang Membutuhkan Tindakan</h2>
            </div>
            <a href={{ route('admin.pesanan') }}" class="shrink-0 rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Lihat Semua</a>
        </div>
        <p class="mt-1 mb-4 text-sm text-slate-500">Pesanan yang perlu segera Anda tindak lanjuti.</p>

        <div class="overflow-x-auto">
            <div class="min-w-[720px] overflow-hidden rounded-xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-[#EAF3FC] text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">No. Pesanan</th>
                            <th class="px-5 py-3">Pelanggan</th>
                            <th class="px-5 py-3">Layanan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
    @forelse($pesanan as $item)
        <tr class="hover:bg-slate-50/70">
            <td class="px-5 py-4 font-semibold text-slate-700">
                #{{ $item->nomor_pesanan }}
            </td>

            <td class="px-5 py-4 text-slate-600">
                {{ $item->user->name }}
            </td>

            <td class="px-5 py-4 text-slate-600">
                {{ $item->layanan->nama_layanan }}
            </td>

            <td class="px-5 py-4">
                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                    Menunggu Verifikasi
                </span>
            </td>

            <td class="px-5 py-4 text-slate-500">
                {{ $item->created_at->format('d M Y') }}
                <span class="block text-xs text-slate-400">
                    {{ $item->created_at->format('H:i') }}
                </span>
            </td>

            <td class="px-5 py-4 text-center">
                <a href="{{ route('admin.verifikasi.detail', ['kode' => $item->nomor_pesanan]) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    Verifikasi
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                Tidak ada pembayaran yang menunggu verifikasi.
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pengingat --}}
    <div>
        <h2 class="mb-3 text-lg font-bold text-slate-900">Pengingat</h2>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <a href="{{ route('admin.verifikasi') }}" class="flex items-center gap-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 transition hover:shadow-md">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800">2 Pembayaran menunggu verifikasi</p>
                    <p class="text-sm text-slate-500">Segera lakukan verifikasi pembayaran pelanggan.</p>
                </div>
                <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </a>
            <a href="{{ route('admin.pesanan') }}" class="flex items-center gap-4 rounded-2xl border border-green-200 bg-green-50 p-4 transition hover:shadow-md">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5 12 3 3 7.5m18 0-9 4.5m9-4.5v9l-9 4.5m0-9L3 7.5m9 4.5v9m-9-13.5v9l9 4.5" /></svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-slate-800">2 Pesanan siap diambil</p>
                    <p class="text-sm text-slate-500">Informasikan ke pelanggan untuk pengambilan.</p>
                </div>
                <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>
    </div>

</div>
@endsection
