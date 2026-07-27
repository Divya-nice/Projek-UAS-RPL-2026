@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Upload Bukti Pembayaran</h2>

    <p>Nomor Pesanan: {{ $pesanan->nomor_pesanan }}</p>
    <p>Total Bayar: Rp{{ number_format($pesanan->total_biaya) }}</p>

    <form action="{{ route('pesanan.bukti.proses', $pesanan->nomor_pesanan) }}" 
          method="POST" 
          enctype="multipart/form-data">

        @csrf

        <label>Bukti Pembayaran</label>
        <input type="file" name="bukti" required>

        <button type="submit">
            Verifikasi
        </button>

    </form>
</div>

@endsection