<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {

            $table->string('nomor_pesanan')
                ->unique()
                ->after('layanan_id');

            $table->string('wilayah')
                ->nullable()
                ->after('alamat');

            $table->string('bukti_pembayaran')
                ->nullable()
                ->after('updated_at');

            $table->enum('status_pembayaran', [
                'menunggu_upload',
                'menunggu_verifikasi',
                'diterima',
                'ditolak'
            ])
            ->default('menunggu_upload')
            ->after('bukti_pembayaran');

        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_pesanan',
                'wilayah',
                'bukti_pembayaran',
                'status_pembayaran'
            ]);
        });
    }
};