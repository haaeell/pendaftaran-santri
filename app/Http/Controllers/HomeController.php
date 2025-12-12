<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('home', [
            'role' => $user->role,
            'dataDiri' => $user->dataDiri,
            'pembayaran' => $user->pembayaran()->latest()->first(),
            'jadwalTes' => $jadwalTes,
            'statusJadwalTes' => $statusJadwalTes,

            // ADMIN
            'totalPendaftar' => \App\Models\User::where('role', 'santri')->count(),
            'menunggu' => \App\Models\PembayaranSantri::where('status', 'menunggu')->count(),
            'terverifikasi' => \App\Models\PembayaranSantri::where('status', 'diterima')->count(),
            'totalSoal' => \App\Models\Soal::count(),
        ]);
    }
}
