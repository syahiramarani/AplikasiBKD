<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BebanDosen extends Model
{
    protected $table = 'beban_dosen';

    protected $fillable = [
        'nama',
        'min_sks',
        'max_sks'
    ];

    public function dosens()
    {
        return $this->hasMany(Dosen::class, 'beban_dosen_id');
    }
}