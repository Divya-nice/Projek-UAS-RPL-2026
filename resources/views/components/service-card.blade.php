@props([
    'slug',
    'nama',
    'harga',
    'estimasi' => null,
    'deskripsi' => '',
    'populer' => false,
])

<div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <div class="relative h-44 shrink-0 overflow-hidden">
        <x-shoe-thumb :slug="$slug" :alt="$nama" class="h-full w-full transition duration-500 group-hover:scale-105" />
        @if($populer)
            <span class="absolute right-3 top-3 rounded-full bg-[#1E7BC8] px-2.5 py-0.5 text-[11px] font-semibold text-white shadow">Populer</span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="text-base font-bold text-[#1E293B]">{{ $nama }}</h3>
        <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ $deskripsi }}</p>

        @if($estimasi)
            <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-slate-400">
                <x-icon name="clock" class="h-4 w-4" />
                <span>Estimasi {{ $estimasi }}</span>
            </div>
        @endif

        <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
            <span class="text-lg font-bold text-[#1566AD]">{{ $harga }}</span>
            <a href="{{ route('pesanan.form', ['layanan' => $slug]) }}"
               class="rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40">
                Pilih
            </a>
        </div>
    </div>
</div>
