<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSantri extends Model
{
    protected $table = 'pembayaran_santri';

    protected $fillable = [
        'user_id',
        'jenis',
        'nominal_bayar',
        'rekening_id',
        'bukti_transfer',
        'status',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekening()
    {
        return $this->belongsTo(RekeningPembayaran::class, 'rekening_id');
    }
}
