<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\TahunAkademik;
use App\Models\User;
use App\Models\DataDiriSantri;
use App\Models\PengaturanPembayaran;
use App\Models\RekeningPembayaran;
use App\Models\PembayaranSantri;
use App\Models\KategoriSoal;
use App\Models\Soal;
use App\Models\TimelineSeleksi;
use App\Models\JadwalTesSantri;
use App\Models\HasilTesSantri;
use App\Models\PengumumanHasil;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | SEEDER: TAHUN AKADEMIK
        |--------------------------------------------------------------------------
        */
        $tahunAktif = TahunAkademik::create([
            'tahun' => '2025/2026',
            'aktif' => true,
        ]);

        TahunAkademik::create([
            'tahun' => '2024/2025',
            'aktif' => false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEEDER: USERS (Admin + Santri)
        |--------------------------------------------------------------------------
        */
        $admin = User::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@psb.test',
            'no_telp' => '0800000000',
            'nik' => '1234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SEEDER: PENGATURAN PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        $reg = PengaturanPembayaran::create([
            'jenis' => 'registrasi',
            'nominal' => 100000,
            'tahun_akademik_id' => $tahunAktif->id,
        ]);

        $du = PengaturanPembayaran::create([
            'jenis' => 'daftar_ulang',
            'nominal' => 500000,
            'tahun_akademik_id' => $tahunAktif->id,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEEDER: REKENING PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        $rek1 = RekeningPembayaran::create([
            'bank' => 'Mandiri',
            'nomor_rekening' => '1244678209236',
            'atas_nama' => 'PPFM',
        ]);

        RekeningPembayaran::create([
            'bank' => 'BRI',
            'nomor_rekening' => '145789276543862',
            'atas_nama' => 'PPFM',
        ]);

        RekeningPembayaran::create([
            'bank' => 'BNI',
            'nomor_rekening' => '1457629875',
            'atas_nama' => 'PPFM',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEEDER: KATEGORI SOAL
        |--------------------------------------------------------------------------
        */
        $kategori = [
            [
                'nama_kategori' => 'Wawancara',
                'tipe_kriteria' => 'threshold',
                'minimal_benar' => 3,
                'bobot' => null,
            ],
            [
                'nama_kategori' => 'Materi Tajwid',
                'tipe_kriteria' => 'benefit',
                'minimal_benar' => null,
                'bobot' => 40,
            ],
            [
                'nama_kategori' => 'Hafalan Bacaan Salat',
                'tipe_kriteria' => 'benefit',
                'minimal_benar' => null,
                'bobot' => 40,
            ],
            [
                'nama_kategori' => 'Tulis Ayat',
                'tipe_kriteria' => 'benefit',
                'minimal_benar' => null,
                'bobot' => 20,
            ],
        ];

        foreach ($kategori as $kat) {
            KategoriSoal::create($kat);
        }


        /*
        |--------------------------------------------------------------------------
        | SEEDER: SOAL (3 per kategori)
        |--------------------------------------------------------------------------
        */
        foreach (KategoriSoal::all() as $k) {
            for ($i = 1; $i <= 10; $i++) {
                Soal::create([
                    'kategori_id' => $k->id,
                    'pertanyaan' => "Contoh pertanyaan {$i} untuk {$k->nama_kategori}",
                    'pilihan' => [
                        'Pilihan 1',
                        'Pilihan 2',
                        'Pilihan 3',
                        'Pilihan 4',
                    ],
                    'jawaban' => 'Pilihan 1',
                ]);
            }
        }

        /*
|--------------------------------------------------------------------------
| SEEDER: TIMELINE SELEKSI
|--------------------------------------------------------------------------
*/
        TimelineSeleksi::insert([
            [
                'tahun_akademik_id' => $tahunAktif->id,
                'nama_gelombang' => 'Gelombang 1',
                'mulai' => '2025-01-01',
                'selesai' => '2025-03-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun_akademik_id' => $tahunAktif->id,
                'nama_gelombang' => 'Gelombang 2',
                'mulai' => '2025-04-01',
                'selesai' => '2025-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tahun_akademik_id' => $tahunAktif->id,
                'nama_gelombang' => 'Gelombang 3 (Terakhir)',
                'mulai' => '2025-07-01',
                'selesai' => '2025-08-15', // ⬅️ INI jadi deadline daftar ulang
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
