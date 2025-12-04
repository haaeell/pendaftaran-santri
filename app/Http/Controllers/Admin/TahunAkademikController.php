<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $data = TahunAkademik::all();
        return view('admin.tahun.index', compact('data'));
    }

    public function store(Request $request)
    {
        TahunAkademik::create($request->all());
        return back()->with('success', 'Tahun akademik ditambahkan.');
    }

    public function aktifkan($id)
    {
        TahunAkademik::query()->update(['aktif' => false]);
        TahunAkademik::findOrFail($id)->update(['aktif' => true]);

        return back()->with('success', 'Tahun akademik diaktifkan.');
    }
}
