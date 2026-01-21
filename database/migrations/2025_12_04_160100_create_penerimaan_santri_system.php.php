<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | MASTER TAHUN AKADEMIK
        |--------------------------------------------------------------------------
        */
        Schema::create('tahun_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // contoh: "2025/2026"
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 1. USERS (Register Santri & Admin)
        |--------------------------------------------------------------------------
        */
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('no_telp')->nullable();
            $table->string('nik')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'santri', 'penguji'])->default('santri');
            $table->rememberToken();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 2. DATA DIRI SANTRI
        |--------------------------------------------------------------------------
        */
        Schema::create('data_diri_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('nama_lengkap');
            $table->string('kabupaten_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat_domisili')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('nik')->nullable();
            $table->string('nisn')->nullable();

            $table->string('foto_diri')->nullable();
            $table->string('foto_kk')->nullable();

            // Pendidikan Agama
            $table->string('instansi_1')->nullable();
            $table->string('instansi_2')->nullable();
            $table->string('instansi_3')->nullable();

            // Prestasi
            $table->string('prestasi_1')->nullable();
            $table->string('prestasi_2')->nullable();
            $table->string('prestasi_3')->nullable();

            // Penyakit khusus
            $table->text('penyakit_khusus')->nullable();

            // Data Wali
            $table->string('hubungan_wali')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('rata_rata_penghasilan')->nullable();
            $table->string('no_telp_wali')->nullable();

            // Informasi PSB
            $table->boolean('info_alumni')->default(false);
            $table->boolean('info_saudara')->default(false);
            $table->boolean('info_instagram')->default(false);
            $table->boolean('info_tiktok')->default(false);
            $table->boolean('info_youtube')->default(false);
            $table->boolean('info_facebook')->default(false);
            $table->boolean('info_lainnya')->default(false);

            // Pendidikan Tujuan
            $table->enum('pendidikan_tujuan', [
                'SMP dalam Pesantren',
                'SMA dalam Pesantren',
                'Universitas luar Pesantren',
                'Tidak Sekolah'
            ]);

            $table->enum('status_seleksi', [
                'belum_diterima',
                'lolos_seleksi',
                'tidak_lolos_seleksi',
                'diterima',
                'gugur'
            ])->default('belum_diterima');

            $table->float('nilai_akhir')->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. PENGATURAN PEMBAYARAN
        |--------------------------------------------------------------------------
        */
        Schema::create('pengaturan_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['registrasi', 'daftar_ulang']);
            $table->decimal('nominal', 12, 2)->default(0);
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 4. REKENING
        |--------------------------------------------------------------------------
        */
        Schema::create('rekening_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 5. PEMBAYARAN SANTRI
        |--------------------------------------------------------------------------
        */
        Schema::create('pembayaran_santri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis', ['registrasi', 'daftar_ulang']);
            $table->decimal('nominal_bayar', 12, 2);

            $table->foreignId('rekening_id')->constrained('rekening_pembayaran')->cascadeOnDelete();
            $table->string('bukti_transfer')->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 6. TIMELINE SELEKSI
        |--------------------------------------------------------------------------
        */
        Schema::create('timeline_seleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->string('nama_gelombang');
            $table->date('mulai');
            $table->date('selesai');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 7. KATEGORI SOAL
        |--------------------------------------------------------------------------
        */
        Schema::create('kategori_soal', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->enum('tipe_kriteria', ['threshold', 'benefit']);
            $table->integer('bobot')->nullable(); // hanya benefit
            $table->integer('minimal_benar')->nullable(); // untuk threshold

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 8. SOAL
        |--------------------------------------------------------------------------
        */
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_soal')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->json('pilihan');
            $table->string('jawaban');
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 9. JADWAL TES SANTRI
        |--------------------------------------------------------------------------
        */
        Schema::create('jadwal_tes_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');
            $table->boolean('sudah_mulai')->default(false);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 10. HASIL TES SANTRI
        |--------------------------------------------------------------------------
        */
        Schema::create('hasil_tes_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_soal')->cascadeOnDelete();

            $table->integer('nilai')->nullable();
            $table->boolean('lulus_threshold')->nullable();
            $table->json('jawaban')->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 11. PENGUMUMAN HASIL
        |--------------------------------------------------------------------------
        */
        Schema::create('pengumuman_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->cascadeOnDelete();
            $table->date('tanggal_pengumuman');
            $table->enum('status', ['belum', 'sudah'])->default('belum');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::table('data_diri_santri', function (Blueprint $table) {
            $table->foreignId('tahun_akademik_id')
                ->nullable()
                ->constrained('tahun_akademik')
                ->cascadeOnDelete();
        });

        Schema::create('qris_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: QRIS PMB 2026
            $table->string('image'); // path gambar qris
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman_hasil');
        Schema::dropIfExists('hasil_tes_santri');
        Schema::dropIfExists('jadwal_tes_santri');
        Schema::dropIfExists('soal');
        Schema::dropIfExists('kategori_soal');
        Schema::dropIfExists('timeline_seleksi');
        Schema::dropIfExists('pembayaran_santri');
        Schema::dropIfExists('rekening_pembayaran');
        Schema::dropIfExists('pengaturan_pembayaran');
        Schema::dropIfExists('data_diri_santri');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tahun_akademik');
    }
};
