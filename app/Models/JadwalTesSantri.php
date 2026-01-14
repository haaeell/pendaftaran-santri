<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTesSantri extends Model
{
    protected $table = 'jadwal_tes_santri';

    protected $fillable = [
        'user_id',
        'waktu_mulai',
        'waktu_selesai',
        'sudah_mulai',
        'link_gmeet',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'sudah_mulai' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
