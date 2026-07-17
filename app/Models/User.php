<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Field yang boleh diisi
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ];

    /**
     * Field yang disembunyikan saat JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const ROLE_PPMPP = 'p4m';
    public const ROLE_KAJUR = 'kajur';
    public const ROLE_KAPRODI = 'kaprodi';

    public function isPpmpp(): bool
    {
        return $this->role === self::ROLE_PPMPP;
    }
    public function isKajur(): bool
    {
        return $this->role === self::ROLE_KAJUR;
    }
    public function isKaprodi(): bool
    {
        return $this->role === self::ROLE_KAPRODI;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    } // opsional
}
