<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun',
        'aktif',
    ];

    public function pembayaran()
    {
        return $this->hasMany(PengaturanPembayaran::class, 'tahun_akademik_id');
    }

    public function timeline()
    {
        return $this->hasMany(TimelineSeleksi::class, 'tahun_akademik_id');
    }

    public function pengumuman()
    {
        return $this->hasMany(PengumumanHasil::class, 'tahun_akademik_id');
    }
}
