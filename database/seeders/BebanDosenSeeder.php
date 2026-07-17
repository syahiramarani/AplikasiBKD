<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BebanDosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('beban_dosens')->insert([

            ['nama_beban' => 'DS', 'min_sks' => 9, 'max_sks' => 16],

            ['nama_beban' => 'Direktur', 'min_sks' => 3, 'max_sks' => 16],
            ['nama_beban' => 'Wakil Direktur', 'min_sks' => 4, 'max_sks' => 16],

            ['nama_beban' => 'Ketua Senat', 'min_sks' => 5, 'max_sks' => 16],
            ['nama_beban' => 'Ketua SPI', 'min_sks' => 5, 'max_sks' => 16],
            ['nama_beban' => 'Ketua Jurusan', 'min_sks' => 5, 'max_sks' => 16],
            ['nama_beban' => 'Kepala Pusat', 'min_sks' => 5, 'max_sks' => 16],

            ['nama_beban' => 'Kepala UPA', 'min_sks' => 6, 'max_sks' => 16],
            ['nama_beban' => 'Sekretaris Senat', 'min_sks' => 6, 'max_sks' => 16],
            ['nama_beban' => 'Sekretaris SPI', 'min_sks' => 6, 'max_sks' => 16],
            ['nama_beban' => 'Sekretaris Jurusan', 'min_sks' => 6, 'max_sks' => 16],
            ['nama_beban' => 'Ketua Program Studi', 'min_sks' => 6, 'max_sks' => 16],

            ['nama_beban' => 'Koordinator Pusat', 'min_sks' => 7, 'max_sks' => 16],
            ['nama_beban' => 'Kepala Laboratorium', 'min_sks' => 7, 'max_sks' => 16],

        ]);
    }
}