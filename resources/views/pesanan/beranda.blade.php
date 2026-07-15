@extends('layouts.app')

@section('title', 'Beranda — Cuci Sepatu')

@section('content')
    {{-- Hero --}}
    <section class="bg-gradient-to-b from-white to-[#F2F7FD]">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-8 lg:py-24">
            <div class="animate-[fadeInUp_0.6s_ease-out]">
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-[#1E293B] sm:text-4xl lg:text-5xl">
                    Sepatu Bersih,<br>
                    Langkah Makin
                    <span class="text-[#1E7BC8]">Percaya Diri</span>
                </h1>
                <p class="mt-5 max-w-lg text-sm leading-relaxed text-slate-500 sm:text-base">
                    Berikan perawatan profesional terbaik untuk sepatu kesayangan Anda. Dengan teknologi pembersihan modern dan tenaga ahli berpengalaman, kami pastikan sepatu Anda kembali seperti baru.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('pesanan.form') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#2E8BD9] to-[#1566AD] px-6 py-3 text-sm font-semibold text-white shadow-md shadow-[#1E7BC8]/25 transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2">
                        Pesan Sekarang
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                    <a href="{{ route('pesanan.katalog') }}"
                       class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-[#1566AD] shadow-sm transition hover:bg-slate-50">
                        Lihat Layanan
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-full bg-[#1E7BC8]/10 blur-3xl"></div>
                <x-shoe-thumb slug="hero" fit="contain" alt="Sepatu bersih CuciSepatu" class="mx-auto aspect-[4/3] w-full max-w-xl" />
            </div>
        </div>
    </section>

    {{-- Layanan Populer --}}
    <section class="bg-[#F2F7FD] py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Layanan Populer</h2>
                <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500">Solusi perawatan sepatu lengkap untuk segala jenis kebutuhan Anda.</p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($populer as $slug => $item)
                    <x-popular-card
                        :slug="$slug"
                        :nama="$item['nama']"
                        :harga="\App\Http\Controllers\PesananController::rupiah($item['harga'])"
                        :deskripsi="$item['deskripsi']" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mengapa Memilih Kami --}}
    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Mengapa Memilih Kami</h2>
                <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500">Komitmen kami memberikan pengalaman perawatan sepatu terbaik untuk Anda.</p>
            </div>

            <div class="mx-auto mt-10 grid max-w-4xl gap-4">
                @foreach($keunggulan as $fitur)
                    <x-feature-item :icon="$fitur['icon']" :judul="$fitur['judul']" :teks="$fitur['teks']" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Syarat & Ketentuan --}}
    <section class="bg-[#EAF3FC] py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3 lg:gap-10">
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Syarat &amp; Ketentuan Layanan</h2>
                    <ul class="mt-6 space-y-3">
                        @foreach($syarat as $poin)
                            <li class="flex items-start gap-3">
                                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-[#1E7BC8]" />
                                <span class="text-sm leading-relaxed text-slate-600">{{ $poin }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex flex-col items-center rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-100 lg:sticky lg:top-24 lg:self-start">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#EAF3FC] text-[#1E7BC8]">
                        <x-icon name="shield" class="h-8 w-8" />
                    </span>
                    <h3 class="mt-5 text-lg font-bold text-[#1E293B]">Perlindungan Maksimal</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">Setiap pasang sepatu Anda adalah prioritas kami. Kami bekerja dengan transparansi dan tanggung jawab penuh.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontak --}}
    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-100 border-t-4 border-t-[#1E7BC8] p-6 shadow-sm transition hover:shadow-md">
                    <x-icon name="map-pin" class="h-6 w-6 text-[#1E7BC8]" />
                    <h3 class="mt-4 text-sm font-semibold text-[#1E293B]">Alamat</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $kontak['alamat'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 border-t-4 border-t-[#1E7BC8] p-6 shadow-sm transition hover:shadow-md">
                    <x-icon name="phone" class="h-6 w-6 text-[#1E7BC8]" />
                    <h3 class="mt-4 text-sm font-semibold text-[#1E293B]">WhatsApp Kami</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $kontak['whatsapp'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 border-t-4 border-t-[#1E7BC8] p-6 shadow-sm transition hover:shadow-md">
                    <x-icon name="clock" class="h-6 w-6 text-[#1E7BC8]" />
                    <h3 class="mt-4 text-sm font-semibold text-[#1E293B]">Jam Operasional</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $kontak['operasional'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 border-t-4 border-t-[#1E7BC8] p-6 shadow-sm transition hover:shadow-md">
                    <x-icon name="share" class="h-6 w-6 text-[#1E7BC8]" />
                    <h3 class="mt-4 text-sm font-semibold text-[#1E293B]">Media Sosial</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $kontak['sosial'] }}</p>
                </div>
            </div>
        </div>
    </section>
@endsection
