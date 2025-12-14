<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\DataDiriSantri;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendaftarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = $user->dataDiri;
        return view('santri.pendaftar.index', compact('data', 'user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => [
                'required',
                'digits:10',
                'unique:data_diri_santri,nisn'
            ],
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $tahunAktif = TahunAkademik::where('aktif', 1)->firstOrFail();

        DataDiriSantri::create(array_merge(
            $request->all(),
            [
                'user_id' => Auth::id(),
                'tahun_akademik_id' => $tahunAktif->id,
            ]
        ));

        return back()->with('success', 'Data berhasil disimpan.');
    }


    public function edit()
    {
        $user = Auth::user();
        $data = $user->dataDiri;

        return view('santri.pendaftar.edit', compact('data', 'user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nisn' => [
                'required',
                'digits:10',
                'unique:data_diri_santri,nisn'
            ],
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_tujuan' => 'required'
        ], [
            'nisn.required' => 'NISN harus diisi.',
            'nisn.digits' => 'NISN harus terdiri dari 10 angka.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'pendidikan_tujuan.required' => 'Pendidikan tujuan harus diisi.',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'nik' => $request->nik,
        ]);

        $data = DataDiriSantri::firstOrNew(['user_id' => $user->id]);

        $fields = [
            'nama_lengkap',
            'kabupaten_lahir',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat_domisili',
            'nisn',
            'instansi_1',
            'instansi_2',
            'instansi_3',
            'prestasi_1',
            'prestasi_2',
            'prestasi_3',
            'hubungan_wali',
            'nama_wali',
            'no_telp_wali',
            'pendidikan_tujuan',
            'info_alumni',
            'info_saudara',
            'info_instagram',
            'info_tiktok',
            'info_youtube',
            'info_facebook',
            'info_lainnya'
        ];

        foreach ($fields as $field) {
            $data->$field = $request->$field ?? $data->$field ?? null;
        }

        foreach (
            [
                'info_alumni',
                'info_saudara',
                'info_instagram',
                'info_tiktok',
                'info_youtube',
                'info_facebook',
                'info_lainnya'
            ] as $chk
        ) {
            $data->$chk = $request->has($chk) ? 1 : 0;
        }

        if ($request->hasFile('foto_diri')) {
            $path = $request->file('foto_diri')->store('foto_diri', 'public');
            $data->foto_diri = $path;
        }

        if ($request->hasFile('foto_kk')) {
            $path = $request->file('foto_kk')->store('foto_kk', 'public');
            $data->foto_kk = $path;
        }

        $tahunAktif = TahunAkademik::where('aktif', 1)->firstOrFail();
        $data->tahun_akademik_id = $tahunAktif->id;

        $data->save();
        return redirect()->route('santri.jadwal.index')->with('success', 'Data berhasil disimpan.');
    }
}
