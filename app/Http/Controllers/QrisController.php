<?php

namespace App\Http\Controllers;

use App\Models\QrisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->has('aktif')) {
            QrisPembayaran::where('aktif', true)->update(['aktif' => false]);
        }

        $path = $request->file('image')->store('qris', 'public');

        QrisPembayaran::create([
            'nama'  => $request->nama,
            'image' => $path,
            'aktif' => $request->has('aktif'),
        ]);

        return back()->with('success', 'QRIS berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $qris = QrisPembayaran::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->has('aktif')) {
            QrisPembayaran::where('id', '!=', $qris->id)->update(['aktif' => false]);
            $qris->aktif = true;
        } else {
            $qris->aktif = false;
        }

        if ($request->hasFile('image')) {
            if ($qris->image && Storage::disk('public')->exists($qris->image)) {
                Storage::disk('public')->delete($qris->image);
            }

            $qris->image = $request->file('image')->store('qris', 'public');
        }

        $qris->nama = $request->nama;
        $qris->save();

        return back()->with('success', 'QRIS berhasil diperbarui');
    }

    public function destroy($id)
    {
        $qris = QrisPembayaran::findOrFail($id);

        if ($qris->image && Storage::disk('public')->exists($qris->image)) {
            Storage::disk('public')->delete($qris->image);
        }

        $qris->delete();

        return back()->with('success', 'QRIS berhasil dihapus');
    }
}
