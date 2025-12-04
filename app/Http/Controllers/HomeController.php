<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        return view('home', [
            'role' => $user->role,
            'dataDiri' => $user->dataDiri ?? null,
            'pembayaran' => $user->pembayaran()->latest()->first() ?? null,
            'jadwalTes' => $user->jadwalTes ?? null,
            'hasil' => $user->hasilTes ?? null,

            'totalPendaftar' => \App\Models\User::where('role', 'santri')->count(),
            'menunggu' => \App\Models\PembayaranSantri::where('status', 'menunggu')->count(),
            'terverifikasi' => \App\Models\PembayaranSantri::where('status', 'diterima')->count(),
            'totalSoal' => \App\Models\Soal::count(),
        ]);
    }
}
