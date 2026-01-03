<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDiriSantri;
use App\Models\PembayaranSantri;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        $registrasi = PembayaranSantri::with(['user.dataDiri', 'rekening'])
            ->where('jenis', 'registrasi')
            ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->latest()
            ->get();

        $daftarUlang = PembayaranSantri::with(['user.dataDiri', 'rekening'])
            ->where('jenis', 'daftar_ulang')
            ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact(
            'registrasi',
            'daftarUlang',
            'tahunAktif'
        ));
    }
    public function approve($id)
    {
        $pembayaran = PembayaranSantri::findOrFail($id);

        $pembayaran->update([
            'status' => 'diterima'
        ]);

        if ($pembayaran->jenis === 'daftar_ulang') {
            DataDiriSantri::where('user_id', $pembayaran->user_id)
                ->update([
                    'status_seleksi' => 'diterima'
                ]);
        }

        return back()->with('success', 'Pembayaran telah disetujui dan santri dinyatakan DITERIMA.');
    }


    public function reject($id, Request $request)
    {
        PembayaranSantri::findOrFail($id)->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }

    public function cancel($id)
    {
        PembayaranSantri::findOrFail($id)->update([
            'status' => 'menunggu',
            'catatan_admin' => null,
        ]);

        return back()->with('success', 'Verifikasi pembayaran berhasil dibatalkan.');
    }
}
