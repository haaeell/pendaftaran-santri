<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilTesSantri;
use App\Models\KategoriSoal;
use App\Models\User;
use Illuminate\Http\Request;

class HasilSeleksiController extends Controller
{
    public function index()
    {
        $santri = User::with(['dataDiri', 'hasilTes.kategori'])
            ->where('role', 'santri')
            ->get();

        $kategori = KategoriSoal::all();

        return view('admin.hasil.index', compact('santri', 'kategori'));
    }

    public function proses()
    {
        return back()->with('success', 'Proses seleksi telah dijalankan.');
    }
}
