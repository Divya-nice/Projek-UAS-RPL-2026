@props([
    'current' => 1,
])

@php
    $steps = [
        1 => 'Data Pesanan',
        2 => 'Metode Pengantaran',
        3 => 'Ringkasan Pesanan',
        4 => 'Pembayaran',
    ];
@endphp

<div class="mx-auto flex w-full max-w-3xl items-center">
    @foreach($steps as $no => $label)
        @php $active = $no <= $current; @endphp
        <div class="flex flex-col items-center">
            <span @class([
                'flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold transition',
                'bg-[#1E7BC8] text-white shadow-md shadow-[#1E7BC8]/30' => $active,
                'bg-slate-100 text-slate-400' => ! $active,
            ])>{{ $no }}</span>
            <span @class([
                'mt-2 hidden text-center text-xs font-medium sm:block',
                'text-[#1566AD]' => $no === $current,
                'text-slate-500' => $no < $current,
                'text-slate-400' => $no > $current,
            ])>{{ $label }}</span>
        </div>

        @if(! $loop->last)
            <div @class([
                'mx-2 h-px flex-1 transition',
                'bg-[#1E7BC8]' => $no < $current,
                'bg-slate-200' => $no >= $current,
            ])></div>
        @endif
    @endforeach
</div>
