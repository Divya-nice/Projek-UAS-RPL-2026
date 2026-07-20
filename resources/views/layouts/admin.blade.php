<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Cuci Sepatu') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="h-full bg-[#F1F5F9] text-slate-800 antialiased">

@php
    $navBase   = 'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition';
    $navActive = 'bg-[#38B6F1] text-white shadow-sm';
    $navIdle   = 'text-blue-50/90 hover:bg-white/10 hover:text-white';
@endphp

<div class="min-h-screen lg:flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="admin-sidebar"
           class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full overflow-y-auto bg-[#1C56C0] px-5 py-6 transition-transform duration-300 lg:static lg:z-auto lg:w-72 lg:translate-x-0">

        {{-- Logo --}}
        <div class="mb-8 flex items-center justify-center">
            <img src="{{ asset('images/logo-cucisepatu.png') }}" alt="CUCISEPATU Pontianak" class="h-28 w-auto object-contain" />
        </div>

        {{-- Menu --}}
        <nav class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                Dashboard
            </a>
            <a href={{ route('admin.pesanan') }} class="{{ $navBase }} {{ request()->routeIs('admin.pesanan*') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" /></svg>
                Kelola Pesanan
            </a>
            <a href="{{ Route::has('admin.verifikasi') ? route('admin.verifikasi') : '#' }}" class="{{ $navBase }} {{ Route::has('admin.verifikasi') && request()->routeIs('admin.verifikasi*') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                Verifikasi Pembayaran
            </a>
            <a href="{{ Route::has('admin.layanan') ? route('admin.layanan') : '#' }}" class="{{ $navBase }} {{ Route::has('admin.layanan') && request()->routeIs('admin.layanan*') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                Kelola Layanan
            </a>
            <a href="{{ Route::has('admin.laporan') ? route('admin.laporan') : '#' }}" class="{{ $navBase }} {{ Route::has('admin.laporan') && request()->routeIs('admin.laporan*') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                Laporan Pendapatan
            </a>
            <a href="{{ Route::has('admin.logout') ? route('admin.logout') : '#' }}" class="{{ $navBase }} {{ $navIdle }} mt-6">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" /></svg>
                Logout
            </a>
        </nav>
    </aside>

    {{-- Overlay (mobile) --}}
    <div id="admin-overlay" class="fixed inset-0 z-30 hidden bg-slate-900/40 lg:hidden"></div>

    {{-- ===================== MAIN ===================== --}}
    <div class="flex min-h-screen flex-1 flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-slate-200 bg-white px-4 py-3 sm:px-6 lg:px-8">
            <button type="button" data-sidebar-toggle class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>

            <div class="ml-auto flex items-center gap-2 sm:gap-4">
                {{-- Profil dropdown --}}
                <div class="relative" data-profile>
                    <button type="button" data-profile-toggle class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-slate-100">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-semibold leading-tight text-slate-800">Pemilik Usaha</span>
                            <span class="block text-xs leading-tight text-slate-400">Admin</span>
                        </span>
                        <svg data-profile-arrow class="h-4 w-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>

                    {{-- Menu dropdown --}}
                    <div data-profile-menu class="absolute right-0 top-full z-50 mt-2 hidden w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white py-2 shadow-xl">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="text-sm font-semibold text-slate-800">Pemilik Usaha</p>
                            <p class="truncate text-xs text-slate-400">admin@cucisepatuptk.id</p>
                        </div>
                        <a href="{{ Route::has('admin.profil') ? route('admin.profil') : '#' }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                            Edit Profil
                        </a>
                        <a href="{{ Route::has('admin.password') ? route('admin.password') : '#' }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                            Ubah Password
                        </a>
                        <div class="my-1 border-t border-slate-100"></div>
                        <a href="{{ Route::has('admin.logout') ? route('admin.logout') : '#' }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" /></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('status'))
                <div class="mx-auto mb-5 flex max-w-6xl items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    {{ session('status') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-overlay');
        function open() { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
        function close() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-sidebar-toggle]')) { open(); return; }
            if (e.target === overlay) { close(); }
        });
    })();

    (function () {
        const wrap = document.querySelector('[data-profile]');
        if (!wrap) return;
        const menu = wrap.querySelector('[data-profile-menu]');
        const arrow = wrap.querySelector('[data-profile-arrow]');
        function toggle() { menu.classList.toggle('hidden'); if (arrow) arrow.classList.toggle('rotate-180'); }
        function close() { menu.classList.add('hidden'); if (arrow) arrow.classList.remove('rotate-180'); }
        document.addEventListener('click', function (e) {
            if (e.target.closest('[data-profile-toggle]')) { e.preventDefault(); toggle(); return; }
            if (!e.target.closest('[data-profile-menu]')) { close(); }
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
    })();
</script>

@stack('scripts')
</body>
</html>
