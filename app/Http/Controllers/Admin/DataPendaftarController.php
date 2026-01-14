<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;

class DataPendaftarController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;

        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        $santri = User::where('role', 'santri')
            ->whereHas('dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->when($status, function ($q) use ($status) {
                $q->whereHas('dataDiri', function ($d) use ($status) {
                    $d->where('status_seleksi', $status);
                });
            })
            ->with('dataDiri')
            ->orderByDesc('id')
            ->get();

        $kategori = KategoriSoal::all();

        return view('admin.pendaftar.index', compact('santri', 'status', 'tahunAktif', 'kategori'));
    }
}
