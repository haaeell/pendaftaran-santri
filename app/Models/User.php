<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'no_telp',
        'nik',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function dataDiri()
    {
        return $this->hasOne(DataDiriSantri::class, 'user_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranSantri::class, 'user_id');
    }

    public function jadwalTes()
    {
        return $this->hasOne(JadwalTesSantri::class, 'user_id');
    }

    public function hasilTes()
    {
        return $this->hasMany(HasilTesSantri::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSantri()
    {
        return $this->role === 'santri';
    }
}
