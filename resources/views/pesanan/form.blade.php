@extends('layouts.app')

@section('title', 'Form Pemesanan — Cuci Sepatu')

@section('content')

@php
$petaHarga = collect($layanan)->mapWithKeys(function ($i, $slug) {
    return [
        $slug => [
            'nama'   => $i['nama'],
            'harga'  => $i['harga'],
            'gambar' => $i['gambar'] ?? null,
        ]
    ];
});

$terpilih = $layanan[$slugTerpilih];
@endphp

<section class="bg-[#F5F8FD] py-10 min-h-screen">

    <div class="mx-auto max-w-6xl px-4">

        {{-- Breadcrumb --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-400">
            <a href="{{ route('pesanan.beranda') }}" class="hover:text-[#1566AD]">
                Beranda
            </a>

            <span>></span>

            <a href="{{ route('pesanan.katalog') }}" class="hover:text-[#1566AD]">
                Pesan Layanan
            </a>

            <span>></span>

            <span class="font-semibold text-[#1566AD]">
                Data Pesanan
            </span>
        </nav>

        <h1 class="text-[42px] font-bold text-[#112A5A] leading-none">
            Data Pesanan
        </h1>

        <p class="mt-3 text-slate-500">
            Lengkapi data pesanan Anda sebelum melanjutkan ke proses pengantaran.
        </p>

        {{-- Step Indicator --}}
        <div class="mt-10">
            <x-step-indicator :current="1"/>
        </div>

        {{-- Error --}}
        @if($errors->any())
            <div class="mt-8 rounded-xl border border-red-200 bg-red-50 p-5 text-red-600">

                <p class="font-semibold">
                    Mohon periksa kembali isian Anda.
                </p>

                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <form
            id="form-pesanan"
            method="POST"
            action="{{ route('pesanan.form.proses') }}"
            enctype="multipart/form-data"
            class="mt-8">

            @csrf

            <input
                type="hidden"
                name="layanan"
                id="input-layanan">

            <div class="grid gap-8 lg:grid-cols-3">

                {{-- ========================= --}}
                {{-- SIDEBAR --}}
                {{-- ========================= --}}

                <aside>

                    <div class="sticky top-24 space-y-5">

                        {{-- Ringkasan --}}
                        <div class="rounded-xl bg-white shadow-lg shadow-slate-100 overflow-hidden">

                            <div class="p-6">

                                <h2 class="text-xl font-bold text-[#112A5A]">
                                    Ringkasan Layanan
                                </h2>

                                @if(!empty($terpilih['gambar']))

                                    <img
                                        id="ringkas-gambar"
                                        src="{{ asset('storage/'.$terpilih['gambar']) }}"
                                        alt="{{ $terpilih['nama'] }}"
                                        class="mt-6 h-56 w-full rounded-2xl object-cover">

                                @else

                                    <x-shoe-thumb
                                        id="ringkas-gambar"
                                        :slug="\Illuminate\Support\Str::slug($terpilih['nama'])"
                                        class="mt-6 h-56 w-full rounded-2xl"/>

                                @endif

                                <p
                                    id="ringkas-nama"
                                    class="mt-5 text-[#1566AD] font-semibold">

                                    {{ $terpilih['nama'] }}

                                </p>

                                <p
                                    id="ringkas-harga"
                                    class="mt-1 text-xl font-bold text-[#112A5A]">

                                    {{ \App\Http\Controllers\PesananController::rupiah($terpilih['harga']) }}

                                </p>

                                {{-- Dropdown layanan --}}
                                <select
                                    id="pilih-layanan"
                                    class="mt-6 w-full rounded-xl border border-[#1566AD] bg-white px-4 py-3 text-center font-semibold text-[#1566AD]">

                                    @foreach($layanan as $slug => $item)

                                        <option
                                            value="{{ $slug }}"
                                            @selected($slug==$slugTerpilih)>

                                            {{ $item['nama'] }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        {{-- Informasi --}}
                        <div class="rounded-xl border border-blue-100 bg-[#EEF5FF] p-5">

                            <div class="flex items-start gap-3">

                                <x-icon
                                    name="information-circle"
                                    class="mt-0.5 h-5 w-5 text-[#1566AD]" />

                                <p class="text-sm leading-7 text-[#1566AD]">

                                    Informasi: Pastikan seluruh data yang
                                    dimasukkan sudah benar sebelum melanjutkan
                                    ke langkah berikutnya.

                                </p>

                            </div>

                        </div>

                    </div>

                </aside>

                {{-- ========================= --}}
                {{-- KONTEN --}}
                {{-- ========================= --}}

                <div class="space-y-6 lg:col-span-2">

                    {{-- Data Pelanggan --}}
                    <div class="rounded-xl bg-white p-6 shadow-lg shadow-slate-100">

                        <h2 class="text-lg font-bold text-[#112A5A]">
                            Data Pelanggan
                        </h2>

                        <div class="mt-3 border-b border-slate-200"></div>

                        <div class="mt-6 grid gap-5 sm:grid-cols-2">

                            {{-- Nama --}}
                            <div>

                                <label
                                    class="mb-2 block text-sm font-semibold text-[#243B6A]">

                                    Nama Lengkap *

                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    value="{{ old('nama') }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                    class="h-11 w-full rounded-lg border border-slate-300 bg-[#F8FAFD] px-4">

                            </div>

                            {{-- Telepon --}}
                            <div>

                                <label
                                    class="mb-2 block text-sm font-semibold text-[#243B6A]">

                                    Nomor Telepon / HP *

                                </label>

                                <input
                                    type="text"
                                    name="telepon"
                                    value="{{ old('telepon') }}"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                    class="h-11 w-full rounded-lg border border-slate-300 bg-[#F8FAFD] px-4">

                            </div>

                            {{-- Email --}}
                            <div class="sm:col-span-2">

                                <label
                                    class="mb-2 block text-sm font-semibold text-[#243B6A]">

                                    Email (Opsional)

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh@email.com"
                                    class="h-11 w-full rounded-xl border border-slate-300 bg-[#F8FAFD] px-4">

                            </div>

                            {{-- Alamat --}}
                            <div class="sm:col-span-2">

                                <label
                                    class="mb-2 block text-sm font-semibold text-[#243B6A]">

                                    Alamat Lengkap *

                                </label>

                                <textarea
                                    name="alamat"
                                    rows="3"
                                    required
                                    placeholder="Masukkan alamat lengkap penjemputan"
                                    class="w-full rounded-lg border border-slate-300 bg-[#F8FAFD] p-4">{{ old('alamat') }}</textarea>

                            </div>

                        </div>

                    </div>

{{-- ========================= --}}
{{-- Detail Pesanan --}}
{{-- ========================= --}}

<div class="rounded-xl bg-white p-6 shadow-lg shadow-slate-100">

    <h2 class="text-lg font-bold text-[#112A5A]">
        Detail Pesanan
    </h2>

    <div class="mt-3 border-b border-slate-200"></div>

    <div class="mt-6 space-y-5">

        {{-- Jenis Layanan --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-[#243B6A]">

                Jenis Layanan

            </label>

            <input
                id="detail-layanan"
                type="text"
                value="{{ $terpilih['nama'] }}"
                readonly
                class="h-11 w-full rounded-lg border border-slate-300 bg-[#EEF4FC] px-5 text-slate-600">

        </div>

        {{-- Jumlah --}}
        <div>

            <label
                class="mb-3 block text-sm font-semibold text-[#243B6A]">

                Jumlah Sepatu *

            </label>

            <div
                class="inline-flex overflow-hidden rounded-lg border border-slate-300">

                <button
                    type="button"
                    data-qty="minus"
                    class="flex h-10 w-10 items-center justify-center text-xl text-[#112A5A] hover:bg-slate-100">

                    −

                </button>

                <input
                    id="jumlah"
                    name="jumlah"
                    type="number"
                    min="1"
                    max="20"
                    readonly
                    value="{{ old('jumlah',1) }}"
                    class="h-10 w-12 border-x border-slate-300 text-center text-xl font-bold">

                <button
                    type="button"
                    data-qty="plus"
                    class="flex h-10 w-10 items-center justify-center bg-[#112A5A] text-2xl text-white hover:bg-[#1566AD]">

                    +

                </button>

            </div>

        </div>

        {{-- Ukuran --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-[#243B6A]">

                Ukuran Sepatu *

            </label>

            <div id="wadah-ukuran">

                <input
                    type="text"
                    name="ukuran[]"
                    required
                    placeholder="Contoh : Sepatu 1 : 42, Sepatu 2 : 39"
                    class="h-11 w-full rounded-lg border border-slate-300 bg-[#F8FAFD] px-4">

            </div>

        </div>

        {{-- Catatan --}}
        <div>

            <label
                class="mb-2 block text-sm font-semibold text-[#243B6A]">

                Catatan (Opsional)

            </label>

            <textarea
                name="catatan"
                rows="4"
                placeholder="Jelaskan tingkat kotoran atau keluhan spesifik pada sepatu"
                class="w-full rounded-lg border border-slate-300 bg-[#F8FAFD] p-4">{{ old('catatan') }}</textarea>

        </div>

    </div>

</div>

{{-- ========================= --}}
{{-- Upload Foto --}}
{{-- ========================= --}}

<div class="rounded-xl bg-white p-6 shadow-lg shadow-slate-100">

    <h2 class="text-lg font-bold text-[#112A5A]">

        Upload Foto Sepatu

    </h2>

    <div class="mt-6 grid gap-5 lg:grid-cols-3">

        {{-- Upload --}}
        <label
            for="foto"
            class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-white px-5 py-10 text-center lg:col-span-2">

            <x-icon
                name="upload"
                class="h-10 w-10 text-slate-300"/>

            <p
                class="mt-4 text-lg font-semibold text-[#243B6A]">

                Klik untuk upload foto atau drag & drop file di sini

            </p>

            <p
                class="mt-1 text-sm text-slate-500">

                JPG, PNG Maksimal 5 MB

            </p>

            <input
                id="foto"
                type="file"
                name="foto"
                accept=".jpg,.jpeg,.png"
                class="hidden">

        </label>

        {{-- Tips --}}
        <div
            class="rounded-ll bg-[#DDEBFF] p-5">

            <h4
                class="font-bold text-[#112A5A]">

                Tips Foto:

            </h4>

            <ul
                class="mt-3 space-y-2 text-sm text-[#243B6A]">

                <li class="flex gap-2">

                    <x-icon
                        name="check-circle"
                        class="h-5 w-5 text-[#1566AD]"/>

                    Foto tampak depan

                </li>

                <li class="flex gap-2">

                    <x-icon
                        name="check-circle"
                        class="h-5 w-5 text-[#1566AD]"/>

                    Foto tampak samping

                </li>

                <li class="flex gap-2">

                    <x-icon
                        name="check-circle"
                        class="h-5 w-5 text-[#1566AD]"/>

                    Foto bagian belakang

                </li>

                <li class="flex gap-2">

                    <x-icon
                        name="check-circle"
                        class="h-5 w-5 text-[#1566AD]"/>

                    Pastikan foto jelas dan terang

                </li>

            </ul>

        </div>

    </div>

    <ul
        id="daftar-foto"
        class="mt-5 space-y-3">

    </ul>

</div>

{{-- ========================= --}}
{{-- Tombol --}}
{{-- ========================= --}}
<div class="mt-8 flex items-center justify-end gap-8">
    <a
        href="{{ route('pesanan.katalog') }}"
        class="mr-80 rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">

        Kembali

    </a>

    <button
        type="submit"
        class="inline-flex items-center whitespace-nowrap rounded-lg bg-gradient-to-r from-[#1566AD] to-[#0F2A4A] px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:opacity-95">

        Lanjut ke Metode Pengantaran

        <x-icon
            name="arrow-right"
            class="h-4 w-4"/>
    </button>
</div>

</div>
</div>

</form>

</div>

</section>

@push('scripts')
<script>

(function(){

const petaHarga=@json($petaHarga);

const rupiah=(n)=>'Rp'+Number(n).toLocaleString('id-ID');

const inputLayanan=document.getElementById('input-layanan');

const pilih=document.getElementById('pilih-layanan');

const nama=document.getElementById('ringkas-nama');

const harga=document.getElementById('ringkas-harga');

const detail=document.getElementById('detail-layanan');

const gambar=document.getElementById('ringkas-gambar');

const jumlah=document.getElementById('jumlah');

const wadah=document.getElementById('wadah-ukuran');

function renderUkuran(){

    const total = parseInt(jumlah.value) || 1;

    const lama = [];

    wadah.querySelectorAll('input').forEach(input=>{
        lama.push(input.value);
    });

    wadah.innerHTML = "";

    for(let i=0;i<total;i++){

        const div=document.createElement("div");

        div.className="mb-4";

        div.innerHTML=`
            <label class="mb-2 block text-sm font-semibold text-[#243B6A]">
                Ukuran Sepatu ${i+1} *
            </label>

            <input
                type="text"
                name="ukuran[]"
                required
                value="${lama[i] ?? ''}"
                placeholder="Contoh : 42"
                class="h-11 w-full rounded-lg border border-slate-300 bg-[#F8FAFD] px-4">
        `;

        wadah.appendChild(div);

    }

}

function syncLayanan(){

const slug=pilih.value;

const data=petaHarga[slug];

if(!data)return;

inputLayanan.value=slug;

nama.textContent=data.nama;

harga.textContent=rupiah(data.harga);

detail.value=data.nama;

if(data.gambar && gambar.tagName==="IMG"){

gambar.src="{{ asset('storage') }}/"+data.gambar;

gambar.alt=data.nama;

}

}

syncLayanan();

pilih.addEventListener("change",syncLayanan);

document.querySelectorAll("[data-qty]").forEach(btn=>{

btn.addEventListener("click",()=>{

let val=parseInt(jumlah.value)||1;

if(btn.dataset.qty==="plus"){

val++;

}else{

val--;

}

if(val<1) val=1;
if(val>20) val=20;

jumlah.value=val;

renderUkuran();

});

});

const foto=document.getElementById("foto");

const daftar=document.getElementById("daftar-foto");

foto.addEventListener("change",()=>{

daftar.innerHTML="";

Array.from(foto.files).forEach(file=>{

const li=document.createElement("li");

li.className="rounded-xl bg-[#EEF4FC] px-5 py-3 flex justify-between";

li.innerHTML=`

<span class="truncate">${file.name}</span>

<span>${Math.round(file.size/1024)} KB</span>

`;

daftar.appendChild(li);

});

});

syncLayanan();

renderUkuran();

})();
</script>
@endpush

@endsection