<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPembayaran extends Model
{
    protected $table = 'pengaturan_pembayaran';

    protected $fillable = [
        'jenis',
        'nominal',
        'tahun_akademik_id',
    ];

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }
}
