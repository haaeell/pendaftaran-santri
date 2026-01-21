<?php

namespace App\Http\Controllers;

use App\Models\JadwalTesSantri;
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

        $notifications = [];
        if ($user->role === 'santri') {
            $notifications = $user->notifications()
                ->latest()
                ->take(5)
                ->get();
        }

        $tahunAktif = TahunAkademik::where('aktif', true)->first();
        $dataDiri = null;
        $pembayaran = null;
        $jadwalTes = null;
        $statusJadwalTes = 'belum';

        if ($user->role === 'santri') {

            $dataDiri = $user->dataDiri()
                ->first();

            $pembayaran = $user->pembayaran()
                ->latest()
                ->first();

            $jadwalTes = $user->jadwalTes()
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

        $totalJadwalPenguji = 0;
        $belumDinilai = 0;
        $sudahDinilai = 0;
        $jadwalTerdekat = collect();

        if ($user->role === 'penguji') {

            $totalJadwalPenguji = JadwalTesSantri::count();
            $belumDinilai = JadwalTesSantri::whereHas('user.hasilTes', function ($q) {
                $q->where(function ($qq) {
                    $qq->whereNull('nilai')
                        ->orWhere('nilai', 0);
                })
                    ->whereHas('kategori', function ($k) {
                        $k->where('metode', '!=', 'pg');
                    });
            })->count();

            $sudahDinilai = JadwalTesSantri::whereHas('user.hasilTes')->count();
            $jadwalTerdekat = JadwalTesSantri::orderBy('waktu_mulai')->take(5)->get();
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
            'notifications' => $notifications,
            // penguji
            'totalJadwalPenguji' => $totalJadwalPenguji,
            'belumDinilai' => $belumDinilai,
            'sudahDinilai' => $sudahDinilai,
            'jadwalTerdekat' => $jadwalTerdekat,
        ]);
    }
}
