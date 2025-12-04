<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\PengaturanPembayaran;
use App\Models\RekeningPembayaran;
use App\Models\PembayaranSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarUlangController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // hanya bisa daftar ulang jika lolos seleksi
        if ($user->dataDiri->status_seleksi != 'lolos_seleksi') {
            return back()->with('error', 'Anda belum dinyatakan lolos seleksi.');
        }

        $biaya = PengaturanPembayaran::where('jenis', 'daftar_ulang')->first();
        $rekening = RekeningPembayaran::all();

        $pembayaran = PembayaranSantri::where('user_id', $user->id)
            ->where('jenis', 'daftar_ulang')
            ->latest()
            ->first();

        return view('santri.daftarulang.index', compact(
            'user',
            'biaya',
            'rekening',
            'pembayaran'
        ));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'rekening_id' => 'required',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_daftar_ulang', 'public');

        PembayaranSantri::create([
            'user_id' => Auth::id(),
            'jenis' => 'daftar_ulang',
            'nominal_bayar' => 0,
            'rekening_id' => $request->rekening_id,
            'bukti_transfer' => $path,
            'status' => 'menunggu'
        ]);

        return back()->with('success', 'Bukti daftar ulang berhasil dikirim. Menunggu verifikasi admin.');
    }
}
