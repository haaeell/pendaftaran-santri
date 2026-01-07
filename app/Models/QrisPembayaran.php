<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrisPembayaran extends Model
{
    protected $table = 'qris_pembayaran';
    protected $fillable = [
        'nama',
        'image',
        'aktif',
    ];
}
