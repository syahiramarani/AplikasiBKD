<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';

    protected $fillable = ['prodi_id', 'kode_mk', 'nama_mk', 'sks', 'jam', 'semester', 'bidang_id'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class, 'mata_kuliah_id');
    }

    /** Kategori matkul ikut kategori bidangnya */
    public function getKategoriAttribute(): string
    {
        return $this->bidang?->kategori ?? 'Jurusan';
    }

    /** Semester ajaran (Ganjil/Genap) diturunkan dari angka semester */
    public function getSemesterAjaranAttribute(): string
    {
        return $this->semester % 2 !== 0 ? 'Ganjil' : 'Genap';
    }

    public function scopeUntukProdiSemester($query, int $prodiId, int $semesterKe)
    {
        return $query->where('prodi_id', $prodiId)->where('semester', $semesterKe);
    }

    public function scopeSemesterAjaran($query, string $ajaran)
    {
        $list = $ajaran === 'Ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];
        return $query->whereIn('semester', $list);
    }

}
