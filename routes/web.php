<?php

use App\Http\Controllers\Admin\DataPendaftarController;
use App\Http\Controllers\Admin\HasilSeleksiController;
use App\Http\Controllers\Admin\JadwalTestController;
use App\Http\Controllers\Admin\MasterSoalController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\Admin\VerifikasiPembayaranController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Santri\DaftarUlangController;
use App\Http\Controllers\Santri\JadwalSeleksiController;
use App\Http\Controllers\Santri\PendaftarController;
use App\Http\Controllers\Santri\StatusSeleksiController;
use App\Http\Controllers\Santri\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
/*
|--------------------------------------------------------------------------
| SANTRI AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {
        Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
        Route::post('/pendaftar', [PendaftarController::class, 'store'])->name('pendaftar.store');
        Route::get('/pendaftar/edit', [PendaftarController::class, 'edit'])->name('pendaftar.edit');
        Route::post('/pendaftar/update', [PendaftarController::class, 'update'])->name('pendaftar.update');

        Route::get('/jadwal', [JadwalSeleksiController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal/upload-bukti', [JadwalSeleksiController::class, 'uploadBukti'])->name('jadwal.upload');

        Route::post('/test/start', [TestController::class, 'start'])->name('test.start');
        Route::get('/test', [TestController::class, 'index'])->name('test.index');
        Route::post('/test/submit', [TestController::class, 'submit'])->name('test.submit');

        Route::get('/daftar-ulang', [DaftarUlangController::class, 'index'])->name('daftarulang.index');
        Route::post('/daftar-ulang/upload', [DaftarUlangController::class, 'upload'])->name('daftarulang.upload');


        Route::get('/status', [StatusSeleksiController::class, 'index'])->name('status.index');
    });

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/verifikasi-pembayaran', [VerifikasiPembayaranController::class, 'index'])->name('payment.index');
        Route::post('/verifikasi-pembayaran/approve/{id}', [VerifikasiPembayaranController::class, 'approve'])->name('payment.approve');
        Route::post('/verifikasi-pembayaran/reject/{id}', [VerifikasiPembayaranController::class, 'reject'])->name('payment.reject');

        Route::get('/jadwal-test', [JadwalTestController::class, 'index'])->name('jadwal.index');
        Route::post('/jadwal-test/store', [JadwalTestController::class, 'store'])->name('jadwal.store');
        Route::post('/jadwal-test/update/{id}', [JadwalTestController::class, 'update'])->name('jadwal.update');
        Route::delete('/jadwal-test/delete/{id}', [JadwalTestController::class, 'destroy'])->name('jadwal.delete');

        Route::get('/hasil-seleksi', [HasilSeleksiController::class, 'index'])->name('hasil.index');
        Route::post('/hasil-seleksi/proses', [HasilSeleksiController::class, 'proses'])->name('hasil.proses');

        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
        Route::post('/pengumuman/umumkan', [PengumumanController::class, 'umumkan'])->name('pengumuman.umumkan');

        Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun.index');
        Route::post('/tahun-akademik/store', [TahunAkademikController::class, 'store'])->name('tahun.store');
        Route::post('/tahun-akademik/update/{id}', [TahunAkademikController::class, 'update'])->name('tahun.update');
        Route::delete('/tahun-akademik/delete/{id}', [TahunAkademikController::class, 'destroy'])->name('tahun.delete');
        Route::post('/tahun-akademik/aktifkan/{id}', [TahunAkademikController::class, 'aktifkan'])->name('tahun.aktifkan');

        Route::get('/pendaftar', [DataPendaftarController::class, 'index'])
            ->name('pendaftar.index');

        Route::prefix('master-soal')->name('master-soal.')->group(function () {
            Route::get('/', [MasterSoalController::class, 'index'])->name('index');

            // kategori
            Route::post('/kategori/store', [MasterSoalController::class, 'storeKategori'])->name('kategori.store');
            Route::put('/kategori/update/{id}', [MasterSoalController::class, 'updateKategori'])->name('kategori.update');
            Route::delete('/kategori/delete/{id}', [MasterSoalController::class, 'deleteKategori'])->name('kategori.delete');

            // soal
            Route::post('/soal/store', [MasterSoalController::class, 'storeSoal'])->name('soal.store');
            Route::put('/soal/update/{id}', [MasterSoalController::class, 'updateSoal'])->name('soal.update');
            Route::delete('/soal/delete/{id}', [MasterSoalController::class, 'deleteSoal'])->name('soal.delete');
        });
    });
