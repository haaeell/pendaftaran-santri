<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PengumumanHasil;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * VALIDASI REGISTER
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'username' => ['required', 'string', 'max:255', 'unique:users,username'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'no_telp' => ['required', 'string', 'max:20'],
                'nik' => ['required', 'digits:16', 'unique:users,nik'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ],
            [
                // USERNAME
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'username.max' => 'Username maksimal 255 karakter.',

                // NAMA
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',

                // EMAIL
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',

                // NO TELP
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',

                // NIK
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
                'nik.unique' => 'NIK sudah terdaftar.',

                // PASSWORD
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );
    }

    public function showRegistrationForm()
    {
        $tahunAktif = TahunAkademik::where('aktif', true)->first();

        $pendaftaranDitutup = false;

        if ($tahunAktif) {
            $pendaftaranDitutup = PengumumanHasil::where(
                'tahun_akademik_id',
                $tahunAktif->id
            )->exists();
        }

        return view('auth.register', compact('pendaftaranDitutup'));
    }


    /**
     * CREATE USER BARU
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'no_telp' => $data['no_telp'],
            'nik' => $data['nik'],
            'role' => 'santri',
            'password' => Hash::make($data['password']),
        ]);
    }
}
