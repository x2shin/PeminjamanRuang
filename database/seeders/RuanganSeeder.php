<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan; // Jangan lupa tambahkan ini

class RuanganSeeder extends Seeder
{
    public function run()
    {
        Ruangan::create([
            'nama_ruangan' => 'Laboratorium Komputer 1',
            'kapasitas' => 36,
            'status' => 'tersedia'
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Aula Utama',
            'kapasitas' => 200,
            'status' => 'tersedia'
        ]);

        Ruangan::create([
            'nama_ruangan' => 'Ruang Audio Visual',
            'kapasitas' => 50,
            'status' => 'tersedia'
        ]);
    }
}