<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        Layanan::create([
            'nama_layanan' => 'Deep Cleaning',
            'harga' => 35000,
            'estimasi' => '2-3 hari',
            'deskripsi' => 'Membersihkan sepatu secara menyeluruh'
        ]);

        Layanan::create([
            'nama_layanan' => 'One Day Service',
            'harga' => 50000,
            'estimasi' => '1 hari',
            'deskripsi' => 'Layanan cuci sepatu cepat selesai'
        ]);

        Layanan::create([
            'nama_layanan' => 'Repaint',
            'harga' => 85000,
            'estimasi' => '3-5 hari',
            'deskripsi' => 'Pengecatan ulang sepatu'
        ]);
    }
}