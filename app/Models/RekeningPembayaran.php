<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningPembayaran extends Model
{
    protected $table = 'rekening_pembayaran';

    protected $fillable = [
        'bank',
        'nomor_rekening',
        'atas_nama',
        'qris'
    ];

    public function pembayaranSantri()
    {
        return $this->hasMany(PembayaranSantri::class, 'rekening_id');
    }
}
