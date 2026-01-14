<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\HasilTesSantri;
use App\Models\JadwalTesSantri;
use App\Models\PembayaranSantri;
use App\Models\PengaturanPembayaran;
use App\Models\QrisPembayaran;
use App\Models\RekeningPembayaran;
use App\Models\TimelineSeleksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalSeleksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $biaya = PengaturanPembayaran::where('jenis', 'registrasi')->first();

        $rekening = RekeningPembayaran::all();

        $pembayaran = PembayaranSantri::where('user_id', $user->id)
            ->where('jenis', 'registrasi')
            ->latest()
            ->first();
        $jadwalTes = JadwalTesSantri::where('user_id', $user->id)->first();

        $hasilTes = HasilTesSantri::where('user_id', $user->id)
            ->with('kategori')
            ->get();

        $qris = QrisPembayaran::orderBy('nama')->get();

        return view('santri.jadwal.index', compact(
            'user',
            'biaya',
            'rekening',
            'pembayaran',
            'jadwalTes',
            'hasilTes',
            'qris'
        ));
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048'
        ]);

        $user = Auth::user();

        $biaya = PengaturanPembayaran::where('jenis', 'registrasi')->first();

        if (!$biaya) {
            return back()->with('error', 'Pengaturan biaya registrasi belum diatur admin.');
        }

        $cek = PembayaranSantri::where('user_id', $user->id)
            ->where('jenis', 'registrasi')
            ->whereIn('status', ['menunggu', 'diterima'])
            ->first();

        if ($cek) {
            return back()->with('error', 'Anda sudah mengupload bukti pembayaran sebelumnya.');
        }

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        PembayaranSantri::create([
            'user_id' => $user->id,
            'jenis' => 'registrasi',
            'nominal_bayar' => $biaya->nominal,
            'rekening_id' => $request->rekening_id ?? RekeningPembayaran::first()->id,
            'bukti_transfer' => $path,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
