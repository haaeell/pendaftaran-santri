<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSantri;
use App\Models\JadwalTesSantri;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalTestController extends Controller
{
    public function index()
    {
        $santri = PembayaranSantri::where('jenis', 'registrasi')
            ->where('status', 'diterima')
            ->with('user')
            ->get();

        $jadwal = JadwalTesSantri::all()->keyBy('user_id');

        return view('admin.jadwal.index', compact('santri', 'jadwal'));
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
