<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataDiriSantri;
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

    public function storeGmeet(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'kategori_id' => 'required',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        HasilTesSantri::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'kategori_id' => $request->kategori_id,
            ],
            [
                'nilai' => $request->nilai,
                'jawaban' => json_encode(['tipe' => 'GMEET']),
                'lulus_threshold' => null,
            ]
        );

        // OPTIONAL: hitung ulang nilai akhir
        $this->hitungNilaiAkhir($request->user_id);

        return back()->with('success', 'Nilai wawancara berhasil disimpan');
    }

    private function hitungNilaiAkhir($userId)
    {
        $hasilTes = HasilTesSantri::with('kategori')
            ->where('user_id', $userId)
            ->get();

        /**
         * 1. CEK THRESHOLD
         * Jika ADA threshold TIDAK LULUS → LANGSUNG GUGUR
         */
        foreach ($hasilTes as $hasil) {
            if (
                $hasil->kategori->tipe_kriteria === 'threshold'
                && $hasil->lulus_threshold === false
            ) {
                DataDiriSantri::where('user_id', $userId)->update([
                    'nilai_akhir'    => 0,
                    'status_seleksi' => 'tidak_lolos_seleksi',
                ]);

                return; // ⛔ STOP, tidak perlu hitung nilai
            }
        }

        /**
         * 2. HITUNG NILAI NON-THRESHOLD
         */
        $total = 0;

        foreach ($hasilTes as $hasil) {
            $kat = $hasil->kategori;

            if ($kat->tipe_kriteria !== 'threshold' && $kat->bobot > 0) {
                $total += ($hasil->nilai * $kat->bobot / 100);
            }
        }

        /**
         * 3. TENTUKAN STATUS AKHIR
         */
        $status = $total >= 75
            ? 'lolos_seleksi'
            : 'tidak_lolos_seleksi';

        DataDiriSantri::where('user_id', $userId)->update([
            'nilai_akhir'    => round($total, 2),
            'status_seleksi' => $status,
        ]);
    }


    public function storeThreshold(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'kategori_id' => 'required',
            'lulus_threshold' => 'required|boolean',
        ]);

        HasilTesSantri::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'kategori_id' => $request->kategori_id,
            ],
            [
                'nilai' => null,
                'lulus_threshold' => $request->lulus_threshold,
                'jawaban' => json_encode(['tipe' => 'THRESHOLD']),
            ]
        );

        $this->hitungNilaiAkhir($request->user_id);

        return back()->with('success', 'Hasil threshold berhasil disimpan');
    }
}
