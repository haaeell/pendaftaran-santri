<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengumumanHasil;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAkademik::where('aktif', 1)->firstOrFail();

        $pengumuman = PengumumanHasil::where('tahun_akademik_id', $tahunAktif->id)->first();

        $santri = User::where('role', 'santri')
            ->whereHas('dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->with([
                'dataDiri' => function ($q) use ($tahunAktif) {
                    $q->where('tahun_akademik_id', $tahunAktif->id);
                },
            ])
            ->get();

        return view('admin.pengumuman.index', compact(
            'pengumuman',
            'santri',
            'tahunAktif'
        ));
    }


    public function umumkan()
    {
        $tahunAktif = TahunAkademik::where('aktif', 1)->firstOrFail();

        $pengumuman = PengumumanHasil::firstOrCreate(
            ['tahun_akademik_id' => $tahunAktif->id],
            [
                'status' => 'sudah',
                'tanggal_pengumuman' => now(),
            ]
        );

        $santri = User::where('role', 'santri')
            ->whereHas('dataDiri')
            ->with(['hasilTes', 'dataDiri'])
            ->get();

        foreach ($santri as $s) {
            $hasil = $s->hasilTes;

            if ($hasil->isEmpty()) {
                continue;
            }

            $lulusSemua = $hasil->every(fn($h) => $h->nilai >= 75);

            $s->dataDiri->update([
                'status_seleksi' => $lulusSemua
                    ? 'lolos_seleksi'
                    : 'tidak_lolos_seleksi',
            ]);
        }

        $pengumuman->update([
            'status' => 'sudah',
        ]);

        return back()->with('success', 'Pengumuman berhasil diumumkan dan status santri diperbarui.');
    }

}
