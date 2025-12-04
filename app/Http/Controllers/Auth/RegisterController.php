<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        return Validator::make($data, [
            'username'   => ['required', 'string', 'max:255', 'unique:users'],
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telp'    => ['required', 'string', 'max:20'],
            'nik'        => ['required', 'string', 'max:20'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * CREATE USER BARU
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'name'     => $data['name'],
            'email'    => $data['email'],
            'no_telp'  => $data['no_telp'],
            'nik'      => $data['nik'],
            'role'     => 'santri',
            'password' => Hash::make($data['password']),
        ]);
    }
}
