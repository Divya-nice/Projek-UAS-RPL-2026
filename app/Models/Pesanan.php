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
        'nama',
        'nomor_hp',
        'alamat',
        'jumlah_sepatu',
        'ukuran_sepatu',
        'foto_sepatu',
        'metode_pengantaran',
        'pin_lokasi',
        'status',
    ];


    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}