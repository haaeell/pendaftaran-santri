<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSantri;
use App\Models\JadwalTesSantri;
use App\Models\TahunAkademik;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalTestController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->firstOrFail();

        $jadwalSemua = JadwalTesSantri::whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
            $q->where('tahun_akademik_id', $tahunAktif->id);
        })->with(['user.dataDiri', 'user.hasilTes'])
            ->get();

        foreach ($jadwalSemua as $j) {
            $user = $j->user;

            if ($user->hasilTes()->exists()) {
                continue;
            }

            if (Carbon::now()->greaterThan(Carbon::parse($j->waktu_selesai))) {
                $user->dataDiri->update([
                    'status_seleksi' => 'gugur'
                ]);
            }
        }

        $santri = PembayaranSantri::where('jenis', 'registrasi')
            ->where('status', 'diterima')
            ->whereHas('user.dataDiri', function ($q) use ($tahunAktif) {
                $q->where('tahun_akademik_id', $tahunAktif->id);
            })
            ->with(['user.dataDiri'])
            ->get();

        $jadwal = $jadwalSemua->keyBy('user_id');

        return view('admin.jadwal.index', compact('santri', 'jadwal', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
        ]);

        JadwalTesSantri::updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'sudah_mulai' => false,
            ]
        );

        return back()->with('success', 'Jadwal tes berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
        ]);

        $item = JadwalTesSantri::findOrFail($id);
        $item->update([
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil diperbarui.');
    }
}
