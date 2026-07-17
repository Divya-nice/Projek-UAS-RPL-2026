<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Login Admin — Cuci Sepatu PTK</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="h-full bg-white text-slate-800 antialiased">

<div class="flex min-h-screen">

    {{-- Panel kiri (biru) --}}
    <div class="relative hidden w-1/2 shrink-0 overflow-hidden bg-gradient-to-br from-[#1566AD] to-[#1C56C0] p-12 text-white lg:flex lg:flex-col">
        {{-- Lingkaran dekoratif --}}
        <span class="pointer-events-none absolute -left-16 -top-10 h-56 w-56 rounded-full bg-white/10"></span>
        <span class="pointer-events-none absolute left-8 top-1/2 h-24 w-24 rounded-full bg-white/10"></span>
        <span class="pointer-events-none absolute -bottom-16 right-10 h-64 w-64 rounded-full bg-white/10"></span>

        {{-- Logo --}}
        <div class="relative flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-[#1566AD]">
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2 17.5c0-.6.4-1 1-1h2.2l1.5-2.6c.2-.3.5-.5.9-.5h3.3c.3 0 .6.1.8.3l2.4 2.1 4.6.9c1 .2 1.7 1 1.8 2 .1.6-.4 1.3-1.1 1.3H3c-.6 0-1-.4-1-1v-1.5Z"/></svg>
            </span>
            <span class="text-lg font-semibold">Cuci Sepatu PTK</span>
        </div>

        {{-- Teks utama --}}
        <div class="relative mt-16">
            <h1 class="text-4xl font-bold leading-tight">Kelola Usaha Cuci<br>Sepatu Lebih Mudah</h1>
            <p class="mt-5 max-w-sm text-sm leading-relaxed text-blue-50/90">Masuk sebagai pemilik usaha untuk mengelola pesanan, memverifikasi pembayaran, mengatur layanan, dan melihat laporan pendapatan.</p>
        </div>
    </div>

    {{-- Panel kanan (form) --}}
    <div class="flex w-full items-center justify-center px-6 py-12 sm:px-10 lg:w-1/2">
        <div class="w-full max-w-sm">
            <h2 class="text-2xl font-bold text-slate-900">Login Admin</h2>
            <p class="mt-1.5 text-sm text-slate-500">Masuk untuk mengakses Dashboard Admin Cuci Sepatu PTK.</p>

            {{-- Pesan error --}}
            @if ($errors->any())
                <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Email Admin</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@cucisepatuptk.com"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/60 py-2.5 pl-11 pr-3.5 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required autofocus />
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        </span>
                        <input type="password" name="password" id="admin-password" placeholder="••••••••"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/60 py-2.5 pl-11 pr-11 text-sm text-slate-700 placeholder-slate-400 focus:border-[#1E7BC8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/20" required />
                        <button type="button" data-toggle-password class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol --}}
                <button type="submit" class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl bg-[#1566AD] py-2.5 text-sm font-semibold text-white shadow-md shadow-[#1566AD]/25 transition hover:bg-[#12599c] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/50 focus:ring-offset-2">
                    Masuk ke Dashboard
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </button>
            </form>

            {{-- Kotak info terproteksi --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl bg-[#EAF3FC] px-4 py-3.5">
                <span class="mt-0.5 text-[#1566AD]">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd" /></svg>
                </span>
                <div class="text-xs leading-relaxed text-slate-600">
                    <p class="font-semibold text-slate-700">Halaman Terproteksi</p>
                    <p>Halaman ini hanya dapat diakses oleh pemilik usaha (Admin). Pastikan kredensial Anda aman.</p>
                </div>
            </div>

            <p class="mt-8 text-center text-sm text-slate-500">
                Login sebagai pelanggan?
                <a href="{{ route('login') }}" class="font-semibold text-[#1E7BC8] hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

</div>

<script>
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('[data-toggle-password]');
        if (!toggle) return;
        const input = document.getElementById('admin-password');
        if (input) input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>

</body>
</html>
