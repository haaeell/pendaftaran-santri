<?php

namespace App\Http\Controllers;

use App\Models\PengumumanHasil;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jadwalTes = $user->jadwalTes;

        $statusJadwalTes = 'belum';

        if ($jadwalTes) {
            if (Carbon::parse($jadwalTes->tanggal_tes)->isPast()) {
                $statusJadwalTes = 'kadaluarsa';
            } else {
                $statusJadwalTes = 'aktif';
            }
        }

        $tahunAktif = TahunAkademik::where('aktif', true)->first();

        $pengumumanSudah = false;

        if ($tahunAktif) {
            $pengumumanSudah = PengumumanHasil::where('tahun_akademik_id', $tahunAktif->id)
                ->where('status', 'sudah')
                ->exists();
        }


        return view('home', [
            'role' => $user->role,
            'dataDiri' => $user->dataDiri,
            'pembayaran' => $user->pembayaran()->latest()->first(),
            'jadwalTes' => $jadwalTes,
            'statusJadwalTes' => $statusJadwalTes,
            'pengumumanSudah' => $pengumumanSudah,

            // ADMIN
            'totalPendaftar' => \App\Models\User::where('role', 'santri')->count(),
            'menunggu' => \App\Models\PembayaranSantri::where('status', 'menunggu')->count(),
            'terverifikasi' => \App\Models\PembayaranSantri::where('status', 'diterima')->count(),
            'totalSoal' => \App\Models\Soal::count(),
        ]);
    }
}
