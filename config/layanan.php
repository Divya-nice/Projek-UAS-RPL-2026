<?php

/*
|--------------------------------------------------------------------------
| Data Layanan Cuci Sepatu
|--------------------------------------------------------------------------
| Sumber data statis untuk katalog layanan, ongkos jemput per kecamatan,
| keunggulan, dan informasi kontak. Dipakai bersama oleh controller & view
| agar konsisten dan mudah dirawat (single source of truth).
*/

return [

    // Informasi umum / kontak (dipakai di beranda & footer)
    'kontak' => [
        'alamat'      => 'Jl. Nirbaya, Kota Pontianak, Kalimantan Barat',
        'whatsapp'    => '0895-6004-47505',
        'operasional' => 'Senin - Sabtu, 08.00 - 17.00 WIB',
        'sosial'      => '@cucisepatupontianak',
        'rekening'    => [
            'bank'   => 'BCA',
            'nomor'  => '1234 5678 9012',
            'nama'   => 'Cuci Sepatu PTK',
        ],
    ],

    // Katalog treatment / layanan (halaman Katalog & Form)
    'layanan' => [
        'one-day-service' => [
            'nama'     => 'One Day Service',
            'harga'    => 50000,
            'estimasi' => '1 Hari',
            'populer'  => true,
            'kategori' => 'Reguler',
            'deskripsi'=> 'Pembersihan cepat selesai dalam 1 hari untuk kebutuhan mendesak Anda.',
        ],
        'deep-cleaning-regular' => [
            'nama'     => 'Deep Cleaning / Regular',
            'harga'    => 35000,
            'estimasi' => '2 Hari',
            'populer'  => false,
            'kategori' => 'Reguler',
            'deskripsi'=> 'Pembersihan menyeluruh untuk semua jenis sepatu dengan teknik laundry terbaik.',
        ],
        'unyellowing-whitening' => [
            'nama'     => 'Unyellowing / Whitening',
            'harga'    => 50000,
            'estimasi' => '2 Hari',
            'populer'  => false,
            'kategori' => 'Perawatan',
            'deskripsi'=> 'Menghilangkan noda kuning pada midsole dan mencerahkan sepatu seperti baru.',
        ],
        'women-shoes-sandal' => [
            'nama'     => 'Women Shoes / Sandal',
            'harga'    => 25000,
            'estimasi' => '2 Hari',
            'populer'  => false,
            'kategori' => 'Perawatan',
            'deskripsi'=> 'Perawatan khusus sepatu wanita dan sandal dengan material sensitif.',
        ],
        'kids-shoes-sandal' => [
            'nama'     => 'Kids Shoes / Sandal',
            'harga'    => 25000,
            'estimasi' => '2 Hari',
            'populer'  => false,
            'kategori' => 'Perawatan',
            'deskripsi'=> 'Perawatan aman dan lembut untuk sepatu anak menggunakan bahan ramah lingkungan.',
        ],
        'leather-care' => [
            'nama'     => 'Leather Care',
            'harga'    => 45000,
            'estimasi' => '2 Hari',
            'populer'  => false,
            'kategori' => 'Perawatan',
            'deskripsi'=> 'Perawatan khusus sepatu kulit agar tetap awet, lentur, dan berkilau alami.',
        ],
        'cap-cleaning' => [
            'nama'     => 'Cap Cleaning',
            'harga'    => 30000,
            'estimasi' => '1 Hari',
            'populer'  => false,
            'kategori' => 'Reguler',
            'deskripsi'=> 'Pembersihan topi dari noda, keringat dan kotoran.',
        ],
        'repaint' => [
            'nama'     => 'Repaint',
            'harga'    => 85000,
            'estimasi' => '3-5 Hari',
            'populer'  => false,
            'kategori' => 'Premium',
            'deskripsi'=> 'Pengecatan ulang sepatu agar tampak seperti baru.',
        ],
        'sole-repair' => [
            'nama'     => 'Sole Repair',
            'harga'    => 45000,
            'estimasi' => '2-4 Hari',
            'populer'  => false,
            'kategori' => 'Premium',
            'deskripsi'=> 'Perbaiki sol sepatu yang lepas atau rusak.',
        ],
    ],

    // Ongkos jemput per kecamatan (halaman Form - metode pengantaran)
    'ongkos_jemput' => [
        'Pontianak Selatan'  => 10000,
        'Pontianak Kota'     => 10000,
        'Pontianak Tenggara' => 12000,
        'Pontianak Barat'    => 15000,
        'Pontianak Timur'    => 15000,
        'Pontianak Utara'    => 20000,
        'Sungai Raya'        => 12000,
    ],

    // Keunggulan (section "Mengapa Memilih Kami" pada beranda)
    'keunggulan' => [
        ['icon' => 'shield-check', 'judul' => 'Kualitas Terjamin',  'teks' => 'Hasil pembersihan maksimal dengan standar operasional yang ketat.'],
        ['icon' => 'truck',        'judul' => 'Antar Jemput',       'teks' => 'Hemat waktu Anda dengan layanan kurir antar jemput khusus area Pontianak.'],
        ['icon' => 'bolt',         'judul' => 'Proses Cepat',       'teks' => 'Layanan yang efisien tanpa mengesampingkan detail dan kebersihan.'],
        ['icon' => 'wallet',       'judul' => 'Pembayaran Mudah',   'teks' => 'Berbagai pilihan metode pembayaran digital maupun tunai.'],
        ['icon' => 'headset',      'judul' => 'Pelayanan Ramah',    'teks' => 'Customer service kami siap membantu kendala dan pertanyaan Anda.'],
    ],

    // Syarat & ketentuan layanan (section pada beranda)
    'syarat' => [
        'Sepatu akan diperiksa sebelum proses pencucian dimulai.',
        'Estimasi pengerjaan menyesuaikan jenis layanan yang dipilih.',
        'Pelanggan dapat memilih antar sendiri atau dijemput pemilik.',
        'Biaya penjemputan ditentukan berdasarkan wilayah pelanggan.',
        'Pembayaran dapat dilakukan secara tunai maupun transfer.',
        'Bukti pembayaran wajib diunggah untuk metode transfer.',
        'Pesanan dapat dibatalkan sebelum proses pencucian dimulai.',
        'Kerusakan yang sudah ada sebelum pencucian bukan menjadi tanggung jawab penyedia layanan.',
        'Setelah sepatu diterima, pelanggan disarankan segera membuka kemasan plastik penyimpanan agar sirkulasi udara tetap baik dan mengurangi risiko timbulnya bau atau jamur akibat kelembapan.',
    ],

];
