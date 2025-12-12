<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanPembayaran;
use App\Models\RekeningPembayaran;
use App\Models\TimelineSeleksi;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class PengaturanPembayaranController extends Controller
{
    /* =====================================================
     | INDEX
     |=====================================================*/
    public function index()
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        /* ================== PENGATURAN PEMBAYARAN ================== */
        $pengaturan = PengaturanPembayaran::where('tahun_akademik_id', $tahunAktif->id)
            ->get()
            ->keyBy('jenis');

        // Pastikan selalu ada 2 jenis
        foreach (['registrasi', 'daftar_ulang'] as $jenis) {
            if (!isset($pengaturan[$jenis])) {
                $pengaturan[$jenis] = PengaturanPembayaran::create([
                    'jenis' => $jenis,
                    'nominal' => 0,
                    'tahun_akademik_id' => $tahunAktif->id,
                ]);
            }
        }

        /* ================== REKENING ================== */
        $rekening = RekeningPembayaran::orderBy('bank')->get();

        /* ================== TIMELINE SELEKSI ================== */
        $timeline = TimelineSeleksi::where('tahun_akademik_id', $tahunAktif->id)
            ->orderBy('mulai')
            ->get();

        return view('admin.pengaturan-pembayaran.index', compact(
            'tahunAktif',
            'pengaturan',
            'rekening',
            'timeline'
        ));
    }

    /* =====================================================
     | UPDATE NOMINAL PEMBAYARAN
     |=====================================================*/
    public function update(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:0',
        ]);

        PengaturanPembayaran::findOrFail($id)->update([
            'nominal' => $request->nominal,
        ]);

        return back()->with('success', 'Nominal pembayaran berhasil diperbarui.');
    }

    /* =====================================================
     | REKENING PEMBAYARAN
     |=====================================================*/
    public function storeRekening(Request $request)
    {
        $request->validate([
            'bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
        ]);

        RekeningPembayaran::create($request->only(
            'bank',
            'nomor_rekening',
            'atas_nama'
        ));

        return back()->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function updateRekening(Request $request, $id)
    {
        $request->validate([
            'bank' => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
        ]);

        RekeningPembayaran::findOrFail($id)->update(
            $request->only('bank', 'nomor_rekening', 'atas_nama')
        );

        return back()->with('success', 'Rekening berhasil diperbarui.');
    }

    public function deleteRekening($id)
    {
        RekeningPembayaran::findOrFail($id)->delete();

        return back()->with('success', 'Rekening berhasil dihapus.');
    }

    /* =====================================================
     | TIMELINE SELEKSI
     |=====================================================*/
    public function storeTimeline(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        TimelineSeleksi::create([
            'tahun_akademik_id' => $tahunAktif->id,
            'nama_gelombang' => $request->nama_gelombang,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
        ]);

        return back()->with('success', 'Timeline seleksi berhasil ditambahkan.');
    }

    public function updateTimeline(Request $request, $id)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        TimelineSeleksi::findOrFail($id)->update(
            $request->only('nama_gelombang', 'mulai', 'selesai')
        );

        return back()->with('success', 'Timeline seleksi berhasil diperbarui.');
    }

    public function deleteTimeline($id)
    {
        TimelineSeleksi::findOrFail($id)->delete();

        return back()->with('success', 'Timeline seleksi berhasil dihapus.');
    }
}
