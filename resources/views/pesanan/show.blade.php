<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white rounded-lg shadow p-6">

    <h1 class="text-2xl font-bold mb-4 text-blue-600">
        Pesanan Berhasil Dibuat
    </h1>

    <p class="mb-2">
        <b>Kode Pesanan:</b> #{{ $pesanan->id }}
    </p>

    <p class="mb-2">
        <b>Nama:</b> {{ $pesanan->nama }}
    </p>

    <p class="mb-2">
        <b>Nomor HP:</b> {{ $pesanan->nomor_hp }}
    </p>

    <p class="mb-2">
        <b>Alamat:</b> {{ $pesanan->alamat }}
    </p>

    <hr class="my-4">

    <h2 class="text-lg font-semibold mb-2">
        Detail Layanan
    </h2>

    <p class="mb-2">
        <b>Layanan:</b>
        {{ $pesanan->layanan->nama_layanan }}
    </p>

    <p class="mb-2">
        <b>Harga:</b>
        Rp{{ number_format($pesanan->layanan->harga, 0, ',', '.') }}
    </p>

    <p class="mb-2">
        <b>Estimasi:</b>
        {{ $pesanan->layanan->estimasi }}
    </p>

    <p class="mb-2">
        <b>Jumlah Sepatu:</b>
        {{ $pesanan->jumlah_sepatu }} pasang
    </p>

    <p class="mb-2">
        <b>Ukuran Sepatu:</b>
        {{ $pesanan->ukuran_sepatu }}
    </p>

    <p class="mb-2">
        <b>Metode Pengantaran:</b>
        {{ ucfirst($pesanan->metode_pengantaran) }}
    </p>

    @if($pesanan->pin_lokasi)
    <p class="mb-2">
        <b>Pin Lokasi:</b>
        {{ $pesanan->pin_lokasi }}
    </p>
    @endif

    @if($pesanan->foto_sepatu)
    <p class="mb-2">
        <b>Foto Sepatu:</b>
    </p>

    <img 
        src="{{ asset('storage/'.$pesanan->foto_sepatu) }}"
        class="w-40 rounded"
    >
    @endif

    <hr class="my-4">

    <p class="font-bold text-orange-600">
        Status:
        {{ $pesanan->status }}
    </p>
<p class="font-bold text-orange-600">
    Status:
    {{ $pesanan->status }}
</p>

<div class="mt-6 flex gap-3">
    <a href="{{ route('pesanan.beranda') }}"
       class="rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-300">
        Kembali ke Beranda
    </a>

    <a href="{{ route('pesanan.riwayat') }}"
       class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
        Riwayat Pesanan
    </a>
</div>

</div>   {{-- ini penutup card --}}
</body>
</html>

</div>

</body>
</html>