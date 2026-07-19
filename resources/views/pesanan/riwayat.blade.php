@extends('layouts.app')

@section('title', 'Riwayat Pesanan — Cuci Sepatu PTK')

@section('content')
<section class="min-h-[70vh] bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="animate-[fadeInUp_0.5s_ease-out]">
            <h1 class="text-3xl font-bold text-[#1E293B]">Riwayat Pesanan</h1>
            <p class="mt-1 text-sm text-slate-500">Lihat status dan riwayat seluruh pesanan layanan Anda.</p>
        </div>

        @if(session('sukses'))
            <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0" /> <span>{{ session('sukses') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                <x-icon name="clock" class="mt-0.5 h-5 w-5 shrink-0" /> <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Pencarian --}}
        <div class="relative mt-6">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            </span>
            <input id="cari-pesanan" type="text" placeholder="Cari nomor pesanan atau jenis layanan..."
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
        </div>

        @if(count($riwayat) > 0)
            <div id="daftar-pesanan" class="mt-6 space-y-4">
                @foreach($riwayat as $item)
                    @php
                        $badge = match($item['status']) {
                            'Selesai'    => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                            'Diproses'   => 'bg-sky-50 text-sky-600 ring-sky-100',
                            'Dibatalkan' => 'bg-rose-50 text-rose-600 ring-rose-100',
                            default      => 'bg-amber-50 text-amber-600 ring-amber-100',
                        };
                        $kodeUrl = ltrim($item['kode'], '#');
                        $mb = $item['metode_bayar'] ?? null;
                        $adaNota = in_array($item['status'], ['Diproses', 'Selesai'], true);
                    @endphp
                    <div class="kartu-pesanan rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md"
                         data-cari="{{ strtolower($item['kode'] . ' ' . $item['layanan']) }}">
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-4">
                            <div class="min-w-[150px]">
                                <p class="font-semibold text-[#1566AD]">{{ $item['kode'] }}</p>
                                <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 {{ $badge }}"><span class="h-1.5 w-1.5 rounded-full bg-current"></span> {{ $item['status'] }}</span>
                            </div>

                            <div class="grid flex-1 grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-4">
                                <div><p class="text-[11px] text-slate-400">Layanan</p><p class="mt-0.5 text-sm font-semibold text-[#1E293B]">{{ $item['layanan'] }}</p></div>
                                <div><p class="text-[11px] text-slate-400">Tanggal</p><p class="mt-0.5 text-sm font-semibold text-[#1E293B]">{{ $item['tanggal'] }}</p></div>
                                <div><p class="text-[11px] text-slate-400">Jumlah</p><p class="mt-0.5 text-sm font-semibold text-[#1E293B]">{{ $item['jumlah'] }} Pasang</p></div>
                                <div><p class="text-[11px] text-slate-400">Metode</p><p class="mt-0.5 text-sm font-semibold text-[#1E293B]">{{ $item['pengiriman'] ?? '-' }}</p></div>
                            </div>

                            <div class="text-right">
                                <p class="text-lg font-bold text-[#1E293B]">{{ \App\Http\Controllers\PesananController::rupiah($item['total']) }}</p>
                            </div>

                            <div class="flex flex-col items-stretch gap-2">
                                @switch($item['status'])
                                    @case('Menunggu Pembayaran')
                                        @if($mb === 'transfer')
                                            <a href="{{ route('pesanan.bayar', ['kode' => $kodeUrl]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600"><x-icon name="wallet" class="h-4 w-4" /> Lakukan Pembayaran</a>
                                        @endif
                                        <form method="POST" action="{{ route('pesanan.batalkan', ['kode' => $kodeUrl]) }}" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-500 transition hover:bg-rose-50">Batalkan Pesanan</button>
                                        </form>
                                        @break
                                    @case('Menunggu Verifikasi')
                                        <span class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-amber-50 px-4 py-2 text-xs font-medium text-amber-600 ring-1 ring-amber-100"><x-icon name="clock" class="h-4 w-4" /> Menunggu Verifikasi</span>
                                        @break
                                    @case('Diproses')
                                    @case('Selesai')
                                        <a href="{{ route('pesanan.nota', ['kode' => $kodeUrl]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0F2A4A] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1566AD]"><x-icon name="clipboard" class="h-4 w-4" /> Lihat Nota</a>
                                        @break
                                    @case('Dibatalkan')
                                        <button type="button" disabled class="inline-flex cursor-not-allowed items-center justify-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-400">Pesanan Dibatalkan</button>
                                        @break
                                @endswitch
                            </div>
                        </div>

                        @unless($adaNota)
                            <div class="mt-4 border-t border-dashed border-slate-100 pt-3">
                                <p class="text-xs text-slate-400">Nota Belum Tersedia</p>
                            </div>
                        @endunless
                    </div>
                @endforeach
            </div>

            <p id="info-jumlah" class="mt-6 text-center text-xs text-slate-400">Menampilkan {{ count($riwayat) }} dari {{ count($riwayat) }} riwayat pesanan</p>
            <p id="kosong-cari" class="mt-6 hidden text-center text-sm text-slate-400">Tidak ada pesanan yang cocok dengan pencarian Anda.</p>
        @else
            <div class="mt-8 rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-100">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EAF3FC] text-[#1E7BC8]"><x-icon name="clipboard" class="h-8 w-8" /></span>
                <h2 class="mt-5 text-lg font-bold text-[#1E293B]">Belum Ada Riwayat</h2>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Anda belum pernah melakukan pemesanan. Yuk pesan layanan pertama Anda!</p>
                <a href="{{ route('pesanan.katalog') }}" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-95">Pesan Sekarang <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('cari-pesanan');
        if (! input) return;
        var kartu = Array.prototype.slice.call(document.querySelectorAll('.kartu-pesanan'));
        var info = document.getElementById('info-jumlah');
        var kosong = document.getElementById('kosong-cari');
        var total = kartu.length;

        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();
            var tampil = 0;
            kartu.forEach(function (el) {
                var cocok = el.getAttribute('data-cari').indexOf(q) !== -1;
                el.classList.toggle('hidden', ! cocok);
                if (cocok) tampil++;
            });
            info.textContent = 'Menampilkan ' + tampil + ' dari ' + total + ' riwayat pesanan';
            kosong.classList.toggle('hidden', tampil !== 0);
        });
    })();
</script>
@endpush
@endsection