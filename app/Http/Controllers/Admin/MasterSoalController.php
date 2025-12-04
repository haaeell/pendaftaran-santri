<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\Soal;
use Illuminate\Http\Request;

class MasterSoalController extends Controller
{
    public function index()
    {
        $kategori = KategoriSoal::with('soal')->get();
        return view('admin.master-soal.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'tipe_kriteria' => 'required|in:threshold,benefit',
            'minimal_benar' => 'nullable|integer',
            'bobot' => 'nullable|integer',
        ]);

        KategoriSoal::create($request->all());

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = KategoriSoal::findOrFail($id);
        $kategori->update($request->all());
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteKategori($id)
    {
        KategoriSoal::findOrFail($id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }


    // ================== CRUD SOAL ===================

    public function storeSoal(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'pertanyaan' => 'required',
            'jawaban' => 'required',
            'pilihan' => 'required|array',
        ]);

        Soal::create([
            'kategori_id' => $request->kategori_id,
            'pertanyaan' => $request->pertanyaan,
            'pilihan' => json_encode($request->pilihan),
            'jawaban' => $request->jawaban,
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateSoal(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);

        $soal->update([
            'pertanyaan' => $request->pertanyaan,
            'pilihan' => json_encode($request->pilihan),
            'jawaban' => $request->jawaban,
        ]);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function deleteSoal($id)
    {
        Soal::findOrFail($id)->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}
