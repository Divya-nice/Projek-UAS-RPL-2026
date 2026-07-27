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
        'total_biaya',
        'status',
        'bukti_pembayaran',
        'status_pembayaran',
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
}