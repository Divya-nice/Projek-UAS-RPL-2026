<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat user dummy untuk testing login
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // User admin untuk login ke /admin/login (kolom is_admin baru)
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'is_admin' => true,
        ]);


        // Menambahkan data layanan cuci sepatu
        $this->call([
            LayananSeeder::class,
        ]);
    }
}