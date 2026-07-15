@extends('layouts.app')

@section('title', 'Metode Pengantaran — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
    $metodeTerpilih = old('metode', $pesanan['metode'] ?? 'jemput');
    $kecTerpilih    = old('kecamatan', $pesanan['kecamatan'] ?? null);
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>/</span>
            <a href="{{ route('pesanan.form') }}" class="hover:text-[#1E7BC8]">Data Pesanan</a>
            <span>/</span>
            <span class="font-medium text-[#1566AD]">Metode Pengantaran</span>
        </nav>

        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Metode Pengantaran</h1>
        <p class="mt-1.5 text-sm text-slate-500">Pilih cara pengantaran sepatu Anda sebelum melanjutkan ke ringkasan.</p>

        {{-- Stepper --}}
        <div class="mt-8">
            <x-step-indicator :current="2" />
        </div>

        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                <p class="font-semibold">Mohon periksa kembali isian Anda:</p>
                <ul class="mt-1.5 list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pesanan.pengantaran.proses') }}" class="mt-8">
            @csrf
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Kolom form --}}
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Metode Pengantaran</h2>
                        <p class="mt-1 text-sm text-slate-500">Pilih cara pengantaran yang sesuai untuk Anda.</p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label class="metode-opsi relative cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 transition">
                                <input type="radio" name="metode" value="jemput" class="peer sr-only" @checked($metodeTerpilih === 'jemput')>
                                <div class="flex items-start gap-3">
                                    <x-icon name="truck" class="h-6 w-6 text-[#1E7BC8]" />
                                    <div>
                                        <p class="text-sm font-semibold text-[#1E293B]">Dijemput oleh Pemilik</p>
                                        <p class="mt-0.5 text-xs text-slate-500">Kami akan menjemput sepatu Anda di alamat yang telah ditentukan.</p>
                                    </div>
                                </div>
                                <span class="metode-cek absolute right-3 top-3 hidden h-5 w-5 items-center justify-center rounded-full bg-[#1E7BC8] text-white"><x-icon name="check" class="h-3 w-3" /></span>
                            </label>

                            <label class="metode-opsi relative cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 transition">
                                <input type="radio" name="metode" value="antar" class="peer sr-only" @checked($metodeTerpilih === 'antar')>
                                <div class="flex items-start gap-3">
                                    <x-icon name="building" class="h-6 w-6 text-slate-400" />
                                    <div>
                                        <p class="text-sm font-semibold text-[#1E293B]">Antar Sendiri</p>
                                        <p class="mt-0.5 text-xs text-slate-500">Anda mengantarkan sepatu ke lokasi usaha sesuai jam operasional.</p>
                                    </div>
                                </div>
                                <span class="metode-cek absolute right-3 top-3 hidden h-5 w-5 items-center justify-center rounded-full bg-[#1E7BC8] text-white"><x-icon name="check" class="h-3 w-3" /></span>
                            </label>
                        </div>

                        {{-- Detail penjemputan --}}
                        <div id="detail-jemput" class="mt-5 space-y-4 rounded-xl bg-[#F2F7FD] p-4">
                            <div>
                                <label for="kecamatan" class="mb-1.5 block text-sm font-medium text-slate-700">Kecamatan</label>
                                <select id="kecamatan" name="kecamatan"
                                    class="block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">
                                    @foreach($ongkos as $nama => $biaya)
                                        <option value="{{ $nama }}" data-ongkir="{{ $biaya }}" @selected($kecTerpilih === $nama)>{{ $nama }} ({{ $rp($biaya) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="alamat_jemput" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat Penjemputan</label>
                                <textarea id="alamat_jemput" name="alamat_jemput" rows="2" placeholder="Jl. Ahmad Yani No. 123, dekat Masjid Mujahidin"
                                    class="block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">{{ old('alamat_jemput', $pesanan['alamat_jemput'] ?? '') }}</textarea>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-white px-4 py-3 text-sm shadow-sm">
                                <span class="font-medium text-slate-600">Estimasi Ongkos Jemput</span>
                                <span class="font-bold text-[#1566AD]" id="label-ongkir">Rp0</span>
                            </div>

                            {{-- Peta lokasi penjemputan --}}
                            <div>
                                <div class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-slate-700">
                                    <x-icon name="map-pin" class="h-4 w-4 text-[#1E7BC8]" /> Lokasi Penjemputan
                                </div>
                                <div class="overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                                    <iframe id="peta-jemput" title="Peta lokasi penjemputan"
                                        src="https://maps.google.com/maps?q=Pontianak&amp;z=13&amp;output=embed"
                                        class="h-48 w-full" style="border:0" loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                                </div>
                                <p class="mt-1.5 text-xs text-slate-400">Titik peta menyesuaikan alamat &amp; kecamatan yang Anda isi di atas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan singkat --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-4">
                        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                            <h2 class="text-base font-bold text-[#1E293B]">Ringkasan Layanan</h2>
                            <dl class="mt-4 space-y-3 text-sm">
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Layanan</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['layanan_nama'] ?? '-' }}</dd></div>
                                <div class="flex justify-between gap-4"><dt class="text-slate-500">Jumlah</dt><dd class="text-right font-medium text-slate-700">{{ $pesanan['jumlah'] ?? 1 }} pasang</dd></div>
                                <div class="mt-2 flex justify-between border-t border-dashed border-slate-200 pt-3">
                                    <dt class="font-semibold text-[#1E293B]">Subtotal</dt>
                                    <dd class="font-bold text-[#1566AD]">{{ $rp($pesanan['subtotal'] ?? 0) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="flex items-start gap-2.5 rounded-2xl bg-[#EAF3FC] p-4 text-xs leading-relaxed text-[#1566AD]">
                            <x-icon name="shield-check" class="mt-0.5 h-4 w-4 shrink-0" />
                            <span>Ongkos jemput mengikuti kecamatan yang dipilih. Total akhir akan tampil pada halaman ringkasan.</span>
                        </div>
                    </div>
                </aside>
            </div>

            {{-- Aksi --}}
            <div class="mt-6 flex flex-col-reverse items-center justify-between gap-3 sm:flex-row">
                <a href="{{ route('pesanan.form') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 sm:w-auto">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
                </a>
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#1566AD] to-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2 sm:w-auto">
                    Lanjut ke Ringkasan <x-icon name="arrow-right" class="h-4 w-4" />
                </button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        const rupiah = (n) => 'Rp' + Number(n || 0).toLocaleString('id-ID');
        const detailJemput = document.getElementById('detail-jemput');
        const kecamatan    = document.getElementById('kecamatan');
        const labelOngkir  = document.getElementById('label-ongkir');
        const alamatJemput = document.getElementById('alamat_jemput');
        const peta         = document.getElementById('peta-jemput');
        let petaTimer;

        function hitungOngkir() {
            const opt = kecamatan.options[kecamatan.selectedIndex];
            labelOngkir.textContent = rupiah(opt ? opt.dataset.ongkir : 0);
        }

        function updatePeta() {
            const opt    = kecamatan.options[kecamatan.selectedIndex];
            const kec    = opt ? opt.value : '';
            const alamat = (alamatJemput.value || '').trim();
            const query  = [alamat, kec, 'Pontianak'].filter(Boolean).join(', ');
            peta.src = 'https://maps.google.com/maps?q=' + encodeURIComponent(query) + '&z=15&output=embed';
        }

        function syncMetode() {
            const metode = document.querySelector('input[name=metode]:checked').value;
            document.querySelectorAll('.metode-opsi').forEach((label) => {
                const dipilih = label.querySelector('input').checked;
                label.classList.toggle('border-[#1E7BC8]', dipilih);
                label.classList.toggle('bg-[#F2F7FD]', dipilih);
                label.classList.toggle('border-slate-200', ! dipilih);
                label.classList.toggle('bg-white', ! dipilih);
                const cek = label.querySelector('.metode-cek');
                cek.classList.toggle('hidden', ! dipilih);
                cek.classList.toggle('flex', dipilih);
            });
            detailJemput.style.display = metode === 'jemput' ? '' : 'none';
            kecamatan.required = metode === 'jemput';
        }

        document.querySelectorAll('input[name=metode]').forEach((r) => r.addEventListener('change', syncMetode));
        kecamatan.addEventListener('change', () => { hitungOngkir(); updatePeta(); });
        alamatJemput.addEventListener('input', () => {
            clearTimeout(petaTimer);
            petaTimer = setTimeout(updatePeta, 700);
        });

        syncMetode();
        hitungOngkir();
        updatePeta();
    })();
</script>
@endpush
@endsection
