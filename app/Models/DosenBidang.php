<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenBidang extends Model
{
    protected $table = 'dosen_bidangs';

    protected $fillable = [
        'dosen_id',
        'bidang_id'
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}