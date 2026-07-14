@extends('layouts.app')

@section('title', 'Katalog Layanan — Cuci Sepatu')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-b from-white to-[#F2F7FD]">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-20">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#1E293B] sm:text-4xl">Pesan Layanan</h1>
                <p class="mt-4 max-w-lg text-sm leading-relaxed text-slate-500 sm:text-base">
                    Pilih layanan terbaik untuk sepatu kesayangan Anda. Kami siap membuat sepatu Anda bersih, wangi, dan seperti baru kembali dengan teknologi pembersihan terkini.
                </p>
                <a href="#daftar-layanan"
                   class="mt-8 inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#1566AD] to-[#0F3F73] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95">
                    Lihat Menu Layanan
                    <x-icon name="arrow-down" class="h-4 w-4" />
                </a>
            </div>
            <div class="relative">
                <div class="absolute -inset-4 rounded-full bg-[#1E7BC8]/10 blur-3xl"></div>
                <x-shoe-thumb slug="hero" fit="contain" alt="Katalog layanan CuciSepatu" class="mx-auto aspect-[4/3] w-full max-w-xl" />
            </div>
        </div>
    </section>

    {{-- Daftar Layanan --}}
    <section id="daftar-layanan" class="bg-[#F2F7FD] py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Pilih Treatment / Layanan</h2>
                <p class="mt-2 text-sm text-slate-500">Harga dapat berubah sesuai kondisi sepatu.</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($layanan as $slug => $item)
                    <x-service-card
                        :slug="$slug"
                        :nama="$item['nama']"
                        :harga="\App\Http\Controllers\PesananController::rupiah($item['harga'])"
                        :estimasi="$item['estimasi']"
                        :deskripsi="$item['deskripsi']"
                        :populer="$item['populer']" />
                @endforeach
            </div>
        </div>
    </section>
@endsection
