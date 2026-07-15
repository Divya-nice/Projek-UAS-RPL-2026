<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    /**
     * Menampilkan semua layanan.
     */
    public function index()
    {
        $layanan = Layanan::all();

        return view('pesanan.katalog', [
            'layanan' => $layanan
        ]);
    }
}

