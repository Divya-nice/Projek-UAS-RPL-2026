@extends('layouts.app')

@section('title', 'Riwayat Pesanan — Cuci Sepatu PTK')

@section('content')
<section class="min-h-[70vh] bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="animate-[fadeInUp_0.5s_ease-out]">
            <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">
                Riwayat Pesanan
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Lihat status dan riwayat pesanan layanan cuci sepatu Anda.
            </p>
        </div>

        @if($riwayat->count() > 0)
            <div class="mt-8 space-y-4">

                @foreach($riwayat as $item)

                    @php
                        $badge = match($item->status) {
                            'Selesai' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
                            'Diproses' => 'bg-sky-50 text-sky-600 ring-sky-100',
                            default => 'bg-amber-50 text-amber-600 ring-amber-100',
                        };
                    @endphp

                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition hover:shadow-md sm:p-6">

                        <div class="flex flex-wrap items-start justify-between gap-3">

                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-[#1566AD]">
                                        {{ $item->nomor_pesanan }}
                                    </span>

                                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold ring-1 {{ $badge }}">
                                        {{ $item->status }}
                                    </span>
                                </div>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <span class="text-lg font-bold text-[#1E293B]">
                                {{ \App\Http\Controllers\PesananController::rupiah($item->total_biaya) }}
                            </span>

                        </div>

                        <div class="mt-4 grid gap-3 border-t border-slate-100 pt-4 sm:grid-cols-3">

                            <div>
                                <p class="text-xs text-slate-400">
                                    Jenis Layanan
                                </p>

                                <p class="mt-0.5 text-sm font-medium text-[#1E293B]">
                                    {{ $item->layanan->nama_layanan }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">
                                    Jumlah Sepatu
                                </p>

                                <p class="mt-0.5 text-sm font-medium text-[#1E293B]">
                                    {{ $item->jumlah_sepatu }} Pasang
                                </p>
                            </div>

                            <div class="sm:text-right">
                                <a href="{{ route('pesanan.katalog') }}"
                                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1E7BC8] hover:underline">
                                    Pesan Lagi
                                    <x-icon name="arrow-right" class="h-4 w-4"/>
                                </a>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="mt-8 rounded-2xl bg-white p-12 text-center shadow-sm ring-1 ring-slate-100">

                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EAF3FC] text-[#1E7BC8]">
                    <x-icon name="clipboard" class="h-8 w-8"/>
                </span>

                <h2 class="mt-5 text-lg font-bold text-[#1E293B']">
                    Belum Ada Riwayat
                </h2>

                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                    Anda belum pernah melakukan pemesanan. Yuk pesan layanan pertama Anda!
                </p>

                <a href="{{ route('pesanan.katalog') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-95">
                    Pesan Sekarang
                    <x-icon name="arrow-right" class="h-4 w-4"/>
                </a>

            </div>

        @endif

    </div>
</section>
@endsection