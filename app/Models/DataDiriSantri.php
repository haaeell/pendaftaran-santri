<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDiriSantri extends Model
{
    protected $table = 'data_diri_santri';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'kabupaten_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_domisili',
        'email',
        'no_telp',
        'nik',
        'nisn',
        'foto_diri',
        'foto_kk',
        'instansi_1',
        'instansi_2',
        'instansi_3',
        'prestasi_1',
        'prestasi_2',
        'prestasi_3',
        'penyakit_khusus',
        'hubungan_wali',
        'nama_wali',
        'rata_rata_penghasilan',
        'no_telp_wali',
        'info_alumni',
        'info_saudara',
        'info_instagram',
        'info_tiktok',
        'info_youtube',
        'info_facebook',
        'info_lainnya',
        'pendidikan_tujuan',
        'status_seleksi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'info_alumni' => 'boolean',
        'info_saudara' => 'boolean',
        'info_instagram' => 'boolean',
        'info_tiktok' => 'boolean',
        'info_youtube' => 'boolean',
        'info_facebook' => 'boolean',
        'info_lainnya' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
