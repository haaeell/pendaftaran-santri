<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengumumanHasil;
use App\Models\User;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = PengumumanHasil::latest()->first();
        $santri = User::where('role', 'santri')->whereHas('dataDiri')->with(['dataDiri', 'hasilTes'])->get();

        return view('admin.pengumuman.index', compact('pengumuman', 'santri'));
    }

    public function umumkan()
    {
        $pengumuman = PengumumanHasil::latest()->first();

        if (!$pengumuman) {
            return back()->with('error', 'Data pengumuman belum tersedia.');
        }

        $santri = User::where('role', 'santri')->whereHas('dataDiri')->with('hasilTes', 'dataDiri')->get();

        foreach ($santri as $s) {
            $hasil = $s->hasilTes;

            if ($hasil->count() == 0) continue;
            $lulusSemua = true;

            foreach ($hasil as $h) {
                if ($h->nilai < 75) {
                    $lulusSemua = false;
                    break;
                }
            }

            $s->dataDiri->status_seleksi = $lulusSemua ? 'lolos_seleksi' : 'tidak_lolos_seleksi';
            $s->dataDiri->save();
        }

        $pengumuman->update(['status' => 'sudah']);

        return back()->with('success', 'Pengumuman berhasil diumumkan dan status peserta diperbarui.');
    }
}
