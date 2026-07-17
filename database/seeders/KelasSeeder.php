<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('kelas')->truncate(); // 👈 penting

        $prodiList = DB::table('prodis')->get();

        foreach ($prodiList as $prodi) {

            for ($semester = 1; $semester <= 8; $semester++) {

                foreach (['A', 'B', 'C', 'D'] as $rombel) {

                    DB::table('kelas')->updateOrInsert(
                        [
                            'prodi_id' => $prodi->id,
                            'semester' => $semester,
                            'nama_kelas' => $rombel,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
