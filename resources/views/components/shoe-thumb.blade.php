@props([
    'class' => '',
    'label' => null,
    'slug' => null,
    'src' => null,
    'alt' => 'Foto sepatu',
    'fit' => 'cover',
])

@php
    $foto = null;
    $kandidat = [];
    if ($src) { $kandidat[] = $src; }
    if ($slug) {
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $kandidat[] = 'images/layanan/' . $slug . '.' . $ext;
            $kandidat[] = 'images/' . $slug . '.' . $ext;
        }
    }
    foreach ($kandidat as $rel) {
        if (is_file(public_path($rel))) { $foto = $rel; break; }
    }
    $objectFit = $fit === 'contain' ? 'object-contain' : 'object-cover';
@endphp

<div class="relative overflow-hidden {{ $class }}">
    @if($foto)
        <img src="{{ asset($foto) }}" alt="{{ $alt }}" class="h-full w-full {{ $objectFit }}" />
    @else
        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#EAF3FC] via-[#D6E8FA] to-[#BBD8F5]">
            <svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-1/2 w-1/2 text-[#1E7BC8]/70">
                <path d="M2 27c0-2 1-3 3-3l14-1 9-8c2-2 4-2 6 0l4 5c8 2 15 4 18 7 2 2 4 4 4 6 0 2-1 3-3 3H5c-2 0-3-1-3-3v-6Z" fill="currentColor" fill-opacity="0.18"/>
                <path d="M2 30h58c1.5 0 2.5 1 2.5 2.5S61.5 35 60 35H5c-2 0-3-1-3-3v-2Z" fill="currentColor" fill-opacity="0.35"/>
                <path d="M20 24l8-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-opacity="0.5"/>
                <path d="M26 26l6-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-opacity="0.5"/>
            </svg>
        </div>
    @endif
    @if($label)
        <span class="absolute left-3 top-3 rounded-md bg-white/85 px-2 py-0.5 text-[11px] font-semibold text-[#1566AD] shadow-sm backdrop-blur">{{ $label }}</span>
    @endif
</div>
