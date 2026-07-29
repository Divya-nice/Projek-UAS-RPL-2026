<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom ini menampung isi textarea "Catatan Admin (Internal)" pada
     * halaman detail pesanan. Sebelumnya textarea tersebut tidak
     * memiliki atribut `name` sehingga isinya selalu hilang saat form
     * disimpan (tidak benar-benar tersambung ke database).
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('catatan_admin');
        });
    }
};