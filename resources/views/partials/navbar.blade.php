<header class="sticky top-0 z-40 border-b border-slate-100 bg-white/90 backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        {{-- Brand --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Cuci Sepatu" class="h-8 w-auto" onerror="this.style.display='none'" />
            <span class="text-lg font-bold text-[#1E293B]">Cuci<span class="text-[#1E7BC8]">Sepatu</span></span>
        </a>

        {{-- Menu desktop --}}
        <div class="hidden items-center gap-8 md:flex">
            <a href="#" class="text-sm font-medium text-slate-600 transition hover:text-[#1E7BC8]">Beranda</a>
            <a href="#" class="text-sm font-medium text-slate-600 transition hover:text-[#1E7BC8]">Layanan</a>
            <a href="#" class="text-sm font-medium text-slate-600 transition hover:text-[#1E7BC8]">Tentang</a>
            <a href="#" class="text-sm font-medium text-slate-600 transition hover:text-[#1E7BC8]">Kontak</a>
        </div>

        {{-- Aksi kanan (desktop) --}}
        <div class="hidden items-center gap-3 md:flex">
            <a href="{{ url('/login') }}" class="text-sm font-semibold text-[#1E7BC8] hover:underline">Masuk</a>
            <a href="{{ url('/register') }}"
               class="rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-95">
                Daftar
            </a>
        </div>

        {{-- Tombol hamburger (mobile) --}}
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
            <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Beranda</a>
            <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Layanan</a>
            <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Tentang</a>
            <a href="#" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Kontak</a>
            <div class="mt-2 flex items-center gap-3 border-t border-slate-100 pt-3">
                <a href="{{ url('/login') }}" class="text-sm font-semibold text-[#1E7BC8]">Masuk</a>
                <a href="{{ url('/register') }}" class="rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-4 py-2 text-sm font-semibold text-white">Daftar</a>
            </div>
        </div>
    </div>
</header>
