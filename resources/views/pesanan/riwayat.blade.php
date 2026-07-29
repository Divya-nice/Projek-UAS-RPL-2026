@extends('layouts.app')

@section('title', 'Riwayat Pesanan — Cuci Sepatu PTK')

@section('content')
<section class="min-h-[70vh] bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="animate-[fadeInUp_0.5s_ease-out]">
            <h1 class="text-3xl font-bold text-[#1E293B]">Riwayat Pesanan</h1>
            <p class="mt-1 text-sm text-slate-500">
                Lihat status dan riwayat seluruh pesanan layanan Anda.
            </p>
        </div>

        @if(session('sukses'))
            <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0"/>
                <span>{{ session('sukses') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mt-6 flex items-start gap-2.5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                <x-icon name="clock" class="mt-0.5 h-5 w-5 shrink-0"/>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('pesanan.riwayat') }}" class="relative mt-6">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
            </span>

            <input id="cari-pesanan"
                   type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nomor pesanan atau layanan..."
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-12 pr-4 text-sm shadow-sm focus:border-[#1E7BC8] focus:ring-2 focus:ring-[#1E7BC8]/20">
        </form>

        @if($riwayat->count())

            <div id="daftar-pesanan" class="mt-6 space-y-4">

                @foreach($riwayat as $item)

                    @php
                        $badge = match($item->status) {
                            'Selesai' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                            'Diproses', 'Dicuci', 'Dikeringkan', 'Siap Diambil' => 'bg-sky-50 text-sky-600 ring-sky-100',
                            'Dibatalkan' => 'bg-rose-50 text-rose-600 ring-rose-100',
                            default => 'bg-amber-50 text-amber-600 ring-amber-100',
                        };

                        $kodeUrl = ltrim($item->nomor_pesanan,'#');
                        $mb = $item->metode_bayar;
                        $adaNota = in_array($item->status,['Diproses','Dicuci', 'Dikeringkan', 'Siap Diambil', 'Selesai']);
                    @endphp

                    <div class="kartu-pesanan rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 hover:shadow-md"
                        data-cari="{{ strtolower($item->nomor_pesanan.' '.$item->layanan->nama_layanan) }}">

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-4">

                            <div class="min-w-[160px]">
                                <p class="font-semibold text-[#1566AD]">
                                    #{{ $item->nomor_pesanan }}
                                </p>

                                <span class="mt-2 inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $badge }}">
                                    {{ $item->status }}
                                </span>
                            </div>

                            <div class="grid flex-1 grid-cols-2 gap-4 sm:grid-cols-4">

                                <div>
                                    <p class="text-xs text-slate-400">Layanan</p>
                                    <p class="font-semibold">
                                        {{ $item->layanan->nama_layanan }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">Tanggal</p>
                                    <p class="font-semibold">
                                        {{ $item->created_at->format('d M Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">Jumlah</p>
                                    <p class="font-semibold">
                                        {{ $item->jumlah_sepatu }} Pasang
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-slate-400">Pengantaran</p>
                                    <p class="font-semibold">
                                        {{ ucfirst($item->metode_pengantaran) }}
                                    </p>
                                </div>

                            </div>

                            <div class="text-right">
                                <p class="text-lg font-bold text-[#1566AD]">
                                    {{ \App\Http\Controllers\PesananController::rupiah($item->total_biaya) }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2">

                                @if($item->status == 'Menunggu Pembayaran')

                                    @if($mb == 'transfer')

                                        <a href="{{ route('pesanan.bayar',['kode'=>$kodeUrl]) }}"
                                           class="rounded-lg bg-emerald-500 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-emerald-600">
                                            Lakukan Pembayaran
                                        </a>

                                    @endif

                                    <form method="POST"
                                          action="{{ route('pesanan.batalkan',['kode'=>$kodeUrl]) }}">
                                        @csrf

                                        <button
                                            onclick="return confirm('Batalkan pesanan?')"
                                            class="w-full rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-500 hover:bg-rose-50">
                                            Batalkan
                                        </button>
                                    </form>

                                @elseif($item->status == 'Menunggu Verifikasi')

                                    <span class="rounded-lg bg-amber-100 px-4 py-2 text-center text-xs font-semibold text-amber-700">
                                        Menunggu Verifikasi
                                    </span>

                                @elseif(in_array($item->status,['Diproses', 'Dicuci', 'Dikeringkan', 'Siap Diambil', 'Selesai']))

                                    <a href="{{ route('pesanan.nota',['kode'=>$kodeUrl]) }}"
                                       class="rounded-lg bg-[#1566AD] px-4 py-2 text-center text-sm font-semibold text-white">
                                        Lihat Nota
                                    </a>

                                @else

                                    <button disabled
                                        class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-2 text-sm text-rose-400">
                                        Pesanan Dibatalkan
                                    </button>

                                @endif

                            </div>

                        </div>

                        @unless($adaNota)
                            <div class="mt-4 border-t border-dashed pt-3">
                                <p class="text-xs text-slate-400">
                                    Nota belum tersedia.
                                </p>
                            </div>
                        @endunless

                    </div>

                @endforeach

            </div>

            <p class="mt-6 text-center text-xs text-slate-400">
                Menampilkan {{ $riwayat->firstItem() }} - {{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} pesanan
            </p>

            <div class="mt-4 flex justify-center">
                {{ $riwayat->onEachSide(1)->links() }}
            </div>

        @elseif(request('search'))

            <div class="mt-8 rounded-2xl bg-white p-12 text-center shadow-sm">
                <h2 class="text-lg font-bold">Tidak Ditemukan</h2>

                <p class="mt-2 text-sm text-slate-500">
                    Tidak ada pesanan yang cocok dengan pencarian "{{ request('search') }}".
                </p>

                <a href="{{ route('pesanan.riwayat') }}"
                   class="mt-5 inline-flex rounded-lg bg-[#1566AD] px-5 py-3 font-semibold text-white">
                    Reset Pencarian
                </a>
            </div>

        @else

            <div class="mt-8 rounded-2xl bg-white p-12 text-center shadow-sm">
                <h2 class="text-lg font-bold">Belum Ada Riwayat</h2>

                <p class="mt-2 text-sm text-slate-500">
                    Anda belum pernah melakukan pemesanan.
                </p>

                <a href="{{ route('pesanan.katalog') }}"
                   class="mt-5 inline-flex rounded-lg bg-[#1566AD] px-5 py-3 font-semibold text-white">
                    Pesan Sekarang
                </a>
            </div>

        @endif

    </div>
</section>

@endsection