<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTesSantri extends Model
{
    protected $table = 'hasil_tes_santri';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nilai',
        'lulus_threshold',
        'jawaban',
    ];

    protected $casts = [
        'lulus_threshold' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriSoal::class, 'kategori_id');
    }
}
