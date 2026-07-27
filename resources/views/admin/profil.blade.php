@extends('layouts.admin')

@section('title', 'Edit Profil')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    {{-- Judul --}}
    <div class="border-b border-slate-200 pb-5">
        <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Edit Profil</h1>
        <p class="mt-1 text-slate-500">Perbarui informasi akun pemilik usaha</p>
    </div>

    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf

        {{-- Foto profil --}}
        <div class="flex flex-col items-center gap-3 border-b border-slate-100 pb-6 sm:flex-row sm:gap-5">
            <span class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-800 text-white">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            </span>
            <div class="text-center sm:text-left">
                <p class="font-semibold text-slate-800">Foto Profil</p>
                <p class="text-sm text-slate-400">Format JPG atau PNG, maks. 2MB</p>
                <label class="mt-2 inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    Ubah Foto
                    <input type="file" name="foto" accept="image/*" class="hidden" />
                </label>
            </div>
        </div>

        <div class="mt-6 space-y-5">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input type="text" name="nama" value="Bapak Rudi" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="admin@cucisepatuptk.id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">No. Telepon</label>
                <input type="text" name="telepon" value="0812-3456-7890" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-medium text-slate-700">Alamat Usaha</label>
                <textarea name="alamat" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20">Jl. Ahmad Yani No. 12, Pontianak</textarea>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E7BC8] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#1566AD]">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" /></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection
