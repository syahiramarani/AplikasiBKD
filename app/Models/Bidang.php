<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected $fillable = ['nama'];

    /** Bidang "Umum" dianggap kategori MKDU, sisanya kategori Jurusan */

    public function dosens()
    {
        return $this->belongsToMany(
            Dosen::class,
            'dosen_bidangs',
            'bidang_id',
            'dosen_id'
        );
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
}
