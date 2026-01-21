<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengujiController extends Controller
{
    public function index()
    {
        $penguji = User::where('role', 'penguji')->latest()->get();
        return view('admin.penguji.index', compact('penguji'));
    }

    public function create()
    {
        return view('admin.penguji.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penguji'
        ]);

        return redirect()->route('admin.penguji.index')
            ->with('success', 'Penguji berhasil ditambahkan');
    }

    public function edit(User $penguji)
    {
        return view('admin.penguji.edit', compact('penguji'));
    }

    public function update(Request $request, User $penguji)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $penguji->id
        ]);

        $penguji->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->password) {
            $penguji->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.penguji.index')
            ->with('success', 'Data penguji berhasil diupdate');
    }

    public function destroy(User $penguji)
    {
        $penguji->delete();
        return back()->with('success', 'Penguji berhasil dihapus');
    }
}
