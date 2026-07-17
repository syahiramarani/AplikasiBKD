<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\TahunAjaran;

class DistribusiHistory extends Model
{
    protected $table = 'distribusi_beban_history';

    protected $fillable = [
        'batch_id',
        'tahun_ajaran_id',
        'prodi_id',
        'semester',
        'dosen_id',
        'mata_kuliah_id',
        'kelas_id',
        'sks',
        'jam',
        'sumber'
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

}








