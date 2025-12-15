<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $data = TahunAkademik::orderBy('tahun')->get();
        return view('admin.tahun.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate(['tahun' => 'required']);
        TahunAkademik::create($request->only('tahun'));

        return back()->with('success', 'Tahun akademik ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['tahun' => 'required']);

        TahunAkademik::findOrFail($id)
            ->update($request->only('tahun'));

        return back()->with('success', 'Tahun akademik diperbarui.');
    }

    public function destroy($id)
    {
        TahunAkademik::findOrFail($id)->delete();
        return back()->with('success', 'Tahun akademik dihapus.');
    }

    public function aktifkan($id)
    {
        TahunAkademik::query()->update(['aktif' => false]);
        TahunAkademik::findOrFail($id)->update(['aktif' => true]);

        return back()->with('success', 'Tahun akademik diaktifkan.');
    }
}
