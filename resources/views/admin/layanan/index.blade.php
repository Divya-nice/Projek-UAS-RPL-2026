@extends('layouts.admin')

@section('title', 'Kelola Layanan')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Kelola Layanan</h1>
        <p class="mt-1 text-slate-500">Kelola seluruh layanan yang tersedia untuk pelanggan.</p>
    </div>

    {{-- Tombol tambah --}}
    <div class="flex justify-end">
        <a href="{{ route('admin.layanan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E7BC8] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1566AD]">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Layanan
        </a>
    </div>

    {{-- Tabel layanan --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left text-sm">
                <thead>
                    <tr class="bg-[#EAF3FC] text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-5 py-3.5">Layanan</th>
                        <th class="px-5 py-3.5">Harga</th>
                        <th class="px-5 py-3.5">Durasi Pengerjaan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach ($layanan as $item)

                    <tr class="hover:bg-slate-50/70">

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">

                                    @if ($item->gambar)

                                        <img src="{{ asset('storage/' . $item->gambar) }}"
                                             alt="{{ $item->nama_layanan }}"
                                             class="h-full w-full object-cover" />

                                    @else

                                        <span class="text-slate-300">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-6m0 0a1.5 1.5 0 0 0-3 0m3 0V5.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v9.375m14.25-9.375h3.375c.621 0 1.125.504 1.125 1.125v9.375" /></svg>
                                        </span>

                                    @endif

                                </div>

                                <div>
                                    <p class="font-semibold text-slate-700">
                                        {{ $item->nama_layanan }}
                                    </p>

                                    <p class="text-xs text-slate-400">
                                        {{ $item->deskripsi }}
                                    </p>
                                </div>

                            </div>
                        </td>

                        <td class="px-5 py-4 font-semibold text-slate-700">
                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            <span class="inline-flex items-center gap-1.5">

                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>

                                {{ $item->estimasi }}

                            </span>
                        </td>

                        <td class="px-5 py-4 text-slate-600">
    ...
</td>

<td class="px-5 py-4">
    @if ($item->status === 'aktif')
        <span class="font-semibold text-green-600">
            Aktif
        </span>
    @else
        <span class="font-semibold text-red-500">
            Nonaktif
        </span>
    @endif
</td>

                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">

                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.layanan.edit', $item->id) }}"
                                   title="Edit layanan"
                                   class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:bg-slate-50">

                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>

                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.layanan.destroy', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus layanan {{ $item->nama_layanan }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            title="Hapus layanan"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 text-red-500 transition hover:bg-red-50">

                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>

                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection