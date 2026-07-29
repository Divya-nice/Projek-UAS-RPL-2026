@extends('layouts.admin')

@section('title', ($mode ?? 'create') === 'edit' ? 'Edit Layanan' : 'Tambah Layanan')

@section('content')
@php $isEdit = ($mode ?? 'create') === 'edit'; @endphp
<div class="mx-auto max-w-3xl space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex flex-wrap items-center gap-1.5 text-sm text-slate-400">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600">Dashboard</a>
        <span>&rsaquo;</span>
        <a href="{{ route('admin.layanan') }}" class="hover:text-slate-600">Kelola Layanan</a>
        <span>&rsaquo;</span>
        <span class="font-medium text-[#1E7BC8]">{{ $isEdit ? 'Edit Layanan' : 'Tambah Layanan' }}</span>
    </nav>

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $isEdit ? 'Edit Layanan' : 'Tambah Layanan' }}</h1>
        <p class="mt-1 text-slate-500">{{ $isEdit ? 'Perbarui informasi layanan yang sudah ada' : 'Tambahkan layanan cuci sepatu baru' }}</p>
    </div>

    <form action="{{ $isEdit ? route('admin.layanan.update', $layanan->id) : route('admin.layanan.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

        <div class="space-y-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Layanan</label>
                <input type="text" name="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan ?? '') }}" placeholder="Contoh: Deep Cleaning" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $layanan->harga ?? '') }}" placeholder="45000" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Estimasi Pengerjaan</label>
                    <input type="text" name="estimasi" value="{{ old('estimasi', $layanan->estimasi ?? '') }}" placeholder="Contoh: 3 Hari" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
<select name="status" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">
    <option value="aktif" {{ old('status', $layanan->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>
        Aktif
    </option>

    <option value="nonaktif" {{ old('status', $layanan->status ?? '') === 'nonaktif' ? 'selected' : '' }}>
        Nonaktif
    </option>
</select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi Layanan</label>
                <textarea name="deskripsi"
          rows="4"
          placeholder="Jelaskan detail layanan..."
          class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">{{ old('deskripsi', $layanan->deskripsi ?? '') }}</textarea>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Gambar Layanan</label>
                <div class="flex items-center justify-center rounded-xl border-2 border-dashed border-slate-200 px-4 py-8 text-center">
                    <div>
                        <div id="preview-gambar-wrapper" class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-slate-100 {{ ($layanan->gambar ?? null) ? '' : 'hidden' }}">
                            <img id="preview-gambar"
                                 src="{{ ($layanan->gambar ?? null) ? asset('storage/' . $layanan->gambar) : '' }}"
                                 alt="Pratinjau gambar layanan"
                                 class="h-full w-full object-cover">
                        </div>
                        <svg id="placeholder-gambar" class="mx-auto h-9 w-9 text-slate-300 {{ ($layanan->gambar ?? null) ? 'hidden' : '' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                        <p class="mt-2 text-sm text-slate-500">Klik untuk unggah gambar</p>
                        <input id="input-gambar" type="file" name="gambar" accept="image/*" class="mt-3 text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-[#EAF3FC] file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-[#1566AD]" />
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.layanan') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E7BC8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1566AD]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" /></svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Layanan' }}
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    (function () {
        const input = document.getElementById('input-gambar');
        const wrapper = document.getElementById('preview-gambar-wrapper');
        const img = document.getElementById('preview-gambar');
        const placeholder = document.getElementById('placeholder-gambar');

        if (!input) return;

        input.addEventListener('change', function () {
            const file = input.files && input.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                img.src = e.target.result;
                wrapper.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });
    })();
</script>
@endpush

@endsection