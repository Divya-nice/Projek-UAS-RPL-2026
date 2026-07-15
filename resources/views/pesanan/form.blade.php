@extends('layouts.app')

@section('title', 'Form Pemesanan — Cuci Sepatu')

@section('content')
@php
    $petaHarga = collect($layanan)->map(fn ($i) => [
        'nama'  => $i['nama_layanan'],
        'harga' => $i['harga'],
    ]);
    $terpilih = $layanan->first();
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>/</span>
            <a href="{{ route('pesanan.katalog') }}" class="hover:text-[#1E7BC8]">Pesan Layanan</a>
            <span>/</span>
            <span class="font-medium text-[#1566AD]">Data Pesanan</span>
        </nav>

        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Data Pesanan</h1>
        <p class="mt-1.5 text-sm text-slate-500">Lengkapi data pesanan Anda sebelum melanjutkan ke proses pengantaran.</p>

        {{-- Stepper --}}
        <div class="mt-8">
            <x-step-indicator :current="1" />
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

        <form method="POST" action="{{ route('pesanan.store') }}" enctype="multipart/form-data" class="mt-8" id="form-pesanan">
            @csrf
            <input type="hidden" name="layanan_id" id="input-layanan" value="">

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Ringkasan layanan (sticky) --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-4">
                        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-100">
                            <div class="border-b border-slate-100 p-5">
                                <h2 class="text-base font-bold text-[#1E293B]">Ringkasan Layanan</h2>
                            </div>
                            <div class="p-5">
                                <x-shoe-thumb class="aspect-[4/3] w-full" />
                                <p class="mt-4 text-sm font-semibold text-[#1566AD]" id="ringkas-nama">{{ $terpilih['nama_layanan'] }}</p>
                                <p class="mt-1 text-2xl font-bold text-[#1E293B]" id="ringkas-harga">{{ \App\Http\Controllers\PesananController::rupiah($terpilih['harga']) }}</p>

                                <label class="mt-4 block text-xs font-medium text-slate-500">Ganti Layanan</label>
                                <select id="pilih-layanan"
                                    class="mt-1.5 w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">
                                    @foreach($layanan as $item)
                                        <option value="{{ $item->id }}" data-harga="{{ $item->harga }}" @selected($item->id === $terpilih['id'])>{{ $item->nama_layanan }} &mdash; {{ \App\Http\Controllers\PesananController::rupiah($item->harga) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5 rounded-2xl bg-[#EAF3FC] p-4 text-xs leading-relaxed text-[#1566AD]">
                            <x-icon name="shield-check" class="mt-0.5 h-4 w-4 shrink-0" />
                            <span>Informasi: Pastikan seluruh data yang dimasukkan sudah benar sebelum melanjutkan ke langkah berikutnya.</span>
                        </div>
                    </div>
                </aside>

                {{-- Kolom form --}}
                <div class="space-y-6 lg:col-span-2">
                    {{-- Data Pelanggan --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Data Pelanggan</h2>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="nama" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap Anda" required
                                    class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">
                            </div>
                            <div>
                                <label for="telepon" class="mb-1.5 block text-sm font-medium text-slate-700">Nomor Telepon / HP <span class="text-red-500">*</span></label>
                                <input type="tel" id="telepon" name="telepon" value="{{ old('telepon') }}" placeholder="0812xxxxxxx" required
                                    class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email (Opsional)</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                                    class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="alamat" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea id="alamat" name="alamat" rows="2" placeholder="Jl. Contoh No. 12, Pontianak" required
                                    class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pesanan --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Detail Pesanan</h2>
                        <div class="mt-5 space-y-5">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Layanan</label>
                                <input type="text" id="detail-layanan" value="{{ $terpilih['nama_layanan'] }}" readonly
                                    class="block w-full cursor-not-allowed rounded-lg border border-slate-200 bg-[#F2F7FD] px-3.5 py-2.5 text-sm font-medium text-slate-600">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah Sepatu <span class="text-red-500">*</span></label>
                                <div class="inline-flex items-center overflow-hidden rounded-lg border border-slate-200">
                                    <button type="button" data-qty="minus" class="flex h-10 w-10 items-center justify-center text-slate-500 transition hover:bg-slate-50" aria-label="Kurangi">
                                        <x-icon name="minus" class="h-4 w-4" />
                                    </button>
                                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="20" readonly
                                        class="h-10 w-14 border-x border-slate-200 text-center text-sm font-semibold text-[#1E293B] focus:outline-none">
                                    <button type="button" data-qty="plus" class="flex h-10 w-10 items-center justify-center bg-[#0F2A4A] text-white transition hover:bg-[#1566AD]" aria-label="Tambah">
                                        <x-icon name="plus" class="h-4 w-4" />
                                    </button>
                                </div>
                            </div>

                            {{-- Ukuran sepatu dinamis (di-render via JS) --}}
                            <div id="wadah-ukuran" class="grid gap-4 sm:grid-cols-2"></div>

                            <div>
                                <label for="catatan" class="mb-1.5 block text-sm font-medium text-slate-700">Catatan (Opsional)</label>
                                <textarea id="catatan" name="catatan" rows="2" placeholder="Jelaskan tingkat kotoran atau keluhan spesifik pada sepatu"
                                    class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Foto --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Upload Foto Sepatu</h2>
                        <div class="mt-5 grid gap-4 lg:grid-cols-3">
                            <label for="foto" class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-[#FAFCFE] px-4 py-8 text-center transition hover:border-[#1E7BC8] hover:bg-[#F2F7FD] lg:col-span-2">
                                <x-icon name="upload" class="h-8 w-8 text-[#1E7BC8]" />
                                <span class="mt-3 text-sm font-medium text-slate-600">Klik untuk upload foto atau drag &amp; drop file di sini</span>
                                <span class="mt-1 text-xs text-slate-400">JPG, PNG Maksimal 5 MB</span>
                                <input type="file" id="foto" name="foto[]" accept="image/png,image/jpeg" multiple class="hidden">
                            </label>

                            <div class="rounded-xl bg-[#EAF3FC] p-4">
                                <p class="text-sm font-semibold text-[#1566AD]">Tips Foto:</p>
                                <ul class="mt-2 space-y-1.5 text-xs text-[#1566AD]">
                                    <li class="flex items-center gap-1.5"><x-icon name="check-circle" class="h-3.5 w-3.5" /> Foto tampak depan</li>
                                    <li class="flex items-center gap-1.5"><x-icon name="check-circle" class="h-3.5 w-3.5" /> Foto tampak samping</li>
                                    <li class="flex items-center gap-1.5"><x-icon name="check-circle" class="h-3.5 w-3.5" /> Foto bagian belakang</li>
                                    <li class="flex items-center gap-1.5"><x-icon name="check-circle" class="h-3.5 w-3.5" /> Pastikan foto jelas dan terang</li>
                                </ul>
                            </div>
                        </div>
                        <ul id="daftar-foto" class="mt-4 space-y-2"></ul>
                    </div>

                    {{-- Metode Pengantaran --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                        <h2 class="text-base font-bold text-[#1E293B]">Metode Pengantaran</h2>
                        <p class="mt-1 text-sm text-slate-500">Pilih cara pengantaran yang sesuai untuk Anda.</p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <label class="metode-opsi relative cursor-pointer rounded-xl border-2 border-[#1E7BC8] bg-[#F2F7FD] p-4 transition">
                                <input type="radio" name="metode" value="jemput" class="peer sr-only" checked>
                                <div class="flex items-start gap-3">
                                    <x-icon name="truck" class="h-6 w-6 text-[#1E7BC8]" />
                                    <div>
                                        <p class="text-sm font-semibold text-[#1E293B]">Dijemput oleh Pemilik</p>
                                        <p class="mt-0.5 text-xs text-slate-500">Kami akan menjemput sepatu Anda di alamat yang telah ditentukan.</p>
                                    </div>
                                </div>
                                <span class="metode-cek absolute right-3 top-3 flex h-5 w-5 items-center justify-center rounded-full bg-[#1E7BC8] text-white"><x-icon name="check" class="h-3 w-3" /></span>
                            </label>

                            <label class="metode-opsi relative cursor-pointer rounded-xl border-2 border-slate-200 bg-white p-4 transition">
                                <input type="radio" name="metode" value="antar" class="peer sr-only">
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
                                        <option value="{{ $nama }}" data-ongkir="{{ $biaya }}" @selected(old('kecamatan') === $nama)>{{ $nama }} ({{ \App\Http\Controllers\PesananController::rupiah($biaya) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="alamat_jemput" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat Penjemputan</label>
                                <textarea id="alamat_jemput" name="alamat_jemput" rows="2" placeholder="Jl. Ahmad Yani No. 123, dekat Masjid Mujahidin"
                                    class="block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">{{ old('alamat_jemput') }}</textarea>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-white px-4 py-3 text-sm shadow-sm">
                                <span class="font-medium text-slate-600">Estimasi Ongkos Jemput</span>
                                <span class="font-bold text-[#1566AD]" id="label-ongkir">Rp0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col-reverse items-center justify-between gap-3 sm:flex-row">
                        <a href="{{ route('pesanan.katalog') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50 sm:w-auto">
                            <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
                        </a>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#1566AD] to-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2 sm:w-auto">
                            Lanjut ke Ringkasan <x-icon name="arrow-right" class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        const petaHarga = @json($petaHarga);
        const rupiah = (n) => 'Rp' + Number(n || 0).toLocaleString('id-ID');

        const inputLayanan  = document.getElementById('input-layanan');
        const pilihLayanan  = document.getElementById('pilih-layanan');
        const ringkasNama   = document.getElementById('ringkas-nama');
        const ringkasHarga  = document.getElementById('ringkas-harga');
        const detailLayanan = document.getElementById('detail-layanan');
        const inputJumlah   = document.getElementById('jumlah');
        const wadahUkuran   = document.getElementById('wadah-ukuran');

        function syncLayanan() {
            const slug = pilihLayanan.value;
            const data = petaHarga[slug];
            if (! data) return;
            inputLayanan.value  = slug;
            ringkasNama.textContent  = data.nama;
            ringkasHarga.textContent = rupiah(data.harga);
            detailLayanan.value = data.nama;
        }
        pilihLayanan.addEventListener('change', syncLayanan);

        function renderUkuran() {
            const jumlah = parseInt(inputJumlah.value, 10) || 1;
            const lama = {};
            wadahUkuran.querySelectorAll('input').forEach((el, i) => { lama[i] = el.value; });
            wadahUkuran.innerHTML = '';
            for (let i = 0; i < jumlah; i++) {
                const div = document.createElement('div');
                div.innerHTML =
                    '<label class="mb-1.5 block text-sm font-medium text-slate-700">Ukuran Sepatu ' + (i + 1) + ' <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="ukuran[]" required placeholder="mis. 42" ' +
                    'class="block w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 shadow-sm transition focus:border-[#1E7BC8] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/30">';
                if (lama[i]) div.querySelector('input').value = lama[i];
                wadahUkuran.appendChild(div);
            }
        }

        document.querySelectorAll('[data-qty]').forEach((btn) => {
            btn.addEventListener('click', () => {
                let val = parseInt(inputJumlah.value, 10) || 1;
                val += btn.dataset.qty === 'plus' ? 1 : -1;
                val = Math.min(20, Math.max(1, val));
                inputJumlah.value = val;
                renderUkuran();
            });
        });

        const detailJemput = document.getElementById('detail-jemput');
        const kecamatan    = document.getElementById('kecamatan');
        const labelOngkir  = document.getElementById('label-ongkir');

        function hitungOngkir() {
            const opt = kecamatan.options[kecamatan.selectedIndex];
            labelOngkir.textContent = rupiah(opt ? opt.dataset.ongkir : 0);
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
        kecamatan.addEventListener('change', hitungOngkir);

        const inputFoto  = document.getElementById('foto');
        const daftarFoto = document.getElementById('daftar-foto');
        inputFoto.addEventListener('change', () => {
            daftarFoto.innerHTML = '';
            Array.from(inputFoto.files).forEach((file) => {
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between rounded-lg bg-[#F2F7FD] px-4 py-2.5 text-sm';
                const kb = (file.size / 1024).toFixed(0);
                li.innerHTML =
                    '<span class="truncate font-medium text-slate-600">' + file.name + '</span>' +
                    '<span class="ml-3 shrink-0 text-xs text-[#1E7BC8]">' + kb + ' KB</span>';
                daftarFoto.appendChild(li);
            });
        });

        syncLayanan();
        renderUkuran();
        syncMetode();
        hitungOngkir();
    })();
</script>
@endpush
@endsection
