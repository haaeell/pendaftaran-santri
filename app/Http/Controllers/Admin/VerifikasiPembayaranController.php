<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSantri;
use Illuminate\Http\Request;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $registrasi = PembayaranSantri::with(['user', 'rekening'])
            ->where('jenis', 'registrasi')
            ->latest()
            ->get();

        $daftarUlang = PembayaranSantri::with(['user', 'rekening'])
            ->where('jenis', 'daftar_ulang')
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('registrasi', 'daftarUlang'));
    }


    public function approve($id)
    {
        PembayaranSantri::findOrFail($id)->update(['status' => 'diterima']);
        return back()->with('success', 'Pembayaran telah disetujui.');
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
