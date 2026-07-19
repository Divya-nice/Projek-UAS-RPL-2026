@props([
    'slug',
    'nama',
    'harga',
    'estimasi' => null,
    'deskripsi' => '',
    'populer' => false,
])

<div class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:shadow-lg">

    <div class="relative h-44 overflow-hidden">
        <x-shoe-thumb
            :slug="$slug"
            fit="cover"
            :alt="$nama"
            class="h-full w-full"
        />

        @if($populer)
            <span class="absolute right-3 top-3 rounded-full bg-[#1E7BC8] px-2.5 py-1 text-xs font-semibold text-white shadow">
                Populer
            </span>
        @endif
    </div>

    <div class="p-5">
        <h3 class="text-xl font-bold text-[#1E293B]">
            {{ $nama }}
        </h3>

        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
            {{ $deskripsi }}
        </p>

        @if($estimasi)
            <div class="mt-3 flex items-center gap-2 text-sm text-slate-400">
                <x-icon name="clock" class="h-4 w-4" />
                <span>Estimasi {{ $estimasi }}</span>
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
            <span class="text-2xl font-bold text-[#1566AD]">
                {{ $harga }}
            </span>

            <a href="{{ route('pesanan.form', ['layanan' => $slug]) }}"
               class="rounded-lg bg-[#2E8BD9] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1566AD]">
                Pilih
            </a>
        </div>
    </div>

</div>