@extends('layouts.app')

@section('title', 'Akun Saya — Cuci Sepatu PTK')

@section('content')
<section class="min-h-[70vh] bg-[#F2F7FD] py-12 lg:py-16">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="animate-[fadeInUp_0.5s_ease-out]">
            <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Akun Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola informasi akun Anda.</p>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            {{-- Kartu profil --}}
            <div class="flex flex-col items-center rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-100">

    <span id="preview-foto"
        class="flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-[#EAF3FC] text-[#1E7BC8] ring-4 ring-white">

        <x-icon name="user" class="h-14 w-14" />

    </span>

    <h2 class="mt-4 text-lg font-bold text-[#1E293B]">
        {{ $user['nama'] }}
    </h2>

    <a href="mailto:{{ $user['email'] }}"
        class="mt-1 text-sm text-[#1E7BC8] hover:underline">
        {{ $user['email'] }}
    </a>

    <button
        type="button"
        id="btn-foto"
        class="mt-6 w-full rounded-lg bg-[#0F2A4A] px-4 py-2.5 text-sm font-semibold text-white">
        Ubah Foto Profil
    </button>

</div>
                <input type="file" id="foto-profil" accept="image/*" class="hidden">
                <form action="{{ route('akun.update') }}" method="POST" enctype="multipart/form-data">
                    <input type="file"
                        name="foto"
                        id="foto-profil"
                        accept="image/*"
                        class="hidden">
            </div>

            {{-- Form informasi akun --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 sm:p-8 lg:col-span-2">
    
                    @csrf
                    @method('PUT')
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E293B]">Nama Lengkap</label>
                            <input type="text" name="nama" data-field disabled value="{{ $user['nama'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E293B]">Email</label>
                            <input type="email" name="email" data-field disabled value="{{ $user['email'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E293B]">Nomor HP</label>
                            <input type="text" name="telepon" data-field disabled value="{{ $user['telepon'] }}" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400" />
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-[#1E293B]">Password</label>
                            <div class="relative">
                                <input type="password" name="password" data-field disabled value="password" data-password class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 pr-10 text-sm text-slate-700 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400" />
                                <button type="button" data-toggle-password class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-[#1E7BC8]" aria-label="Lihat password">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-[#1E293B]">Alamat</label>
                        <textarea rows="3" name="alamat" data-field disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-700 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">{{ $user['alamat'] }}</textarea>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-1">
                        <button type="button" id="btn-ubah-data" class="rounded-lg border border-[#1E7BC8] px-5 py-2.5 text-sm font-semibold text-[#1566AD] transition hover:bg-[#EAF3FC]">Ubah Data</button>
                        <button type="submit" id="btn-simpan" class="hidden rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-95">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Keluar --}}
        <div class="mt-6 flex justify-end rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
            @if(Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-500 transition hover:bg-red-50">
                        <x-icon name="arrow-right" class="h-4 w-4" /> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-500 transition hover:bg-red-50">
                    <x-icon name="arrow-right" class="h-4 w-4" /> Keluar
                </a>
            @endif
        </div>
    </div>
</section>

<script>
    // Lihat / sembunyikan password
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.querySelector('[data-password]');
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        });
    });

    // Tombol Ubah Data
    (function () {
        var btnUbah = document.getElementById('btn-ubah-data');
        var btnSimpan = document.getElementById('btn-simpan');

        if (btnUbah) {
            btnUbah.addEventListener('click', function () {
                document.querySelectorAll('[data-field]').forEach(function (el) {
                    el.disabled = false;
                });

                btnUbah.classList.add('hidden');

                if (btnSimpan) {
                    btnSimpan.classList.remove('hidden');
                }

                var first = document.querySelector('[data-field]');
                if (first) {
                    first.focus();
                }
            });
        }
    })();

    // Upload Foto Profil
    const btnFoto = document.getElementById('btn-foto');
    const inputFoto = document.getElementById('foto-profil');
    const preview = document.getElementById('preview-foto');

    if (btnFoto && inputFoto && preview) {
        btnFoto.addEventListener('click', function () {
            inputFoto.click();
        });

        inputFoto.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.innerHTML =
                    '<img src="' + e.target.result + '" class="h-full w-full object-cover" alt="Foto Profil">';
            };

            reader.readAsDataURL(file);
        });
    }
</script>