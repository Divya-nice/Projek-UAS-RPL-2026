<footer class="border-t border-slate-100 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-4">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Cuci Sepatu" class="h-8 w-auto" onerror="this.style.display='none'" />
                    <span class="text-lg font-bold text-[#1E293B]">Cuci<span class="text-[#1E7BC8]">Sepatu</span></span>
                </div>
                <p class="mt-3 max-w-sm text-sm leading-relaxed text-slate-500">
                    Layanan cuci sepatu profesional — bersih, wangi, dan terawat. Antar-jemput mudah langsung dari aplikasi.
                </p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1E293B]">Layanan</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-[#1E7BC8]">Deep Clean</a></li>
                    <li><a href="#" class="hover:text-[#1E7BC8]">Fast Clean</a></li>
                    <li><a href="#" class="hover:text-[#1E7BC8]">Repaint</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1E293B]">Perusahaan</h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-[#1E7BC8]">Tentang</a></li>
                    <li><a href="#" class="hover:text-[#1E7BC8]">Kontak</a></li>
                    <li><a href="#" class="hover:text-[#1E7BC8]">FAQ</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-slate-200 pt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} CuciSepatu. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>
