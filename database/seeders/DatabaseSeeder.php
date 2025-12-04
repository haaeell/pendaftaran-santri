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

        $santri = User::create([
            'username' => 'santri1',
            'name' => 'Santri Contoh',
            'email' => 'santri1@psb.test',
            'no_telp' => '08123456789',
            'nik' => '9876543210',
            'role' => 'santri',
            'password' => Hash::make('password'),
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEEDER: DATA DIRI SANTRI
        |--------------------------------------------------------------------------
        */
        DataDiriSantri::create([
            'user_id' => $santri->id,
            'nama_lengkap' => 'Santri Contoh',
            'kabupaten_lahir' => 'Cirebon',
            'tanggal_lahir' => '2010-01-01',
            'jenis_kelamin' => 'L',
            'alamat_domisili' => 'Jl. Pesantren No. 123',
            'email' => 'santri1@psb.test',
            'no_telp' => '08123456789',
            'nik' => '9876543210',
            'nisn' => '20250101001',

            'foto_diri' => null,
            'foto_kk' => null,

            'instansi_1' => 'MI Walijaya',
            'instansi_2' => null,
            'instansi_3' => null,

            'prestasi_1' => 'Juara 1 MTQ',
            'prestasi_2' => null,
            'prestasi_3' => null,

            'penyakit_khusus' => null,

            'hubungan_wali' => 'Ayah',
            'nama_wali' => 'Bapak Santri',
            'rata_rata_penghasilan' => '4.000.000',
            'no_telp_wali' => '082233445566',

            'info_instagram' => true,
            'info_alumni' => false,
            'info_saudara' => false,
            'info_tiktok' => false,
            'info_youtube' => false,
            'info_facebook' => false,
            'info_lainnya' => false,

            'pendidikan_tujuan' => 'SMP dalam Pesantren',
            'status_seleksi' => 'belum_diterima',
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
        | SEEDER: PEMBAYARAN SANTRI
        |--------------------------------------------------------------------------
        */
        PembayaranSantri::create([
            'user_id' => $santri->id,
            'jenis' => 'registrasi',
            'nominal_bayar' => 100000,
            'rekening_id' => $rek1->id,
            'bukti_transfer' => null,
            'status' => 'menunggu',
            'catatan_admin' => null,
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
            for ($i = 1; $i <= 3; $i++) {
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
        | SEEDER: JADWAL TES SANTRI
        |--------------------------------------------------------------------------
        */
        JadwalTesSantri::create([
            'user_id' => $santri->id,
            'waktu_mulai' => now()->addDays(7),
            'waktu_selesai' => now()->addDays(7)->addHours(2),
            'sudah_mulai' => false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEEDER: HASIL TES SANTRI (Dummy)
        |--------------------------------------------------------------------------
        */
        foreach (KategoriSoal::all() as $k) {

            HasilTesSantri::create([
                'user_id' => $santri->id,
                'kategori_id' => $k->id,
                'nilai' => rand(60, 90),
                'lulus_threshold' => $k->tipe_kriteria === 'threshold' ? true : null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SEEDER: PENGUMUMAN AWAL
        |--------------------------------------------------------------------------
        */
        PengumumanHasil::create([
            'tahun_akademik_id' => $tahunAktif->id,
            'tanggal_pengumuman' => '2025-09-15',
            'status' => 'belum',
        ]);
    }
}
