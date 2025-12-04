<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineSeleksi extends Model
{
    protected $table = 'timeline_seleksi';

    protected $fillable = [
        'tahun_akademik_id',
        'nama_gelombang',
        'mulai',
        'selesai',
    ];

    protected $casts = [
        'mulai' => 'date',
        'selesai' => 'date',
    ];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
