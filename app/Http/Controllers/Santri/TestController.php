<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\HasilTesSantri;
use App\Models\JadwalTesSantri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function start(Request $request)
    {
        $user = Auth::user();

        $jadwal = JadwalTesSantri::where('user_id', $user->id)->first();
        if (!$jadwal) {
            return back()->with('error', 'Jadwal tes belum tersedia.');
        }

        if (now()->lt($jadwal->waktu_mulai)) {
            return back()->with('error', 'Tes belum dimulai.');
        }

        $jadwal->update(['sudah_mulai' => true]);
        session(['tes_mulai' => now()]);

        return redirect()->route('santri.test.index');
    }

    public function index()
    {
        $kategori = KategoriSoal::with(['soal' => function ($q) {
            $q->inRandomOrder()->limit(5);
        }])->get();

        $soalTampil = [];

        foreach ($kategori as $kat) {
            $soalTampil[$kat->id] = $kat->soal->pluck('id')->toArray();
        }

        $jadwal = JadwalTesSantri::where('user_id', Auth::user()->id)->first();

        session(['soal_tes' => $soalTampil]);

        return view('santri.test.index', compact('kategori', 'jadwal'));
    }


    public function submit(Request $request)
    {
        $user = Auth::user();

        $mulai   = session('tes_mulai') ? Carbon::parse(session('tes_mulai')) : now();
        $selesai = now();

        $jawabanUser  = $request->jawaban ?? [];
        $soalSession = session('soal_tes', []);

        $kategoriList = KategoriSoal::whereIn('id', array_keys($soalSession))
            ->with(['soal' => function ($q) use ($soalSession) {
                $q->whereIn('id', collect($soalSession)->flatten());
            }])->get();

        $nilaiAkhir = 0;
        $gagalThreshold = false;

        foreach ($kategoriList as $kategori) {

            $jumlahBenar   = 0;
            $detailJawaban = [];

            foreach ($kategori->soal as $soal) {

                $jawabanUserIndex = $jawabanUser[$soal->id] ?? null;
                $indexBenar       = (int) $soal->jawaban;

                $status = ($jawabanUserIndex !== null && (int)$jawabanUserIndex === $indexBenar)
                    ? 'betul'
                    : 'salah';

                if ($status === 'betul') {
                    $jumlahBenar++;
                }

                $detailJawaban[] = [
                    'soal_id'            => $soal->id,
                    'jawaban_user'       => $jawabanUserIndex,
                    'jawaban_user_text'  => $soal->pilihan[$jawabanUserIndex] ?? null,
                    'jawaban_benar'      => $indexBenar,
                    'jawaban_benar_text' => $soal->pilihan[$indexBenar] ?? null,
                    'status'             => $status,
                ];
            }

            $totalSoal   = count($kategori->soal);
            $nilaiPersen = $totalSoal > 0
                ? round(($jumlahBenar / $totalSoal) * 100)
                : 0;

            if ($kategori->tipe_kriteria === 'threshold') {
                if ($jumlahBenar < $kategori->minimal_benar) {
                    $gagalThreshold = true;
                }
            }

            if (
                !$gagalThreshold &&
                $kategori->tipe_kriteria === 'benefit'
            ) {
                $nilaiAkhir += ($nilaiPersen * $kategori->bobot / 100);
            }

            HasilTesSantri::updateOrCreate(
                [
                    'user_id'     => $user->id,
                    'kategori_id' => $kategori->id,
                ],
                [
                    'nilai'           => $nilaiPersen,
                    'jumlah_benar'    => $jumlahBenar,
                    'lulus_threshold' => $kategori->tipe_kriteria === 'threshold'
                        ? $jumlahBenar >= $kategori->minimal_benar
                        : null,
                    'jawaban' => json_encode([
                        'kategori_id'  => $kategori->id,
                        'mulai'        => $mulai,
                        'selesai'      => $selesai,
                        'durasi'       => $selesai->diffInMinutes($mulai) . ' menit',
                        'total_soal'   => $totalSoal,
                        'jumlah_benar' => $jumlahBenar,
                        'nilai'        => $nilaiPersen,
                        'jawaban'      => $detailJawaban,
                    ]),
                ]
            );
        }

        $gagalBenefit = false;

        if (!$gagalThreshold && $nilaiAkhir < 75) {
            $gagalBenefit = true;
        }

        if ($gagalThreshold || $gagalBenefit) {
            $user->dataDiri->update([
                'status_seleksi' => 'tidak_lolos_seleksi',
                'nilai_akhir'    => $nilaiAkhir,
            ]);
        } else {
            $user->dataDiri->update([
                'status_seleksi' => 'lolos_seleksi',
                'nilai_akhir'    => $nilaiAkhir,
            ]);
        }

        return redirect()
            ->route('santri.jadwal.index')
            ->with('success', 'Tes selesai. Hasil seleksi berhasil diproses.');
    }
}
