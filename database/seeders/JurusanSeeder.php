<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jurusan; // Import model Jurusan

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama (opsional, tapi bagus untuk testing)
        // Jurusan::truncate(); 

        $jurusans = [
            ['kode' => 'TKJ', 'nama_jurusan' => 'Teknik Komputer Jaringan'],
            ['kode' => 'RPL', 'nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'MM', 'nama_jurusan' => 'Multimedia'],
            ['kode' => 'TJA', 'nama_jurusan' => 'Teknik Jaringan Akses'],
        ];

        // Masukkan data ke database
        foreach ($jurusans as $jurusan) {
            Jurusan::create($jurusan);
        }
    }
}