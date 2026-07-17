<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    protected $table = 'distribusi';

    protected $fillable = [
        'batch_id',
        'dosen_id',
        'prodi_id',
        'tahun_ajaran_id',
        'mata_kuliah_id',
        'kelas_id',
        'sks',
        'jam',
        'sumber',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
    public function totalSks($semester = null)
    {
        return $this->distribusi()
            ->when($semester, function ($q) use ($semester) {
                $q->whereHas('mataKuliah', function ($mk) use ($semester) {
                    $mk->where('semester', $semester);
                });
            })
            ->sum('sks');
    }
    public function totalSksAktif($semester = null)
    {
        return Distribusi::where('dosen_id', $this->id)
            ->when($semester, function ($q) use ($semester) {
                $q->whereHas('mataKuliah', function ($mk) use ($semester) {
                    $mk->where('semester', $semester);
                });
            })
            ->sum('sks');
    }
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }       // prodi tujuan (matkul)
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /** Apakah baris ini hasil mengajar lintas prodi (prodi dosen != prodi tujuan) */
    public function getLintasProdiAttribute(): bool
    {
        return $this->dosen && $this->dosen->prodi_id !== $this->prodi_id;
    }

    /** Semester ajaran (Ganjil/Genap) ikut dari mata kuliah */
    public function getSemesterAjaranAttribute(): string
    {
        if (!$this->mataKuliah) {
            return '-';
        }

        return $this->mataKuliah->semester % 2 === 0
            ? 'Genap'
            : 'Ganjil';
    }

    public function scopeTahunAjaran($query, int $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    public function scopeSemesterAjaran($query, string $ajaran)
    {
        $list = $ajaran === 'Ganjil' ? [1, 3, 5, 7] : [2, 4, 6, 8];
        return $query->whereHas('mataKuliah', fn($q) => $q->whereIn('semester', $list));
    }
}
