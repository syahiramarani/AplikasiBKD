<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    protected $fillable = [
        'prodi_id',
        'tahun_masuk',
        'jumlah_kelas'
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'angkatan_id');
    }
}