@extends('layouts.app')

@section('title', 'Masuk — Cuci Sepatu')
@section('authImage', asset('images/auth-hero.png'))

@section('content')
    {{-- Logo & judul --}}
    <div class="mb-8 flex flex-col items-center text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Cuci Sepatu"
             class="mb-6 h-14 w-auto" onerror="this.style.display='none'" />
        <h1 class="text-2xl font-bold text-[#1E293B]">Selamat Datang</h1>
        <p class="mt-1.5 text-sm text-slate-400">masuk untuk melanjutkan perjalanan anda</p>
    </div>

    {{-- Form login: action ke /login (ganti ke route('login') bila route sudah tersedia) --}}
    <form method="POST" action="{{ url('/login') }}" class="space-y-4">
        @csrf

        <x-auth-input name="email" label="Email" type="email"
                      icon="mail" placeholder="name@university.edu" autocomplete="email" />

        <x-auth-input name="password" label="Password" type="password"
                      icon="lock" placeholder="••••••••" autocomplete="current-password" :password="true" />

        <button type="submit"
            class="mt-2 w-full rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] py-2.5 text-sm font-semibold uppercase tracking-wider text-white shadow-md shadow-[#1E7BC8]/25 transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/50 focus:ring-offset-2">
            Login
        </button>
    </form>

    {{-- Pemisah --}}
    <div class="my-6 flex items-center gap-4">
        <span class="h-px flex-1 bg-slate-200"></span>
        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Atau</span>
        <span class="h-px flex-1 bg-slate-200"></span>
    </div>

    {{-- Login dengan Google (arahkan href ke route OAuth milik tim) --}}
    <a href="#"
       class="flex w-full items-center justify-center gap-2.5 rounded-lg border border-slate-200 bg-white py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
        <x-google-icon />
        <span>Lanjutkan dengan Google</span>
    </a>

    <p class="mt-8 text-center text-sm text-slate-500">
        Belum punya akun?
        <a href="{{ url('/register') }}" class="font-semibold text-[#1E7BC8] hover:underline">Daftar</a>
    </p>
@endsection
