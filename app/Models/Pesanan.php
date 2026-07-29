<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'layanan_id',
        'nomor_pesanan',
        'nama',
        'nomor_hp',
        'alamat',
        'wilayah',
        'jumlah_sepatu',
        'ukuran_sepatu',
        'foto_sepatu',
        'metode_pengantaran',
        'pin_lokasi',
        'ongkos_jemput',
        'total_biaya',
        'status',
        'catatan_admin',
        'bukti_pembayaran',
        'status_pembayaran',
        'metode_bayar',
    ];

    protected function casts(): array
    {
        return [
            'total_biaya' => 'decimal:2',
        ];
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias untuk kolom `metode_bayar`.
     *
     * Beberapa view (mis. admin.verifikasi.detail, admin.laporan.index)
     * memanggil `$pesanan->metode_pembayaran`, padahal kolom di database
     * bernama `metode_bayar`. Sebelumnya ini membuat pengecekan metode
     * COD/transfer di halaman verifikasi selalu bernilai null/false,
     * sehingga tampilan & aksi verifikasi untuk pesanan COD tidak pernah
     * muncul dengan benar. Accessor ini menyambungkan nama atribut yang
     * dipakai di view ke kolom yang sebenarnya ada, tanpa perlu mengubah
     * Blade maupun struktur tabel.
     */
    public function getMetodePembayaranAttribute(): ?string
    {
        return $this->metode_bayar;
    }
}