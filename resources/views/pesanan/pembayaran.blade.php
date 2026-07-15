@extends('layouts.app')

@section('title', 'Pembayaran — Cuci Sepatu')

@section('content')
@php
    $rp = fn ($n) => \App\Http\Controllers\PesananController::rupiah($n);
@endphp

<section class="bg-[#F2F7FD] py-10 lg:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="mb-4 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1E7BC8]">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('pesanan.ringkasan') }}" class="hover:text-[#1E7BC8]">Ringkasan Pesanan</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#1566AD]">Pembayaran</span>
        </nav>

        <h1 class="text-2xl font-bold text-[#1E293B] sm:text-3xl">Pembayaran</h1>
        <p class="mt-1.5 text-sm text-slate-500">Pilih metode pembayaran lalu selesaikan pesanan Anda.</p>

        <div class="mt-8">
            <x-step-indicator :current="4" />
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

        <form method="POST" action="{{ route('pesanan.pembayaran.proses') }}" enctype="multipart/form-data"
              class="mt-8 grid gap-6 lg:grid-cols-3">
            @csrf

            {{-- Kiri --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Metode Pembayaran --}}
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <h2 class="text-base font-bold text-[#1E293B]">Metode Pembayaran</h2>
                    <div class="mt-4 space-y-3">
                        <label class="bayar-opsi flex cursor-pointer items-start gap-3 rounded-xl border-2 border-slate-200 bg-white p-4 transition">
                            <input type="radio" name="metode_bayar" value="transfer" class="mt-0.5 accent-[#1E7BC8]" @checked(old('metode_bayar', 'transfer') === 'transfer')>
                            <div>
                                <p class="text-sm font-semibold text-[#1E293B]">Transfer Bank</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $rekening['bank'] ?? 'BCA' }} {{ $rekening['nomor'] ?? '' }} a.n. {{ $rekening['nama'] ?? '' }}</p>
                            </div>
                        </label>
                        <label class="bayar-opsi flex cursor-pointer items-start gap-3 rounded-xl border-2 border-slate-200 bg-white p-4 transition">
                            <input type="radio" name="metode_bayar" value="cash" class="mt-0.5 accent-[#1E7BC8]" @checked(old('metode_bayar') === 'cash')>
                            <div>
                                <p class="text-sm font-semibold text-[#1E293B]">Tunai (COD)</p>
                                <p class="mt-0.5 text-xs text-slate-500">Bayar saat sepatu dijemput atau diantar.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Unggah Berkas (khusus Transfer) --}}
                <div id="kartu-bukti" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EAF3FC] text-[#1E7BC8]">
                            <x-icon name="upload" class="h-5 w-5" />
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-[#1E293B]">Unggah Bukti Transfer</h2>
                            <p class="text-xs text-slate-500">Lampirkan foto atau screenshot bukti transfer</p>
                        </div>
                    </div>

                    <label for="bukti" id="dropzone"
                           class="mt-5 flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/60 px-6 py-10 text-center transition hover:border-[#1E7BC8] hover:bg-[#F2F7FD]">
                        <x-icon name="upload" class="h-8 w-8 text-slate-300" />
                        <p class="mt-3 text-sm text-slate-500">
                            <span class="font-semibold text-[#1E7BC8]">Klik untuk upload</span> bukti pembayaran atau drag &amp; drop file di sini.
                        </p>
                        <p class="mt-1 text-xs text-slate-400">JPG, PNG, PDF | Maksimal 5 MB</p>
                        <input id="bukti" name="bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" class="hidden">
                    </label>

                    <div id="filePreview" class="mt-4 hidden items-center gap-3 rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#EAF3FC] text-[#1E7BC8]">
                            <x-icon name="clipboard" class="h-5 w-5" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p id="fileName" class="truncate text-sm font-semibold text-[#1E293B]"></p>
                            <p class="text-xs text-slate-400"><span id="fileSize"></span> &bull; <span class="font-medium text-[#1E7BC8]">Berhasil dipilih</span></p>
                        </div>
                        <button type="button" id="fileRemove" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-500" aria-label="Hapus berkas">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>

                    @error('bukti')
                        <p class="mt-3 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kanan --}}
            <div class="space-y-5">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100">
                    <h2 class="text-base font-bold text-[#1E293B]">Rincian Biaya</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-slate-500">Subtotal</dt><dd class="font-medium text-slate-700">{{ $rp($pesanan['subtotal'] ?? 0) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-slate-500">Ongkos Jemput</dt><dd class="font-medium text-slate-700">{{ $rp($pesanan['ongkos_jemput'] ?? 0) }}</dd></div>
                        <div class="mt-2 flex justify-between border-t border-dashed border-slate-200 pt-3">
                            <dt class="text-base font-bold text-[#1E293B]">Total</dt>
                            <dd class="text-base font-bold text-[#1566AD]">{{ $rp($pesanan['total'] ?? $pesanan['subtotal'] ?? 0) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-2xl bg-[#1E7BC8] p-5 text-sm text-white shadow-sm">
                    <div class="flex gap-3">
                        <x-icon name="check-circle" class="h-5 w-5 shrink-0" />
                        <p class="leading-relaxed text-white/95">Setelah pesanan dikirim, admin akan memverifikasi pembayaran Anda. Status pesanan akan diperbarui setelah pembayaran berhasil diverifikasi.</p>
                    </div>
                </div>

                <div id="tips-transfer" class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-base">💡</span>
                        <h3 class="text-sm font-bold text-[#1E293B]">Tips Pembayaran</h3>
                    </div>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" /> Pastikan nominal transfer sesuai.</li>
                        <li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" /> Gunakan bukti transfer yang jelas.</li>
                        <li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" /> Pastikan tanggal pembayaran benar.</li>
                        <li class="flex gap-2"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" /> Jangan mengedit atau memotong bukti transfer.</li>
                    </ul>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="mt-2 flex items-center justify-between gap-4 lg:col-span-3">
                <a href="{{ route('pesanan.ringkasan') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-6 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    <x-icon name="arrow-left" class="h-4 w-4" /> Kembali
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#0F2A4A] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#1566AD] focus:outline-none focus:ring-2 focus:ring-[#1E7BC8]/40 focus:ring-offset-2">
                    Selesaikan Pesanan <x-icon name="arrow-right" class="h-4 w-4" />
                </button>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('bukti');
        var preview = document.getElementById('filePreview');
        var nameEl = document.getElementById('fileName');
        var sizeEl = document.getElementById('fileSize');
        var removeBtn = document.getElementById('fileRemove');
        var dropzone = document.getElementById('dropzone');
        var kartuBukti = document.getElementById('kartu-bukti');
        var tipsTransfer = document.getElementById('tips-transfer');

        function formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(0) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function showFile(file) {
            nameEl.textContent = file.name;
            sizeEl.textContent = formatSize(file.size);
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        }

        function syncBayar() {
            var metode = document.querySelector('input[name=metode_bayar]:checked').value;
            document.querySelectorAll('.bayar-opsi').forEach(function (label) {
                var dipilih = label.querySelector('input').checked;
                label.classList.toggle('border-[#1E7BC8]', dipilih);
                label.classList.toggle('bg-[#F2F7FD]', dipilih);
                label.classList.toggle('border-slate-200', ! dipilih);
                label.classList.toggle('bg-white', ! dipilih);
            });
            var transfer = metode === 'transfer';
            kartuBukti.style.display = transfer ? '' : 'none';
            if (tipsTransfer) { tipsTransfer.style.display = transfer ? '' : 'none'; }
            input.required = transfer;
            if (! transfer) {
                input.value = '';
                preview.classList.add('hidden');
                preview.classList.remove('flex');
            }
        }

        document.querySelectorAll('input[name=metode_bayar]').forEach(function (r) {
            r.addEventListener('change', syncBayar);
        });

        input.addEventListener('change', function () {
            if (input.files && input.files[0]) showFile(input.files[0]);
        });
        removeBtn.addEventListener('click', function () {
            input.value = '';
            preview.classList.add('hidden');
            preview.classList.remove('flex');
        });
        ['dragover', 'dragenter'].forEach(function (ev) {
            dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.add('border-[#1E7BC8]', 'bg-[#F2F7FD]');
            });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            dropzone.addEventListener(ev, function (e) {
                e.preventDefault();
                dropzone.classList.remove('border-[#1E7BC8]', 'bg-[#F2F7FD]');
            });
        });
        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                input.files = e.dataTransfer.files;
                showFile(e.dataTransfer.files[0]);
            }
        });

        syncBayar();
    })();
</script>
@endpush
@endsection
