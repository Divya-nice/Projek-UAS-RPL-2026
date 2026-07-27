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
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" placeholder="Cari no. pesanan / nama / layanan" class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" />
            </div>
            <select class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
                <option>Semua status</option>
                <option>Diproses</option>
                <option>Dicuci</option>
                <option>Dikeringkan</option>
                <option>Siap Diambil</option>
                <option>Selesai</option>
            </select>
            <select class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
                <option>Semua Layanan</option>
                <option>Deep Cleaning</option>
                <option>Regular Cleaning</option>
                <option>Unyellowing</option>
                <option>Repaint</option>
            </select>
            <select class="rounded-xl border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
                <option>Semua Tanggal</option>
                <option>Hari Ini</option>
                <option>Minggu Ini</option>
                <option>Bulan Ini</option>
            </select>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="overflow-x-auto">
        <div class="flex min-w-max items-center gap-6 border-b border-slate-200 px-1">
            <button class="flex items-center gap-2 border-b-2 border-[#1E7BC8] pb-3 text-sm font-semibold text-[#1E7BC8]">Semua <span class="rounded-full bg-[#1E7BC8] px-2 py-0.5 text-[11px] font-bold text-white">12</span></button>
            <button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">Diproses <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-bold text-blue-700">4</span></button>
            <button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">Dicuci <span class="rounded-full bg-pink-100 px-2 py-0.5 text-[11px] font-bold text-pink-600">3</span></button>
            <button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">Dikeringkan <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[11px] font-bold text-orange-600">2</span></button>
            <button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">Siap Diambil <span class="rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-bold text-green-700">3</span></button>
            <button class="flex items-center gap-2 border-b-2 border-transparent pb-3 text-sm font-medium text-slate-500 hover:text-slate-700">Selesai <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[11px] font-bold text-slate-600">3</span></button>
        </div>
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
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4 font-semibold text-slate-700">#PTK240520128</td>
                        <td class="px-5 py-4"><span class="font-medium text-slate-700">Nabila</span><span class="block text-xs text-slate-400">0812-3456-7890</span></td>
                        <td class="px-5 py-4"><span class="text-slate-700">Deep Cleaning</span><span class="block text-xs text-slate-400">1 pasang</span></td>
                        <td class="px-5 py-4 text-slate-600">1 pasang</td>
                        <td class="px-5 py-4 text-slate-500">20 Mei 2024<span class="block text-xs text-slate-400">10:30</span></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Diproses</span></td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Rp45.000</td>
                        <td class="px-5 py-4 text-center"><a href="{{ route('admin.pesanan.detail') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>Lihat Detail</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4 font-semibold text-slate-700">#PTK240520127</td>
                        <td class="px-5 py-4"><span class="font-medium text-slate-700">Siti Aisyah</span><span class="block text-xs text-slate-400">0813-2222-1111</span></td>
                        <td class="px-5 py-4"><span class="text-slate-700">Regular Cleaning</span><span class="block text-xs text-slate-400">1 pasang</span></td>
                        <td class="px-5 py-4 text-slate-600">1 pasang</td>
                        <td class="px-5 py-4 text-slate-500">20 Mei 2024<span class="block text-xs text-slate-400">09:15</span></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Diproses</span></td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Rp60.000</td>
                        <td class="px-5 py-4 text-center"><a href="{{ route('admin.pesanan.detail') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>Lihat Detail</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4 font-semibold text-slate-700">#PTK240520126</td>
                        <td class="px-5 py-4"><span class="font-medium text-slate-700">Dimas Putra</span><span class="block text-xs text-slate-400">0812-9876-5432</span></td>
                        <td class="px-5 py-4"><span class="text-slate-700">Unyellowing</span><span class="block text-xs text-slate-400">1 pasang</span></td>
                        <td class="px-5 py-4 text-slate-600">1 pasang</td>
                        <td class="px-5 py-4 text-slate-500">19 Mei 2024<span class="block text-xs text-slate-400">16:45</span></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full bg-pink-100 px-3 py-1 text-xs font-semibold text-pink-600">Dicuci</span></td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Rp75.000</td>
                        <td class="px-5 py-4 text-center"><a href="{{ route('admin.pesanan.detail') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>Lihat Detail</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4 font-semibold text-slate-700">#PTK240520125</td>
                        <td class="px-5 py-4"><span class="font-medium text-slate-700">Nabila Putri</span><span class="block text-xs text-slate-400">0813-6666-7777</span></td>
                        <td class="px-5 py-4"><span class="text-slate-700">Deep Cleaning</span><span class="block text-xs text-slate-400">2 pasang</span></td>
                        <td class="px-5 py-4 text-slate-600">1 pasang</td>
                        <td class="px-5 py-4 text-slate-500">19 Mei 2024<span class="block text-xs text-slate-400">14:20</span></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-600">Dikeringkan</span></td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Rp55.000</td>
                        <td class="px-5 py-4 text-center"><a href="{{ route('admin.pesanan.detail') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>Lihat Detail</a></td>
                    </tr>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-4 font-semibold text-slate-700">#PTK240520124</td>
                        <td class="px-5 py-4"><span class="font-medium text-slate-700">Andi Wijaya</span><span class="block text-xs text-slate-400">0812-1234-5678</span></td>
                        <td class="px-5 py-4"><span class="text-slate-700">Repaint</span><span class="block text-xs text-slate-400">2 pasang</span></td>
                        <td class="px-5 py-4 text-slate-600">2 pasang</td>
                        <td class="px-5 py-4 text-slate-500">19 Mei 2024<span class="block text-xs text-slate-400">11:05</span></td>
                        <td class="px-5 py-4"><span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Siap Diambil</span></td>
                        <td class="px-5 py-4 font-semibold text-slate-700">Rp150.000</td>
                        <td class="px-5 py-4 text-center"><a href="{{ route('admin.pesanan.detail') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>Lihat Detail</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center gap-1 border-t border-slate-100 px-5 py-4 text-sm text-slate-500">
            Menampilkan 1-3 dari 15 pesanan
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
        </div>
    </div>

</div>
@endsection
