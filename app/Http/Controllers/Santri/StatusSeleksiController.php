<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSantri;
use App\Models\JadwalTesSantri;
use App\Models\PengaturanPembayaran;
use App\Models\PengumumanHasil;
use App\Models\RekeningPembayaran;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatusSeleksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tahunAktif = TahunAkademik::where('aktif', true)->first();

        $pembayaran = PembayaranSantri::where('user_id', $user->id)->first();
        $jadwal = JadwalTesSantri::where('user_id', $user->id)->first();

        $pengumuman = PengumumanHasil::latest()->first();

        $tahun = $tahunAktif ?? null;

        $biayaDaftarUlang = PengaturanPembayaran::where('jenis', 'daftar_ulang')
            ->where('tahun_akademik_id', optional($tahunAktif)->id)
            ->first();

        $rekening = RekeningPembayaran::all();

        $pembayaranDU = PembayaranSantri::where('user_id', $user->id)
            ->where('jenis', 'daftar_ulang')
            ->latest()
            ->first();

        return view('santri.status.index', compact(
            'user',
            'pembayaran',
            'jadwal',
            'pengumuman',
            'biayaDaftarUlang',
            'rekening',
            'pembayaranDU'
        ));
    }
}
