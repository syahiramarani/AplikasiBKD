<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = ['jurusan_id', 'kode_prodi', 'nama_prodi'];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }
    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class);
    }
    public function angkatans()
    {
        return $this->hasMany(Angkatan::class);
    }
    public function distribusi()
    {
        return $this->hasMany(Distribusi::class);
    }
}
