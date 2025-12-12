<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimelineSeleksi;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TimelineSeleksiController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        $timeline = TimelineSeleksi::where('tahun_akademik_id', $tahunAktif->id)
            ->orderBy('mulai')
            ->get();

        return view('admin.timeline-seleksi.index', compact('timeline', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $tahunAktif = TahunAkademik::where('is_active', true)->firstOrFail();

        TimelineSeleksi::create([
            'tahun_akademik_id' => $tahunAktif->id,
            'nama_gelombang' => $request->nama_gelombang,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
        ]);

        return back()->with('success', 'Timeline seleksi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $timeline = TimelineSeleksi::findOrFail($id);

        $request->validate([
            'nama_gelombang' => 'required|string|max:100',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $timeline->update($request->only('nama_gelombang', 'mulai', 'selesai'));

        return back()->with('success', 'Timeline seleksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TimelineSeleksi::findOrFail($id)->delete();

        return back()->with('success', 'Timeline seleksi berhasil dihapus.');
    }
}
