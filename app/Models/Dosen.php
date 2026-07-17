<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\BebanDosen;
use App\Models\Distribusi;
use App\Models\Bidang;
use App\Models\Kelas;

class Dosen extends Model
{

    protected $fillable = [
        'nama_dosen',
        'nip',
        'status',
        'jabatan',
        'jurusan_id',
        'prodi_id',
        'beban_dosen_id',
    ];

    /* RELASI */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    public function totalSksGlobal(): int
    {
        return Distribusi::where('dosen_id', $this->id)
            ->sum('sks');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function bebanDosen()
    {
        return $this->belongsTo(BebanDosen::class, 'beban_dosen_id');
    }


    public function getMaxSksAttribute()
    {
        if (!$this->beban) {
            return $this->status === 'DT' ? 12 : 16;
        }

        // 🔥 DT biasanya lebih kecil atau fixed
        if ($this->status === 'DT') {
            return $this->beban->max_sks_dt ?? 12;
        }

        return $this->beban->max_sks_ds ?? $this->beban->max_sks ?? 16;
    }
    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    public function bidangs()
    {
        return $this->belongsToMany(
            Bidang::class,
            'dosen_bidangs',
            'dosen_id',
            'bidang_id'
        );
    }

    public function dosenBidang()
    {
        return $this->hasMany(DosenBidang::class, 'dosen_id');
    }
    /* CORE AG LOGIC */
    public function cocokDenganBidang(int $bidangId): bool
    {
        return $this->bidangs()
            ->where('bidang_id', $bidangId)
            ->exists();
    }


    public function totalSks(int $tahunAjaranId): int
    {
        return $this->distribusi()
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->sum('sks');
    }

}