<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Angkatan;
use App\Models\Distribusi;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'angkatan_id',
        'rombel'
    ];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $angkatan = $this->angkatan;

        if (!$angkatan || !$angkatan->prodi) {
            return '-';
        }

        return $angkatan->prodi->kode_prodi
            . '-' . $angkatan->tahun_masuk
            . '-' . $this->rombel;
    }

    public function getProdiIdAttribute()
    {
        return $this->angkatan?->prodi_id;
    }
}