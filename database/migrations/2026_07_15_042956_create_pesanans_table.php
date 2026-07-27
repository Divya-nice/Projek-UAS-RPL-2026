<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelanggan (users)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            // Relasi ke layanan
            $table->foreignId('layanan_id')
                  ->constrained('layanans')
                  ->cascadeOnDelete();

            // Nomor pesanan
            $table->string('nomor_pesanan')->unique();

            // Data pelanggan
            $table->string('nama');
            $table->string('nomor_hp');
            $table->text('alamat');
            $table->string('wilayah')->nullable();

            // Data sepatu
            $table->integer('jumlah_sepatu');
            $table->string('ukuran_sepatu');

            // Foto sepatu
            $table->string('foto_sepatu')->nullable();

            // Metode pengantaran
            $table->enum('metode_pengantaran', [
                'antar',
                'jemput'
            ]);

            // Pin lokasi (jika dijemput)
            $table->string('pin_lokasi')->nullable();

            // Total biaya
            $table->decimal('total_biaya', 10, 2)->default(0);

            // Status pesanan
            $table->string('status')
                  ->default('Menunggu Pembayaran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};