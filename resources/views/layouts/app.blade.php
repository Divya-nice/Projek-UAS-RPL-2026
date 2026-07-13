<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ trim($__env->yieldContent('title')) ?: config('app.name', 'Cuci Sepatu') }}</title>

    {{-- Font Poppins (opsional) — hapus bila tim sudah menetapkan font lain --}}
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="flex min-h-full flex-col bg-white text-slate-800 antialiased">

@php $isAuth = View::hasSection('authImage'); @endphp

@if($isAuth)
    {{-- Layout khusus halaman auth: split screen, tanpa navbar/footer (sesuai mockup) --}}
    <div class="flex min-h-screen">
        <div class="relative hidden w-3/5 shrink-0 bg-slate-100 lg:block">
            <img
                src="@yield('authImage')"
                alt="Layanan cuci sepatu"
                class="absolute inset-0 h-full w-full object-cover"
                onerror="this.style.display='none'"
            />
        </div>
        <div class="flex w-full items-center justify-center px-6 py-12 sm:px-10 lg:w-2/5">
            <div class="w-full max-w-sm">
                @yield('content')
            </div>
        </div>
    </div>
@else
    {{-- Layout umum aplikasi --}}
    @include('partials.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
@endif

{{-- Toggle menu mobile + show/hide password (vanilla JS, tanpa dependency) --}}
<script>
    document.addEventListener('click', function (e) {
        // Show / hide password
        const toggle = e.target.closest('[data-toggle-password]');
        if (toggle) {
            const input = document.getElementById(toggle.getAttribute('data-toggle-password'));
            if (input) {
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                const open = toggle.querySelector('[data-eye-open]');
                const closed = toggle.querySelector('[data-eye-closed]');
                if (open) open.classList.toggle('hidden', show);
                if (closed) closed.classList.toggle('hidden', !show);
            }
        }

        // Toggle navbar pada mobile
        const navBtn = e.target.closest('[data-nav-toggle]');
        if (navBtn) {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.toggle('hidden');
        }
    });
</script>

@stack('scripts')
</body>
</html>
