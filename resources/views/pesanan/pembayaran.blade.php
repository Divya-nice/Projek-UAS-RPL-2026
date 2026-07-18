@extends('layouts.app')

@section('title', 'Pembayaran — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $noRek = $rekening['nomor'] ?? '1234 5678 9012';
    $metodeLama = old('metode_bayar', 'transfer');
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <nav class="mb-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.katalog') }}" class="hover:text-[#1E7BC8]">Pesan Layanan</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.form') }}" class="hover:text-[#1E7BC8]">Data Pesanan</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.pengantaran') }}" class="hover:text-[#1E7BC8]">Metode Pengantaran</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.ringkasan') }}" class="hover:text-[#1E7BC8]">Ringkasan Pesanan</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#1566AD]">Pembayaran</span>
        </nav>

        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Pembayaran</h1>
        <p class="mt-1.5 text-sm text-slate-500">Lengkapi data pesanan Anda sebelum melanjutkan ke proses pengantaran.</p>

        <div class="mt-8">
            <x-step-indicator :current="4" />
        </div>

        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                <ul class="list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pesanan.pembayaran.proses') }}" class="mt-8 grid gap-6 lg:grid-cols-3">
            @csrf

            {{-- Kolom kiri --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Ringkasan Tagihan --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-[#1E293B]">Ringkasan Tagihan</h2>
                        <span class="rounded-full bg-[#EAF3FC] px-3 py-1 text-[11px] font-semibold text-[#1566AD]">#PTK{{ now()->format('ymd') }}XXX</span>
                    </div>
                    <dl class="mt-5 grid grid-cols-2 gap-x-6 gap-y-5 text-sm">
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Tanggal Pesanan</dt><dd class="mt-1 font-semibold text-[#1E293B]">{{ now()->translatedFormat('d F Y') }}</dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Metode Pengantaran</dt><dd class="mt-1 font-semibold text-[#1E293B]">{{ ($pesanan['metode'] ?? '') === 'jemput' ? 'Dijemput Pemilik' : 'Diantar Sendiri' }}</dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Jenis Layanan</dt><dd class="mt-1 font-semibold text-[#1E293B]">{{ $pesanan['layanan_nama'] ?? '-' }}</dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Ongkos Jemput</dt><dd class="mt-1 font-semibold text-[#1E293B]">{{ $rp($pesanan['ongkos_jemput'] ?? 0) }}</dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Jumlah Sepatu</dt><dd class="mt-1 font-semibold text-[#1E293B]">{{ $pesanan['jumlah'] ?? 0 }} Pasang</dd></div>
                        <div><dt class="text-xs uppercase tracking-wide text-slate-400">Total Pembayaran</dt><dd class="mt-1 text-2xl font-bold text-[#1566AD]">{{ $rp($pesanan['total'] ?? $pesanan['subtotal'] ?? 0) }}</dd></div>
                    </dl>
                </div>

                {{-- Pilih Metode Pembayaran --}}
                <div class="rounded-2xl bg-[#EEF1FB] p-6 ring-1 ring-[#E0E6F5]">
                    <h2 class="text-base font-bold text-[#1E293B]">Pilih Metode Pembayaran</h2>
                    <div class="mt-4 space-y-3">
                        <label class="bayar-opsi flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 transition">
                            <input type="radio" name="metode_bayar" value="transfer" class="accent-[#1E7BC8]" @checked($metodeLama === 'transfer')>
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="wallet" class="h-4 w-4" /></span>
                            <span class="text-sm font-semibold text-[#1E293B]">Transfer Bank</span>
                        </label>
                        <label class="bayar-opsi flex cursor-pointer items-center gap-3 rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 transition">
                            <input type="radio" name="metode_bayar" value="cash" class="accent-[#1E7BC8]" @checked($metodeLama === 'cash')>
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1566AD]"><x-icon name="wallet" class="h-4 w-4" /></span>
                            <span class="text-sm font-semibold text-[#1E293B]">Cash (Bayar di Tempat)</span>
                        </label>
                    </div>

                    {{-- Informasi Rekening (khusus transfer) --}}
                    <div id="box-rekening" class="mt-3 rounded-xl bg-white p-5 shadow-sm">
                        <p class="text-[11px] uppercase tracking-wide text-slate-400">Informasi Rekening</p>
                        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="rounded-md bg-[#1566AD] px-2 py-0.5 text-xs font-bold text-white">{{ $rekening['bank'] ?? 'BCA' }}</span>
                                <span class="text-lg font-bold tracking-wide text-[#1E293B]">{{ $noRek }}</span>
                            </div>
                            <button type="button" id="btn-salin" data-nomor="{{ $noRek }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#0F2A4A] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#1566AD]">
                                <x-icon name="clipboard" class="h-4 w-4" /> <span id="salin-label">Salin</span>
                            </button>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">a.n. {{ $rekening['nama'] ?? 'Cuci Sepatu PTK' }}</p>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan --}}
            <div class="space-y-5">
                <div id="panel-transfer" class="space-y-5">
                    <div class="rounded-2xl bg-[#0F2A4A] p-6 text-white shadow-sm">
                        <div class="flex items-center gap-2">
                            <x-icon name="clock" class="h-5 w-5" />
                            <h3 class="text-base font-bold">Menunggu<br>Pembayaran</h3>
                        </div>
                        <p class="mt-3 text-xs leading-relaxed text-white/80">Silakan lakukan pembayaran sebelum batas waktu agar pesanan tidak dibatalkan secara otomatis oleh sistem.</p>
                    </div>

                    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                        <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wide text-red-500">
                            <x-icon name="clock" class="h-4 w-4" /> Batas Waktu Pembayaran
                        </div>
                        <p class="mt-2 text-lg font-bold leading-snug text-red-600">{{ now()->translatedFormat('d F Y') }}, 23.59 WIB</p>
                    </div>
                </div>

                <div id="panel-instruksi" class="rounded-2xl bg-[#EAF3FC] p-6 ring-1 ring-[#D6E7F8]">
                    <h3 class="text-sm font-bold text-[#1566AD]">INSTRUKSI PENTING</h3>
                    <ul class="mt-4 space-y-3 text-xs leading-relaxed text-[#1566AD]/90">
                        <li class="flex gap-2.5"><x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0" /> <span>Transfer sesuai nominal hingga 3 digit terakhir.</span></li>
                        <li class="flex gap-2.5"><x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0" /> <span>Simpan bukti transfer Anda sebagai bukti sah.</span></li>
                        <li class="flex gap-2.5"><x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0" /> <span>Bukti pembayaran akan diverifikasi oleh admin maksimal 1&times;24 jam.</span></li>
                    </ul>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="mt-2 flex items-center justify-between gap-4 lg:col-span-3">
                <a href="{{ route('pesanan.ringkasan') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#0F2A4A] px-7 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1566AD] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2">
                    Lanjutkan <x-icon name="arrow-right" class="h-4 w-4" />
                </button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        var boxRekening = document.getElementById('box-rekening');
        var panelTransfer = document.getElementById('panel-transfer');
        var panelInstruksi = document.getElementById('panel-instruksi');

        function sync() {
            var transfer = document.querySelector('input[name=metode_bayar]:checked');
            var isTransfer = transfer && transfer.value === 'transfer';
            document.querySelectorAll('.bayar-opsi').forEach(function (label) {
                var dipilih = label.querySelector('input').checked;
                label.classList.toggle('border-[#1E7BC8]', dipilih);
                label.classList.toggle('border-slate-200', ! dipilih);
            });
            boxRekening.classList.toggle('hidden', ! isTransfer);
            panelTransfer.classList.toggle('hidden', ! isTransfer);
            if (panelInstruksi) panelInstruksi.classList.toggle('hidden', ! isTransfer);
        }
        document.querySelectorAll('input[name=metode_bayar]').forEach(function (r) {
            r.addEventListener('change', sync);
        });
        sync();

        var btn = document.getElementById('btn-salin');
        if (btn) {
            btn.addEventListener('click', function () {
                var nomor = (btn.getAttribute('data-nomor') || '').replace(/\s+/g, '');
                navigator.clipboard.writeText(nomor).then(function () {
                    var label = document.getElementById('salin-label');
                    label.textContent = 'Tersalin';
                    setTimeout(function () { label.textContent = 'Salin'; }, 1800);
                });
            });
        }
    })();
</script>
@endpush
@endsection
