<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSoal extends Model
{
    protected $table = 'kategori_soal';

    protected $fillable = [
        'nama_kategori',
        'tipe_kriteria',
        'bobot',
        'minimal_benar',
        'metode' // pg atau gmeet
    ];

    public function soal()
    {
        return $this->hasMany(Soal::class, 'kategori_id');
    }

    public function hasilTes()
    {
        return $this->hasMany(HasilTesSantri::class, 'kategori_id');
    }
}
