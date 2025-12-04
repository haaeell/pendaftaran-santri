<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataPendaftarController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $santri = User::where('role', 'santri')
            ->whereHas('dataDiri')
            ->when($status, function ($q) use ($status) {
                $q->whereHas('dataDiri', function ($d) use ($status) {
                    $d->where('status_seleksi', $status);
                });
            })
            ->with('dataDiri')
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.pendaftar.index', compact('santri', 'status'));
    }
}
