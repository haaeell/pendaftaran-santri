<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Soal;

class FixSoalFormat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soal:fix-format';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki format pilihan & jawaban pada tabel soal agar konsisten';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $soals = Soal::all();

        foreach ($soals as $s) {

            // Decode pilihan lama
            $pilihan = json_decode($s->pilihan, true);

            if (!is_array($pilihan)) {
                $this->error("Pilihan soal ID {$s->id} tidak valid, dilewati...");
                continue;
            }

            // Normalisasi pilihan menjadi index 0,1,2,3
            $pilihan = array_values($pilihan);

            // Perbaikan jawaban jika sebelumnya adalah string "Pilihan 1"
            if (!is_numeric($s->jawaban)) {

                preg_match('/\d+/', $s->jawaban, $match);
                $index = isset($match[0]) ? (intval($match[0]) - 1) : 0;

                $s->jawaban = $index;
            }

            // Simpan ulang
            $s->pilihan = json_encode($pilihan);
            $s->save();
        }

        $this->info("Semua format soal berhasil diperbaiki 🎉");
    }
}
