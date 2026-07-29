@extends('layouts.admin')

@section('title', 'Laporan Pendapatan')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Judul + kontrol --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Laporan Pendapatan</h1>
            <p class="mt-1 text-slate-500">Ringkasan seluruh transaksi yang telah selesai.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                {{ $semuaTransaksi->count() > 0
    ? $semuaTransaksi->last()->created_at->format('d M Y') . ' - ' .
      $semuaTransaksi->first()->created_at->format('d M Y')
    : 'Belum ada transaksi'
}}
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </button>
        </div>
    </div>

    {{-- Kartu ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        {{-- Total Pendapatan --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3M3.75 19.5h16.5A2.25 2.25 0 0 0 22.5 17.25V6.75A2.25 2.25 0 0 0 20.25 4.5H3.75A2.25 2.25 0 0 0 1.5 6.75v10.5A2.25 2.25 0 0 0 3.75 19.5Z" /></svg>
                </span>
                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                Semua waktu
</span>
            </div>
            <p class="mt-4 text-sm text-slate-500">Total Pendapatan</p>
           <p class="mt-1 text-2xl font-bold text-slate-900">
    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
</p>
            <p class="mt-1 text-xs text-slate-400">
    {{ $totalTransaksi }} transaksi selesai
</p>
        </div>
        {{-- Total Transaksi --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1566AD]">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
            </span>
            <p class="mt-4 text-sm text-slate-500">Total Transaksi</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">
    {{ $totalTransaksi }}
</p>
            <p class="mt-1 text-xs text-slate-400">Transaksi selesai</p>
        </div>
        {{-- Rata-rata Nilai Transaksi --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
            </span>
            <p class="mt-4 text-sm text-slate-500">Rata-rata Nilai Transaksi</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">
    Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}
</p>
            <p class="mt-1 text-xs text-slate-400">Per transaksi</p>
        </div>
    </div>

    {{-- Grafik + Ringkasan Periode --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-bold text-slate-900">Grafik Pendapatan</h2>
            <div class="inline-flex rounded-lg bg-slate-100 p-1 text-sm">
                <button type="button" data-chart-tab="harian" class="chart-tab rounded-md bg-white px-3 py-1.5 font-medium text-slate-900 shadow-sm">Harian</button>
                <button type="button" data-chart-tab="mingguan" class="chart-tab rounded-md px-3 py-1.5 font-medium text-slate-500">Mingguan</button>
                <button type="button" data-chart-tab="bulanan" class="chart-tab rounded-md px-3 py-1.5 font-medium text-slate-500">Bulanan</button>
            </div>
        </div>

        <div class="mt-5 grid gap-6 lg:grid-cols-3">
            {{-- Chart --}}
            <div class="lg:col-span-2">
                @php
                    // Mengubah kumpulan [label, total] menjadi titik-titik SVG
                    // (x,y) di dalam area chart (x: 20-480, y: 50-200), tanpa
                    // mengubah ukuran/gaya kanvas aslinya. Data totalnya benar-benar
                    // berasal dari database (lihat AdminController@laporan).
                    $buatTitik = function ($dataset) {
                        $jumlah = $dataset->count();
                        $max = $dataset->max('total');
                        $max = $max > 0 ? $max : 1;

                        $step = $jumlah > 1 ? (480 - 20) / ($jumlah - 1) : 0;

                        return $dataset->values()->map(function ($item, $i) use ($step, $max) {
                            $x = 20 + ($step * $i);
                            $y = 200 - (($item['total'] / $max) * 150);

                            return [
                                'x' => round($x, 1),
                                'y' => round($y, 1),
                                'label' => $item['label'],
                            ];
                        });
                    };

                    $titikHarian = $buatTitik($chartHarian);
                    $titikMingguan = $buatTitik($chartMingguan);
                    $titikBulanan = $buatTitik($chartBulanan);

                    $keGaris = fn ($titik) => $titik->map(fn ($t) => "{$t['x']},{$t['y']}")->implode(' ');
                    $keArea = fn ($titik) => 'M' . $keGaris($titik) . " L480,200 L20,200 Z";
                @endphp
                <svg viewBox="0 0 500 220" class="h-64 w-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#1E7BC8" stop-opacity="0.18" />
                            <stop offset="100%" stop-color="#1E7BC8" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    {{-- garis grid --}}
                    <line x1="20" y1="50"  x2="480" y2="50"  stroke="#F1F5F9" stroke-width="1" />
                    <line x1="20" y1="100" x2="480" y2="100" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="20" y1="150" x2="480" y2="150" stroke="#F1F5F9" stroke-width="1" />
                    <line x1="20" y1="200" x2="480" y2="200" stroke="#E2E8F0" stroke-width="1" />

                    {{-- Harian --}}
                    <g data-chart="harian">
                        <path d="{{ $keArea($titikHarian) }}" fill="url(#areaGrad)" />
                        <polyline points="{{ $keGaris($titikHarian) }}" fill="none" stroke="#1E7BC8" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" />
                        @foreach ($titikHarian as $t)
                            <text x="{{ $t['x'] }}" y="215" font-size="11" fill="#94A3B8" text-anchor="middle">{{ $t['label'] }}</text>
                        @endforeach
                    </g>

                    {{-- Mingguan --}}
                    <g data-chart="mingguan" class="hidden">
                        <path d="{{ $keArea($titikMingguan) }}" fill="url(#areaGrad)" />
                        <polyline points="{{ $keGaris($titikMingguan) }}" fill="none" stroke="#1E7BC8" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" />
                        @foreach ($titikMingguan as $t)
                            <text x="{{ $t['x'] }}" y="215" font-size="11" fill="#94A3B8" text-anchor="middle">{{ $t['label'] }}</text>
                        @endforeach
                    </g>

                    {{-- Bulanan --}}
                    <g data-chart="bulanan" class="hidden">
                        <path d="{{ $keArea($titikBulanan) }}" fill="url(#areaGrad)" />
                        <polyline points="{{ $keGaris($titikBulanan) }}" fill="none" stroke="#1E7BC8" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round" />
                        @foreach ($titikBulanan as $t)
                            <text x="{{ $t['x'] }}" y="215" font-size="11" fill="#94A3B8" text-anchor="middle">{{ $t['label'] }}</text>
                        @endforeach
                    </g>
                </svg>
            </div>

            {{-- Ringkasan Periode --}}
            <div class="rounded-xl bg-slate-50 p-5">
    <p class="text-sm font-semibold text-slate-700">
        Ringkasan Periode
    </p>

    <div class="mt-4 space-y-3 text-sm">

        <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500">Total Pendapatan</span>
            <span class="shrink-0 font-semibold text-slate-800">
                Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
            </span>
        </div>

        <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500">Total Transaksi</span>
            <span class="shrink-0 font-semibold text-slate-800">
                {{ $totalTransaksi }}
            </span>
        </div>

        <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500">Rata-rata</span>
            <span class="shrink-0 font-semibold text-slate-800">
                Rp{{ number_format($rataRataTransaksi, 0, ',', '.') }}
            </span>
        </div>

        <div class="flex items-center justify-between gap-3">
            <span class="text-slate-500">Total Layanan Terjual</span>
            <span class="shrink-0 font-semibold text-slate-800">
                {{ $totalLayananTerjual }} Item
            </span>
        </div>

    </div>


    <div class="mt-4 border-t border-slate-200 pt-4">
        <p class="flex items-center gap-2 text-xs font-medium text-slate-400"><span class="h-2 w-2 rounded-full bg-[#1E7BC8]"></span> Layanan Terlaris</p>
        <p class="mt-1 font-semibold text-slate-800">{{ $namaLayananTerlaris }}</p>
        <p class="text-sm text-slate-500">{{ $jumlahLayananTerlaris }} Transaksi ({{ $persentaseLayananTerlaris }}%)</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel transaksi selesai --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-bold text-slate-900">Daftar Transaksi Selesai</h2>
            <form method="GET" action="{{ route('admin.laporan') }}" class="flex items-center gap-3">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pesanan atau nama pelanggan" class="w-full rounded-lg border border-slate-200 py-2 pl-9 pr-3 text-sm text-slate-600 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20 sm:w-72" />
                </div>
                <select name="tanggal" onchange="this.form.submit()" class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
                    <option value="semua" {{ request('tanggal','semua')=='semua'?'selected':'' }}>Semua Tanggal</option>
                    <option value="hari_ini" {{ request('tanggal')=='hari_ini'?'selected':'' }}>Hari Ini</option>
                    <option value="minggu_ini" {{ request('tanggal')=='minggu_ini'?'selected':'' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('tanggal')=='bulan_ini'?'selected':'' }}>Bulan Ini</option>
                </select>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','tanggal']))
                    <a href="{{ route('admin.laporan') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead>
                    <tr class="bg-[#EAF3FC] text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-3.5">No. Pesanan</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Nama Pelanggan</th>
                        <th class="px-5 py-3.5">Layanan</th>
                        <th class="px-5 py-3.5">Pembayaran</th>
                        <th class="px-5 py-3.5">Total</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

    @forelse ($transaksi as $t)

        <tr class="hover:bg-slate-50/70">

            <td class="px-5 py-4 font-semibold text-[#1E7BC8]">
                #{{ $t->nomor_pesanan }}
            </td>

            <td class="px-5 py-4 text-slate-500">
                {{ $t->created_at->format('d M Y') }}
            </td>

            <td class="px-5 py-4 font-medium text-slate-700">
                {{ $t->user->name }}
            </td>

            <td class="px-5 py-4 text-slate-500">
                {{ $t->layanan->nama_layanan }}
            </td>

            <td class="px-5 py-4 text-slate-500">
                {{ $t->metode_bayar ?? '-' }}
            </td>

            <td class="px-5 py-4 font-semibold text-slate-700">
                Rp{{ number_format($t->total_biaya, 0, ',', '.') }}
            </td>

            <td class="px-5 py-4 text-center">
                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    Selesai
                </span>
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                Belum ada transaksi yang cocok.
            </td>
        </tr>

    @endforelse

</tbody>
            </table>
        </div>
        {{-- Footer pagination --}}
        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                @if($transaksi->total() > 0)
                    Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
                @else
                    Tidak ada transaksi
                @endif
            </p>
            {{ $transaksi->onEachSide(1)->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    (function () {
        const tabs = document.querySelectorAll('[data-chart-tab]');
        const groups = document.querySelectorAll('[data-chart]');
        tabs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = btn.getAttribute('data-chart-tab');
                tabs.forEach(function (b) {
                    const on = b === btn;
                    b.classList.toggle('bg-white', on);
                    b.classList.toggle('shadow-sm', on);
                    b.classList.toggle('text-slate-900', on);
                    b.classList.toggle('text-slate-500', !on);
                });
                groups.forEach(function (g) {
                    g.classList.toggle('hidden', g.getAttribute('data-chart') !== key);
                });
            });
        });
    })();
</script>
@endpush

@endsection