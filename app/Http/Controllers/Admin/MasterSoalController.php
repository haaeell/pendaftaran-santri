<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\Soal;
use Illuminate\Http\Request;

class MasterSoalController extends Controller
{
    /* =====================================================
     | INDEX
     ===================================================== */
    public function index()
    {
        $kategori = KategoriSoal::with('soal')->get();

        return view('admin.master-soal.index', compact('kategori'));
    }

    /* =====================================================
     | KATEGORI
     ===================================================== */
    public function storeKategori(Request $request)
    {
        $data = $this->validateKategori($request);

        KategoriSoal::create($data);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }


    public function updateKategori(Request $request, $id)
    {
        $kategori = KategoriSoal::findOrFail($id);

        $data = $this->validateKategori($request, $kategori->id);

        $kategori->update($data);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }


    public function deleteKategori($id)
    {
        KategoriSoal::findOrFail($id)->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    /* =====================================================
     | SOAL
     ===================================================== */
    public function storeSoal(Request $request)
    {
        $data = $this->validateSoal($request);

        Soal::create($data);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateSoal(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);

        $data = $this->validateSoal($request, false);

        $soal->update($data);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function deleteSoal($id)
    {
        Soal::findOrFail($id)->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    /* =====================================================
     | VALIDATION HELPERS
     ===================================================== */

    private function validateKategori(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'nama_kategori'  => 'required|string|max:255',
            'tipe_kriteria'  => 'required|in:threshold,benefit',
            'bobot'          => 'required_if:tipe_kriteria,benefit|nullable|integer|min:1|max:100',
            'metode'         => 'required',
        ]);

        if ($validated['tipe_kriteria'] === 'threshold') {
            $validated['bobot'] = null;
            return $validated;
        }

        // ===============================
        // VALIDASI TOTAL BOBOT <= 100
        // ===============================
        $totalBobot = KategoriSoal::where('tipe_kriteria', 'benefit')
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->sum('bobot');

        $totalSetelahTambah = $totalBobot + ($validated['bobot'] ?? 0);

        if ($totalSetelahTambah > 100) {
            abort(
                back()->withErrors([
                    'bobot' => 'Total bobot seluruh kategori tidak boleh lebih dari 100.'
                ])->withInput()
            );
        }

        $validated['minimal_benar'] = null;

        return $validated;
    }


    private function validateSoal(Request $request, bool $requireKategori = true): array
    {
        return $request->validate([
            'kategori_id' => $requireKategori ? 'required|exists:kategori_soal,id' : 'nullable',
            'pertanyaan'  => 'required|string',
            'pilihan'     => 'required|array|min:2',
            'pilihan.*'   => 'required|string',
            'jawaban'     => 'required|integer',
        ]);
    }
}
