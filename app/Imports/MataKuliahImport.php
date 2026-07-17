<?php

namespace App\Imports;

use App\Models\MataKuliah;
use App\Models\Prodi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MataKuliahImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $prodi = Prodi::where('nama_prodi', $row['prodi'])->first();

        if (!$prodi) {
            return null;
        }

        return new MataKuliah([
            'prodi_id' => $prodi->id,
            'kode_mk' => $row['kode_mk'],
            'nama_mk' => $row['nama_mk'],
            'sks' => $row['sks'],
            'jam' => $row['jam'],
            'bidang' => $row['bidang'] ?? null,
            'semester' => $row['semester'] ?? null,
        ]);
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}