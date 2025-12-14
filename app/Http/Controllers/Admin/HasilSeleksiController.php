<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilTesSantri;
use App\Models\KategoriSoal;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        $santri = User::with([
            'dataDiri',
            'hasilTes.kategori'
        ])
            ->where('role', 'santri')
            ->whereHas('dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->whereHas('hasilTes')
            ->get();

        $kategori = KategoriSoal::all();

        return view('admin.hasil.index', compact('santri', 'kategori', 'tahunAktif'));
    }
    public function proses()
    {
        return back()->with('success', 'Proses seleksi telah dijalankan.');
    }
}
