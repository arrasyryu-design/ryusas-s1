<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Akun anggota (siswa/guru). Opsional, TIDAK dibutuhkan untuk melapor/melacak,
 * dan tidak punya akses ke panel Admin (guard "member" terpisah).
 */
class Member extends Authenticatable
{
    protected $fillable = ['name', 'identity_number', 'class_or_position', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }
}
