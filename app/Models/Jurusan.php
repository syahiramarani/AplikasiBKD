<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $fillable = ['kode_jurusan', 'nama_jurusan'];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }
    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }
}
