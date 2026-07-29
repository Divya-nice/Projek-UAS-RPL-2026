@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
@php
    $labelCard = 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm';
    $cardTitle = 'mb-4 rounded-lg bg-[#EFEAFB] px-3 py-2 text-sm font-semibold text-slate-700';
@endphp
<div class="mx-auto max-w-6xl space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-sm text-slate-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('admin.pesanan') }}" class="hover:text-slate-600">Kelola Pesanan</a>
        <span>&rsaquo;</span>
        <span class="font-medium text-[#1E7BC8]">Detail Pesanan</span>
    </nav>

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Detail Pesanan</h1>
        <p class="mt-1 text-slate-500">Kelola dan pantau semua pesanan pelanggan</p>
    </div>

    {{-- Info strip --}}
    <div class="grid grid-cols-1 gap-px overflow-hidden rounded-2xl border border-slate-200 bg-slate-200 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
        <div class="flex items-center gap-3 bg-white p-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg></span>
            <div><p class="text-xs text-slate-400">Nomor Pesanan</p><p class="font-semibold text-slate-800">#{{ $pesanan->nomor_pesanan }}</p></div>
        </div>
        <div class="flex items-center gap-3 bg-white p-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg></span>
            <div><p class="text-xs text-slate-400">Tanggal &amp; Jam</p><p class="font-semibold text-slate-800">{{ $pesanan->created_at->translatedFormat('d F Y') }}</p><p class="text-xs text-slate-400">{{ $pesanan->created_at->format('H:i') }} WIB</p></div>
        </div>
        <div class="flex items-center gap-3 bg-white p-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></span>
            <div><p class="text-xs text-slate-400">Status</p><span class="mt-0.5 inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-600">{{ $pesanan->status }}</span></div>
        </div>
        <div class="flex items-center gap-3 bg-white p-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg></span>
            <div><p class="text-xs text-slate-400">Total Tagihan</p><p class="font-bold text-[#1566AD]">Rp{{ number_format($pesanan->total_biaya,0,',','.') }}</p></div>
        </div>
    </div>

    {{-- Baris 1: Data Pelanggan + Detail Layanan --}}
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="{{ $labelCard }}">
                <p class="{{ $cardTitle }}">Data Pelanggan</p>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3"><svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg><div><p class="text-xs text-slate-400">Nama Lengkap</p><p class="font-medium text-slate-700">{{ $pesanan->user->name }}</p></div></div>
                        <div class="flex items-start gap-3"><svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg><div><p class="text-xs text-slate-400">No. Handphone</p><p class="font-medium text-slate-700">{{ $pesanan->nomor_hp }}</p></div></div>
                        <div class="flex items-start gap-3"><svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg><div><p class="text-xs text-slate-400">Email</p><p class="font-medium text-slate-700">{{ $pesanan->user->email }}</p></div></div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3"><svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg><div><p class="text-xs text-slate-400">Alamat Pengiriman</p><p class="font-medium text-slate-700">{{ $pesanan->alamat }}</p></div></div>
                        <div class="h-40 overflow-hidden rounded-xl border border-slate-200">
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($pesanan->alamat) }}&output=embed"
                                class="h-full w-full"
                                loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto Kondisi Sepatu --}}
            <div class="{{ $labelCard }}">
                <p class="{{ $cardTitle }}">Foto Kondisi Sepatu</p>
                <div class="relative flex h-48 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
                    <span class="text-sm text-slate-300">Foto kondisi sepatu</span>
                    @if($pesanan->foto_sepatu)
                        <img
                            src="{{ asset('storage/'.$pesanan->foto_sepatu) }}"
                            alt="Foto Sepatu"
                            class="absolute inset-0 h-full w-full object-contain">
                    @else
                        <span class="text-sm text-slate-300">
                            Foto belum diupload
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Layanan --}}
        <div class="space-y-6">
            <div class="{{ $labelCard }}">
                <p class="{{ $cardTitle }}">Detail Layanan</p>
                <div class="space-y-4 text-sm">
                    <div><p class="text-xs text-slate-400">Jenis Layanan</p><p class="font-semibold text-[#1566AD]">{{ $pesanan->layanan->nama_layanan }}</p></div>
                    <div class="flex justify-between"><div><p class="text-xs text-slate-400">Jumlah</p><p class="font-medium text-slate-700">{{ $pesanan->jumlah_sepatu }} Pasang</p></div><div class="text-right"><p class="text-xs text-slate-400">Ukuran</p><p class="font-medium text-slate-700">{{ $pesanan->ukuran_sepatu }}</p></div></div>
                    <div><p class="text-xs text-slate-400">Catatan Tambahan</p><p class="italic text-slate-600">{{ $pesanan->catatan ?? '-' }}</p></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris 2: Metode Pengantaran + Informasi Pembayaran --}}
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="{{ $labelCard }}">
            <p class="{{ $cardTitle }}">Metode Pengantaran</p>
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-6m0 0a1.5 1.5 0 0 0-3 0m3 0V5.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v9.375m14.25-9.375h3.375c.621 0 1.125.504 1.125 1.125v9.375" /></svg></span>
                <div class="flex-1 space-y-3">
                    <div><p class="text-xs text-slate-400">Metode</p><p class="font-semibold text-slate-700">{{ $pesanan->metode_pengantaran }}</p></div>
                    <div><p class="text-xs text-slate-400">Alamat Penjemputan</p><p class="text-sm text-slate-600">{{ $pesanan->alamat }}</p></div>
                    <div class="flex items-center justify-between border-t border-slate-100 pt-3"><p class="text-sm text-slate-500">Biaya Antar/Jemput</p><p class="font-semibold text-[#1566AD]">Rp{{ number_format($pesanan->ongkos_jemput ?? 0,0,',','.') }}</p></div>
                </div>
            </div>
        </div>

        <div class="{{ $labelCard }}">
            <p class="{{ $cardTitle }}">Informasi Pembayaran</p>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between"><p class="text-slate-500">Metode Pembayaran</p><p class="font-semibold text-slate-700">{{ $pesanan->metode_bayar }}</p></div>
                <div class="flex items-center justify-between"><p class="text-slate-500">Status</p><span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ ucwords(str_replace('_',' ',$pesanan->status_pembayaran)) }}</span></div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3"><p class="text-slate-500">Subtotal Layanan</p><p class="text-slate-700">Rp{{ number_format($pesanan->layanan->harga * $pesanan->jumlah_sepatu,0,',','.') }}</p></div>
                <div class="flex items-center justify-between"><p class="text-slate-500">Biaya Tambahan</p><p class="text-slate-700">Rp{{ number_format($pesanan->ongkos_jemput ?? 0,0,',','.') }}</p></div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3"><p class="font-semibold text-slate-700">Total Pembayaran</p><p class="text-lg font-bold text-[#1566AD]">Rp{{ number_format($pesanan->total_biaya,0,',','.') }}</p></div>
                <button type="button" data-bukti-open class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-[#EAF3FC] py-2.5 text-sm font-semibold text-[#1566AD] hover:bg-[#dceaf8]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    Lihat Bukti Pembayaran
                </button>
            </div>
        </div>
    </div>

    {{-- Update Status --}}
    <div class="{{ $labelCard }}">
        <p class="{{ $cardTitle }}">Update Status Pesanan</p>
        <p class="-mt-2 mb-4 text-sm text-slate-500">Perbarui status pengerjaan pesanan secara real-time untuk pelanggan.</p>

        <form action="{{ route('admin.pesanan.status', $pesanan->nomor_pesanan) }}" method="POST">
        @csrf
        <div class="rounded-xl bg-[#EAF3FC] p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex-1">
                    <p class="text-xs text-slate-500">Status Saat Ini</p>
                    <p class="mt-1 flex items-center gap-2 font-semibold text-orange-600"><span class="h-2.5 w-2.5 rounded-full bg-orange-500"></span> {{ $pesanan->status }}</p>
                </div>
                <svg class="hidden h-5 w-5 text-slate-400 sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0-6.75-6.75M19.5 12l-6.75 6.75" /></svg>
                <div class="flex-1">
                    <label class="text-xs text-slate-500">Pilih Status Baru</label>
                    <select
                        name="status"
                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm">
                        <option value="Diproses" {{ $pesanan->status=='Diproses'?'selected':'' }}>Diproses</option>
                        <option value="Dicuci" {{ $pesanan->status=='Dicuci'?'selected':'' }}>Dicuci</option>
                        <option value="Dikeringkan" {{ $pesanan->status=='Dikeringkan'?'selected':'' }}>Dikeringkan</option>
                        <option value="Siap Diambil" {{ $pesanan->status=='Siap Diambil'?'selected':'' }}>Siap Diambil</option>
                        <option value="Selesai" {{ $pesanan->status=='Selesai'?'selected':'' }}>Selesai</option>
                        </select>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm text-slate-500">Catatan Admin (Internal)</label>
            <textarea name="catatan_admin" rows="3" placeholder="Tambahkan catatan pengerjaan di sini..." class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">{{ old('catatan_admin', $pesanan->catatan_admin) }}</textarea>
        </div>

        <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('admin.pesanan') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali Ke Daftar
            </a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1566AD] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#12599c]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" /></svg>
                Simpan Perubahan Status
            </button>
        </div>
        </form>
    </div>

</div>

{{-- Modal Bukti Pembayaran --}}
<div id="modal-bukti" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-4">
    <div class="relative w-full max-w-md rounded-2xl bg-white p-3 shadow-2xl">
        <button type="button" data-bukti-close class="absolute -right-3 -top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-lg ring-1 ring-slate-200 hover:bg-slate-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
        <p class="mb-3 px-1 text-sm font-semibold text-slate-700">Bukti Pembayaran</p>
        <div class="relative flex h-96 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
            <span class="px-6 text-center text-sm text-slate-400">Bukti pembayaran belum diunggah pelanggan.</span>
            @if($pesanan->bukti_pembayaran)
            <img
                src="{{ asset('storage/'.$pesanan->bukti_pembayaran) }}"
                class="absolute inset-0 h-full w-full object-contain">
            @else
            <span class="px-6 text-center text-sm text-slate-400">
                Bukti pembayaran belum diunggah pelanggan.
            </span>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('modal-bukti');
        if (!modal) return;
        function open() { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        function close() { modal.classList.add('hidden'); modal.classList.remove('flex'); }
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-bukti-open]')) { e.preventDefault(); open(); return; }
            if (e.target.closest('[data-bukti-close]') || e.target === modal) { close(); }
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    })();
</script>
@endpush

@endsection