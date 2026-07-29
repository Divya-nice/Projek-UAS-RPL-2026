@props([
    'slug',
    'nama',
    'harga',
    'gambar' => null,
    'deskripsi' => '',
])

<div class="group flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <div class="h-40 shrink-0 overflow-hidden">

        @if($gambar)
            <img
                src="{{ asset('storage/'.$gambar) }}"
                alt="{{ $nama }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <x-shoe-thumb
                :slug="$slug"
                :alt="$nama"
                class="h-full w-full transition duration-500 group-hover:scale-105"/>
        @endif

    </div>
    
    <div class="flex flex-1 flex-col p-5">
        <h3 class="text-base font-bold text-[#1E293B]">{{ $nama }}</h3>
        <p class="mt-1.5 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ $deskripsi }}</p>
        <div class="mt-auto flex items-center justify-between pt-4">
            <span class="text-sm font-bold text-[#1566AD]">Mulai {{ $harga }}</span>
            <a href="{{ route('pesanan.form', ['layanan' => $slug]) }}"
               class="rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:opacity-95">
                Pilih
            </a>
        </div>
    </div>
</div>
