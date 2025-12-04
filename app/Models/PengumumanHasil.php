<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumumanHasil extends Model
{
    protected $table = 'pengumuman_hasil';

    protected $fillable = [
        'tahun_akademik_id',
        'tanggal_pengumuman',
        'status',
    ];

    protected $casts = [
        'tanggal_pengumuman' => 'date',
    ];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
