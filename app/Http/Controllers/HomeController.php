<?php

namespace App\Http\Controllers;

use App\Models\PengumumanHasil;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Models\PembayaranSantri;
use App\Models\Soal;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $tahunAktif = TahunAkademik::where('aktif', true)->first();
        $dataDiri = null;
        $pembayaran = null;
        $jadwalTes = null;
        $statusJadwalTes = 'belum';

        if ($tahunAktif && $user->role === 'santri') {

            $dataDiri = $user->dataDiri()
                ->where('tahun_akademik_id', $tahunAktif->id)
                ->first();

            $pembayaran = $user->pembayaran()
                ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id);
                })
                ->latest()
                ->first();

            $jadwalTes = $user->jadwalTes()
                ->where('tahun_akademik_id', $tahunAktif->id)
                ->first();

            if ($jadwalTes) {
                if ($jadwalTes->sudah_mulai) {
                    $statusJadwalTes = 'selesai';
                } elseif (Carbon::parse($jadwalTes->waktu_selesai)->isPast()) {
                    $statusJadwalTes = 'kadaluarsa';
                } else {
                    $statusJadwalTes = 'aktif';
                }
            }
        }

        $pengumumanSudah = $tahunAktif
            ? PengumumanHasil::where('tahun_akademik_id', $tahunAktif->id)
            ->where('status', 'sudah')
            ->exists()
            : false;

        $totalPendaftar = 0;
        $menunggu = 0;
        $terverifikasi = 0;
        $totalSoal = 0;

        if ($tahunAktif && $user->role === 'admin') {

            $totalPendaftar = User::where('role', 'santri')
                ->whereHas('dataDiri', function ($q) use ($tahunAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id);
                })
                ->count();

            $menunggu = PembayaranSantri::where('status', 'menunggu')
                ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id);
                })
                ->count();

            $terverifikasi = PembayaranSantri::where('status', 'diterima')
                ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id);
                })
                ->count();

            $totalSoal = Soal::count();
        }

        return view('home', [
            'role' => $user->role,
            'tahunAktif' => $tahunAktif,
            'dataDiri' => $dataDiri,
            'pembayaran' => $pembayaran,
            'jadwalTes' => $jadwalTes,
            'statusJadwalTes' => $statusJadwalTes,
            'pengumumanSudah' => $pengumumanSudah,
            'totalPendaftar' => $totalPendaftar,
            'menunggu' => $menunggu,
            'terverifikasi' => $terverifikasi,
            'totalSoal' => $totalSoal,
        ]);
    }
}
