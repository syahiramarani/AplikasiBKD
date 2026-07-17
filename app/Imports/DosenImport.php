<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // cari jurusan berdasarkan kode_jurusan
        $jurusan = Jurusan::whereRaw(
            'LOWER(TRIM(kode_jurusan)) = ?',
            [strtolower(trim($row['kode_jurusan']))]
        )->first();

        // jika jurusan tidak ditemukan
        if (!$jurusan) {
            return null;
        }

        // jika nama dosen kosong
        if (empty($row['nama_dosen'])) {
            return null;
        }

        // jika nip sudah ada
        $cekDosen = Dosen::where(
            'nip',
            $row['nip']
        )->first();

        if ($cekDosen) {
            return null;
        }

        return new Dosen([

            'jurusan_id' => $jurusan->id,
            'nama_dosen' => $row['nama_dosen'],
            'nip' => $row['nip'],
            'status' => $row['status'],
            'kategori_mengajar' => $row['kategori_mengajar'],

            // boleh kosong
            'jabatan' => $row['jabatan'] ?? null,

            // boleh kosong
            'keahlian' => $row['keahlian'] ?? null,
        ]);
    }
}