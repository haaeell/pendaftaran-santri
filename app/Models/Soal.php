<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';

    protected $fillable = [
        'kategori_id',
        'pertanyaan',
        'pilihan',
        'jawaban',
    ];

    protected $casts = [
        'pilihan' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSoal::class, 'kategori_id');
    }
}
