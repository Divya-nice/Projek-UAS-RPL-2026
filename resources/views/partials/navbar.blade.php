<header class="sticky top-0 z-40 border-b border-slate-100 bg-white/90 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        {{-- Logo --}}
        <a href="{{ route('pesanan.beranda') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Cuci Sepatu PTK" class="h-8 w-auto" onerror="this.style.display='none'" />
            <span class="text-lg font-bold text-[#1E293B]">Cuci Sepatu <span class="text-[#1E7BC8]">PTK</span></span>
        </a>

        {{-- Menu desktop --}}
        <div class="hidden items-center gap-8 md:flex">
            <a href="{{ route('pesanan.beranda') }}" @class(['text-sm font-medium transition hover:text-[#1E7BC8]', 'text-[#1E7BC8]' => request()->routeIs('pesanan.beranda*'), 'text-slate-600' => ! request()->routeIs('pesanan.beranda*')])>Beranda</a>
            <a href="{{ route('pesanan.katalog') }}" @class(['text-sm font-medium transition hover:text-[#1E7BC8]', 'text-[#1E7BC8]' => request()->routeIs('pesanan.katalog') || request()->routeIs('pesanan.form'), 'text-slate-600' => ! (request()->routeIs('pesanan.katalog') || request()->routeIs('pesanan.form'))])>Pesan</a>
            <a href="{{ route('pesanan.riwayat') }}" @class(['text-sm font-medium transition hover:text-[#1E7BC8]', 'text-[#1E7BC8]' => request()->routeIs('pesanan.riwayat'), 'text-slate-600' => ! request()->routeIs('pesanan.riwayat')])>Riwayat</a>
            <a href="{{ route('pesanan.akun') }}" @class(['text-sm font-medium transition hover:text-[#1E7BC8]', 'text-[#1E7BC8]' => request()->routeIs('pesanan.akun'), 'text-slate-600' => ! request()->routeIs('pesanan.akun')])>Akun</a>
        </div>

        {{-- Sapaan pengguna --}}
        <div class="hidden items-center md:flex">
            <a href="{{ route('pesanan.akun') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3.5 py-1.5 text-sm font-medium text-[#1E293B] shadow-sm transition hover:border-[#1E7BC8]/40 hover:text-[#1566AD]">
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#EAF3FC] text-[#1E7BC8]">
                    <x-icon name="user" class="h-4 w-4" />
                </span>
                Halo, {{ auth()->check() ? auth()->user()->name : 'Pelanggan' }}
            </a>
        </div>

        {{-- Tombol menu mobile --}}
        <button type="button" data-nav-toggle
            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 md:hidden"
            aria-label="Buka menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </nav>

    {{-- Menu mobile --}}
    <div id="mobile-menu" class="hidden border-t border-slate-100 md:hidden">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('pesanan.beranda') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Beranda</a>
            <a href="{{ route('pesanan.katalog') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Pesan</a>
            <a href="{{ route('pesanan.riwayat') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Riwayat</a>
            <a href="{{ route('pesanan.akun') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Akun</a>
            <div class="mt-2 flex items-center gap-2 border-t border-slate-100 pt-3">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#EAF3FC] text-[#1E7BC8]"><x-icon name="user" class="h-4 w-4" /></span>
                <span class="text-sm font-medium text-[#1E293B]">Halo, {{ auth()->check() ? auth()->user()->name : 'Pelanggan' }}</span>
            </div>
        </div>
    </div>
</header>
