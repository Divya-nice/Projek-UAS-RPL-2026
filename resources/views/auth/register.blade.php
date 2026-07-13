@extends('layouts.app')

@section('title', 'Buat Akun — Cuci Sepatu')
@section('authImage', asset('images/auth-hero.png'))

@section('content')
    {{-- Logo & judul --}}
    <div class="mb-6 flex flex-col items-center text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Cuci Sepatu"
             class="mb-5 h-14 w-auto" onerror="this.style.display='none'" />
        <h1 class="text-2xl font-bold text-[#1E293B]">Buat Akun</h1>
    </div>

    {{-- Form register: action ke /register (ganti ke route('register') bila route sudah tersedia) --}}
    <form method="POST" action="{{ url('/register') }}" class="space-y-3.5">
        @csrf

        <x-auth-input name="name" label="Nama Lengkap" type="text"
                      icon="user" placeholder="Enter your full name" autocomplete="name" />

        <x-auth-input name="email" label="Email" type="email"
                      icon="mail" placeholder="name@example.com" autocomplete="email" />

        <x-auth-input name="phone" label="Nomor HP" type="tel"
                      icon="phone" placeholder="08-000-0000" autocomplete="tel" />

        <x-auth-input name="password" label="Password" type="password"
                      icon="lock" placeholder="Min. 8 characters" autocomplete="new-password" :password="true" />

        <x-auth-input name="password_confirmation" label="Konfirmasi Password" type="password"
                      icon="lock" placeholder="Min. 8 characters" autocomplete="new-password" :password="true" />

        <button type="submit"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] py-2.5 text-sm font-semibold text-white shadow-md shadow-[#1E7BC8]/25 transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/50 focus:ring-offset-2">
            <span>Daftar</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>
    </form>

    {{-- Pemisah --}}
    <div class="my-5 flex items-center gap-4">
        <span class="h-px flex-1 bg-slate-200"></span>
        <span class="text-xs font-medium uppercase tracking-wide text-slate-400">Atau</span>
        <span class="h-px flex-1 bg-slate-200"></span>
    </div>

    {{-- Daftar dengan Google (arahkan href ke route OAuth milik tim) --}}
    <a href="#"
       class="flex w-full items-center justify-center gap-2.5 rounded-lg border border-slate-200 bg-white py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
        <x-google-icon />
        <span>Lanjutkan dengan Google</span>
    </a>

    <p class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ url('/login') }}" class="font-semibold text-[#1E7BC8] hover:underline">Masuk</a>
    </p>
@endsection
