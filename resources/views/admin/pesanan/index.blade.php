@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="mx-auto max-w-6xl space-y-5">

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Kelola Pesanan</h1>
        <p class="mt-1 text-slate-500">Kelola dan pantau semua pesanan pelanggan</p>
    </div>

    {{-- Filter --}}
<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

    <form method="GET" action="{{ route('admin.pesanan') }}">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.7"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari no. pesanan / nama / layanan"
                    class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
            </div>

            <select
                name="status"
                class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600">

                <option value="">Semua status</option>

                <option value="Diproses"
                    {{ request('status')=='Diproses'?'selected':'' }}>
                    Diproses
                </option>

                <option value="Dicuci"
                    {{ request('status')=='Dicuci'?'selected':'' }}>
                    Dicuci
                </option>

                <option value="Dikeringkan"
                    {{ request('status')=='Dikeringkan'?'selected':'' }}>
                    Dikeringkan
                </option>

                <option value="Siap Diambil"
                    {{ request('status')=='Siap Diambil'?'selected':'' }}>
                    Siap Diambil
                </option>

                <option value="Selesai"
                    {{ request('status')=='Selesai'?'selected':'' }}>
                    Selesai
                </option>

            </select>

            <select
                name="layanan"
                class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600">

                <option value="">Semua Layanan</option>

                @foreach($layananList as $layanan)
                    <option
                        value="{{ $layanan->id }}"
                        {{ request('layanan')==$layanan->id?'selected':'' }}>
                        {{ $layanan->nama_layanan }}
                    </option>
                @endforeach

            </select>

            <select
                name="tanggal"
                class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600">

                <option value="">Semua Tanggal</option>

                <option value="hari_ini"
                    {{ request('tanggal')=='hari_ini'?'selected':'' }}>
                    Hari Ini
                </option>

                <option value="minggu_ini"
                    {{ request('tanggal')=='minggu_ini'?'selected':'' }}>
                    Minggu Ini
                </option>

                <option value="bulan_ini"
                    {{ request('tanggal')=='bulan_ini'?'selected':'' }}>
                    Bulan Ini
                </option>

            </select>

            <button
                type="submit"
                class="rounded-xl bg-[#1E7BC8] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#1769ab]">
                Filter
            </button>

            <a
                href="{{ route('admin.pesanan') }}"
                class="rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Reset
            </a>

        </div>
    </form>

</div>

    {{-- Tabs --}}
    <div class="overflow-x-auto">
        <div class="flex min-w-max items-center gap-6 border-b border-slate-200 px-1">
            <button class="flex items-center gap-2 border-b-2 border-[#1E7BC8] pb-3 text-sm font-semibold text-[#1E7BC8]">
    Semua
    <span class="rounded-full bg-[#1E7BC8] px-2 py-0.5 text-[11px] font-bold text-white">
        {{ $jumlahSemua }}
    </span>
</button>

<button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">
    Diproses
    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-bold text-blue-700">
        {{ $jumlahDiproses }}
    </span>
</button>

<button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">
    Dicuci
    <span class="rounded-full bg-pink-100 px-2 py-0.5 text-[11px] font-bold text-pink-600">
        {{ $jumlahDicuci }}
    </span>
</button>

<button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">
    Dikeringkan
    <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[11px] font-bold text-orange-600">
        {{ $jumlahDikeringkan }}
    </span>
</button>

<button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">
    Siap Diambil
    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-bold text-green-700">
        {{ $jumlahSiapDiambil }}
    </span>
</button>

<button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">
    Selesai
    <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[11px] font-bold text-slate-600">
        {{ $jumlahSelesai }}
    </span>
</button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left text-sm">
                <thead>
                    <tr class="bg-[#EAF3FC] text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-3.5">No. Pesanan</th>
                        <th class="px-5 py-3.5">Pelanggan</th>
                        <th class="px-5 py-3.5">Layanan</th>
                        <th class="px-5 py-3.5">Jumlah</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Total</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

@foreach($pesanan as $item)
<tr class="hover:bg-slate-50/70">

    <td class="px-5 py-4 font-semibold text-slate-700">
        #{{ $item->nomor_pesanan }}
    </td>

    <td class="px-5 py-4">
        <span class="font-medium text-slate-700">
            {{ $item->user->name }}
        </span>

        <span class="block text-xs text-slate-400">
            {{ $item->user->phone ?? '-'}}
        </span>
    </td>

    <td class="px-5 py-4">
        <span class="text-slate-700">
            {{ $item->layanan->nama_layanan }}
        </span>
    </td>

    <td class="px-5 py-4">
        {{ $item->jumlah_sepatu }} pasang
    </td>

    <td class="px-5 py-4">
        {{ $item->created_at->format('d M Y') }}

        <span class="block text-xs text-slate-400">
            {{ $item->created_at->format('H:i') }}
        </span>
    </td>

    <td class="px-5 py-4">
        @php
            $statusClass = match($item->status){
                'Diproses' => 'bg-blue-100 text-blue-700',
                'Dicuci' => 'bg-pink-100 text-pink-700',
                'Dikeringkan' => 'bg-orange-100 text-orange-700',
                'Siap Diambil' => 'bg-green-100 text-green-700',
                'Selesai' => 'bg-slate-200 text-slate-700',
                default => 'bg-slate-100 text-slate-600',
            };
        @endphp

        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
            {{ $item->status }}
        </span>
    </td>

    <td class="px-5 py-4 font-semibold">
        Rp{{ number_format($item->total_biaya,0,',','.') }}
    </td>

    <td class="px-5 py-4 text-center">
        <a
            href="{{ route('admin.pesanan.detail', ['kode' => $item->nomor_pesanan]) }}"
            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
            Lihat Detail
        </a>
    </td>

</tr>
@endforeach

</tbody>
            </table>
        </div>
            <div class="flex items-center justify-between border-t border-slate-100 px-5 py-4">

                <p class="text-sm text-slate-500">
                    Menampilkan
                    {{ $pesanan->firstItem() }}
                    -
                    {{ $pesanan->lastItem() }}
                    dari
                    {{ $pesanan->total() }}
                    pesanan
                </p>

                {{ $pesanan->links() }}

            </div>
    </div>

</div>
@endsection
