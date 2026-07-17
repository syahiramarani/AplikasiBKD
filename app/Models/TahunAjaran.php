<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Distribusi;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajarans';

    protected $fillable = [
        'tahun_ajaran',
        'status_aktif'
    ];

    public function distribusi()
    {
        return $this->hasMany(Distribusi::class, 'tahun_ajaran_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 1);
    }

    public function history()
    {
        return $this->hasMany(DistribusiHistory::class);
    }
}
