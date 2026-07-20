@extends('layouts.admin')

@section('title', 'Detail Verifikasi Pembayaran')

@section('content')
@php
    $labelCard = 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm';
    $cardTitle = 'mb-4 rounded-lg bg-[#EFEAFB] px-3 py-2 text-sm font-semibold text-slate-700';
@endphp
<div class="mx-auto max-w-5xl space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-sm text-slate-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('admin.verifikasi') }}" class="hover:text-slate-600">Verifikasi Pembayaran</a>
        <span>&rsaquo;</span>
        <span class="font-medium text-[#1E7BC8]">Detail</span>
    </nav>

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Detail Verifikasi Pembayaran</h1>
        <p class="mt-1 text-slate-500">Periksa bukti pembayaran sebelum menyetujui pesanan</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Kiri: Informasi pembayaran --}}
        <div class="{{ $labelCard }}">
            <p class="{{ $cardTitle }}">Informasi Pembayaran</p>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between"><p class="text-slate-500">No. Pesanan</p><p class="font-semibold text-slate-700">{{ $pesanan['kode'] }}</p></div>
                <div class="flex items-center justify-between"><p class="text-slate-500">Nama Pelanggan</p><p class="font-medium text-slate-700">{{ $pesanan['nama'] }}</p></div>
                <div class="flex items-center justify-between"><p class="text-slate-500">Metode Pembayaran</p><p class="font-medium text-slate-700">{{ ucfirst($pesanan['metode_bayar']) }}</p></div>
                <div class="flex items-center justify-between"><p class="text-slate-500">Tanggal Transfer</p><p class="font-medium text-slate-700">{{ $pesanan['tanggal'] }}{{ $pesanan['waktu'] }}</p></div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3"><p class="text-slate-500">Status</p><span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $pesanan['status'] }}</span></div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3"><p class="font-semibold text-slate-700">Total Pembayaran</p><p class="text-lg font-bold text-[#1566AD]">Rp{{ number_format($pesanan['total'], 0, ',', '.') }}</p></div>
            </div>
        </div>

        {{-- Kanan: Bukti pembayaran --}}
        <div class="{{ $labelCard }}">
            <p class="{{ $cardTitle }}">Bukti Pembayaran Pelanggan</p>
            <button type="button" data-bukti-open class="group relative flex h-72 w-full items-center justify-center overflow-hidden rounded-xl bg-slate-100">
                <span class="px-6 text-center text-sm text-slate-400">Bukti pembayaran belum diunggah pelanggan.</span>
                <img src="{{ asset('images/bukti-pembayaran.jpg') }}" alt="Bukti pembayaran pelanggan" class="absolute inset-0 h-full w-full bg-slate-100 object-contain" onerror="this.remove()" />
                <span class="absolute bottom-3 right-3 flex items-center gap-1.5 rounded-lg bg-slate-900/70 px-2.5 py-1 text-xs font-medium text-white opacity-0 transition group-hover:opacity-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    Perbesar
                </span>
            </button>
            <p class="mt-2 text-center text-xs text-slate-400">Klik gambar untuk memperbesar</p>
        </div>
    </div>

    {{-- Aksi verifikasi --}}
<div class="{{ $labelCard }}">
    <p class="{{ $cardTitle }}">Tindakan Verifikasi</p>
    <div class="flex flex-col gap-3 sm:flex-row">

   <form action="{{ route('admin.verifikasi.tolak', $pesanan['kode']) }}"
      method="POST"
      onsubmit="return confirm('Tolak pembayaran ini?')">
    @csrf

    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-500 hover:bg-red-50">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
        Tolak Pembayaran
    </button>
</form>

    <form action="{{ route('admin.verifikasi.terima', $pesanan['kode']) }}" method="POST" onsubmit="return confirm('Terima pembayaran ini?')">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
            Terima Pembayaran
        </button>
    </form>

</div>
</div>
    


{{-- Modal Bukti (perbesar) --}}
<div id="modal-bukti" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-4">
    <div class="relative w-full max-w-md rounded-2xl bg-white p-3 shadow-2xl">
        <button type="button" data-close="modal-bukti" class="absolute -right-3 -top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-lg ring-1 ring-slate-200 hover:bg-slate-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
        <p class="mb-3 px-1 text-sm font-semibold text-slate-700">Bukti Pembayaran</p>
        <div class="relative flex h-96 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
            @if (!empty($pesanan['bukti']))
    <img
        src="{{ asset('storage/' . $pesanan['bukti']) }}"
        alt="Bukti pembayaran pelanggan"
        class="absolute inset-0 h-full w-full bg-slate-100 object-contain"
    >
@else
    <span class="px-6 text-center text-sm text-slate-400">
        Bukti pembayaran belum diunggah pelanggan.
    </span>
@endif
        </div>
    </div>
</div>

{{-- Modal sukses verifikasi --}}
<div id="modal-sukses" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
            <svg class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
        </span>
        <h3 class="mt-4 text-lg font-bold text-slate-900">Pembayaran Berhasil Diverifikasi!</h3>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">Pembayaran pelanggan telah diterima. Status pesanan otomatis berubah menjadi
            <span class="mt-1 inline-flex rounded-full bg-[#EAF3FC] px-2.5 py-0.5 text-xs font-semibold text-[#1566AD]">Diproses</span>
        </p>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('admin.verifikasi') }}" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">Kembali ke Verifikasi</a>
            <a href="{{ route('admin.pesanan') }}" class="flex-1 rounded-xl bg-[#1566AD] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#12599c]">Lihat Pesanan</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        function bind(openSel, modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            function open() { modal.classList.remove('hidden'); modal.classList.add('flex'); }
            function close() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
            document.addEventListener('click', function (e) {
                if (e.target.closest(openSel)) { e.preventDefault(); open(); return; }
                if (e.target.closest('[data-close="' + modalId + '"]') || e.target === modal) { close(); }
            });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
        }
        bind('[data-bukti-open]', 'modal-bukti');
        bind('[data-terima-open]', 'modal-sukses');
    })();
</script>
@endpush

@endsection
