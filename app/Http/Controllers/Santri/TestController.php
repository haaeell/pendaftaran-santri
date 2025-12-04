<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\HasilTesSantri;
use App\Models\JadwalTesSantri;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

        session(['tes_mulai' => now()]); // simpan waktu mulai yang FIX

        return redirect()->route('santri.test.index');
    }

    public function index()
    {
        $kategori = KategoriSoal::with('soal')->get();
        return view('santri.test.index', compact('kategori'));
    }

    public function submit(Request $request)
    {
        $user = Auth::id();

        $mulai = session('tes_mulai') ? Carbon::parse(session('tes_mulai')) : now();
        $selesai = now();

        $jawabanUser = $request->jawaban ?? [];
        $kategoriList = KategoriSoal::with('soal')->get();

        foreach ($kategoriList as $kategori) {

            $nilai = 0;
            $detail = [];

            foreach ($kategori->soal as $soal) {

                $userAnswer = $jawabanUser[$soal->id] ?? null;

                $pilihan = $soal->pilihan;

                // TEMUKAN INDEX JAWABAN BENAR
                $benarIndex = array_search($soal->jawaban, $pilihan);

                $status = $userAnswer == $benarIndex ? 'betul' : 'salah';

                if ($status === 'betul') {
                    $nilai++;
                }

                $detail[] = [
                    'soal_id'           => $soal->id,
                    'jawaban_user'      => $userAnswer,
                    'jawaban_user_text' => $pilihan[$userAnswer] ?? null,
                    'jawaban_benar'     => $benarIndex,
                    'jawaban_benar_text' => $soal->jawaban,
                    'status'            => $status
                ];
            }

            $totalSoal = count($kategori->soal);
            $nilaiPersen = $totalSoal > 0 ? round(($nilai / $totalSoal) * 100) : 0;

            $hasil = [
                'kategori_id' => $kategori->id,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'durasi' => $selesai->diffInMinutes($mulai) . ' menit',
                'total_soal' => $totalSoal,
                'nilai' => $nilaiPersen,
                'jawaban' => $detail
            ];

            HasilTesSantri::updateOrCreate(
                ['user_id' => $user, 'kategori_id' => $kategori->id],
                [
                    'nilai' => $nilaiPersen,
                    'lulus_threshold' => $kategori->minimal_benar
                        ? $nilaiPersen >= $kategori->minimal_benar
                        : null,
                    'jawaban' => json_encode($hasil)
                ]
            );
        }

        return redirect()->route('santri.jadwal.index')
            ->with('success', 'Tes selesai! Hasil berhasil disimpan.');
    }
}
