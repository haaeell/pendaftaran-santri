<?php

use App\Http\Controllers\Santri\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

/* SANTRI */
use App\Http\Controllers\Santri\PendaftarController;
use App\Http\Controllers\Santri\JadwalSeleksiController;
use App\Http\Controllers\Santri\TestController;
use App\Http\Controllers\Santri\DaftarUlangController;
use App\Http\Controllers\Santri\StatusSeleksiController;

/* ADMIN */
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\Admin\JadwalTestController;
use App\Http\Controllers\Admin\HasilSeleksiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\Admin\DataPendaftarController;
use App\Http\Controllers\Admin\MasterSoalController;
use App\Http\Controllers\Admin\PengaturanPembayaranController;
use App\Http\Controllers\QrisController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');



/*
|--------------------------------------------------------------------------
| AREA SANTRI
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {

        /* PENDAFTAR */
        Route::controller(PendaftarController::class)->group(function () {
            Route::get('/pendaftar', 'index')->name('pendaftar.index');
            Route::post('/pendaftar', 'store')->name('pendaftar.store');
            Route::get('/pendaftar/edit', 'edit')->name('pendaftar.edit');
            Route::post('/pendaftar/update', 'update')->name('pendaftar.update');
        });

        /* JADWAL SELEKSI */
        Route::controller(JadwalSeleksiController::class)->group(function () {
            Route::get('/jadwal', 'index')->name('jadwal.index');
            Route::post('/jadwal/upload-bukti', 'uploadBukti')->name('jadwal.upload');
        });

        /* TEST */
        Route::controller(TestController::class)->group(function () {
            Route::post('/test/start', 'start')->name('test.start');
            Route::get('/test', 'index')->name('test.index');
            Route::post('/test/submit', 'submit')->name('test.submit');
        });

        /* DAFTAR ULANG */
        Route::controller(DaftarUlangController::class)->group(function () {
            Route::get('/daftar-ulang', 'index')->name('daftarulang.index');
            Route::post('/daftar-ulang/upload', 'upload')->name('daftarulang.upload');
        });

        /* STATUS SELEKSI */
        Route::get('/status', [StatusSeleksiController::class, 'index'])->name('status.index');

        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile.index');

        Route::post('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
    });



/*
|--------------------------------------------------------------------------
| AREA ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |-------------------------------
        | VERIFIKASI PEMBAYARAN
        |-------------------------------
        */
        Route::prefix('verifikasi-pembayaran')
            ->name('payment.')
            ->controller(VerifikasiPembayaranController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('{id}/approve', 'approve')->name('approve');
                Route::post('{id}/reject', 'reject')->name('reject');
                Route::post('{id}/cancel', 'cancel')->name('cancel');
            });


        /*
        |-------------------------------
        | JADWAL TEST
        |-------------------------------
        */
        Route::prefix('jadwal-test')
            ->name('jadwal.')
            ->controller(JadwalTestController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
            });


        /*
        |-------------------------------
        | HASIL SELEKSI
        |-------------------------------
        */
        Route::prefix('hasil-seleksi')
            ->name('hasil.')
            ->controller(HasilSeleksiController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/proses', 'proses')->name('proses');
                Route::post('/gmeet', 'storeGmeet')->name('gmeet.store');
                Route::post('/threshold', 'storeThreshold')->name('threshold.store');
            });


        /*
        |-------------------------------
        | PENGUMUMAN
        |-------------------------------
        */
        Route::prefix('pengumuman')
            ->name('pengumuman.')
            ->controller(PengumumanController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/umumkan', 'umumkan')->name('umumkan');
            });


        /*
        |-------------------------------
        | TAHUN AKADEMIK
        |-------------------------------
        */
        Route::prefix('tahun-akademik')
            ->name('tahun.')
            ->controller(TahunAkademikController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/store', 'store')->name('store');
                Route::put('/update/{id}', 'update')->name('update');
                Route::delete('/delete/{id}', 'destroy')->name('delete');
                Route::post('/aktifkan/{id}', 'aktifkan')->name('aktifkan');
            });


        /*
        |-------------------------------
        | DATA PENDAFTAR
        |-------------------------------
        */
        Route::get('/pendaftar', [DataPendaftarController::class, 'index'])
            ->name('pendaftar.index');

        /*
        |-------------------------------
        | QRIS
        |-------------------------------
        */
        Route::post('/qris', [QrisController::class, 'store'])->name('qris.store');
        Route::put('/qris/{id}', [QrisController::class, 'update'])->name('qris.update');
        Route::delete('/qris/{id}', [QrisController::class, 'destroy'])->name('qris.delete');



        /*
        |-------------------------------
        | MASTER SOAL
        |-------------------------------
        */
        Route::prefix('master-soal')
            ->name('master-soal.')
            ->controller(MasterSoalController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                // kategori
                Route::post('/kategori/store', 'storeKategori')->name('kategori.store');
                Route::put('/kategori/update/{id}', 'updateKategori')->name('kategori.update');
                Route::delete('/kategori/delete/{id}', 'deleteKategori')->name('kategori.delete');

                // soal
                Route::post('/soal/store', 'storeSoal')->name('soal.store');
                Route::put('/soal/update/{id}', 'updateSoal')->name('soal.update');
                Route::delete('/soal/delete/{id}', 'deleteSoal')->name('soal.delete');
            });

        Route::prefix('pengaturan-pembayaran')
            ->name('pengaturan-pembayaran.')
            ->controller(PengaturanPembayaranController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                // pembayaran
                Route::put('/{id}', 'update')->name('update');

                // rekening
                Route::post('/rekening', 'storeRekening')->name('rekening.store');
                Route::put('/rekening/{id}', 'updateRekening')->name('rekening.update');
                Route::delete('/rekening/{id}', 'deleteRekening')->name('rekening.delete');

                // timeline seleksi
                Route::post('/timeline', 'storeTimeline')->name('timeline.store');
                Route::put('/timeline/{id}', 'updateTimeline')->name('timeline.update');
                Route::delete('/timeline/{id}', 'deleteTimeline')->name('timeline.delete');
            });
    });
