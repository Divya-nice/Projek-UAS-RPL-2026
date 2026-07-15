@props([
    'icon',
    'judul',
    'teks',
])

<div class="flex items-start gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-100 transition duration-300 hover:-translate-y-0.5 hover:shadow-md">
    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1E7BC8]">
        <x-icon :name="$icon" class="h-6 w-6" />
    </span>
    <div>
        <h3 class="text-base font-semibold text-[#1E293B]">{{ $judul }}</h3>
        <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $teks }}</p>
    </div>
</div>
