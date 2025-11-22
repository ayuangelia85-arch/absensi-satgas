<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim_nip',
        'email',
        'status',
        'role',
        'password',
    ];

    protected $hidden = ['password'];

    // 👉 Tambahkan ini agar Auth::attempt pakai nim_nip
    public function getAuthIdentifierName()
    {
        return 'nim_nip';
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
