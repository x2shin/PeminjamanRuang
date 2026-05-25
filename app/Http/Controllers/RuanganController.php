<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RuanganController extends Controller
{
    // Menampilkan halaman kelola ruangan khusus Admin
    public function index() {
        if (Auth::user()->peran != 'admin') return redirect('/peminjaman');
        $data_ruangan = Ruangan::all();
        return view('ruangan.index', compact('data_ruangan'));
    }

    // Menyimpan ruangan baru
    public function simpan(Request $request)
    {
        // 1. Tambahkan validasi kapasitas
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas'    => 'required|integer', 
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $nama_file = null;
        // Jika admin mengupload foto
        if ($request->hasFile('foto')) {
            // Simpan foto ke folder storage/app/public/ruangan
            $path = $request->file('foto')->store('ruangan', 'public');
            $nama_file = basename($path);
        }

        // 2. Masukkan kapasitas ke dalam create
        Ruangan::create([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas'    => $request->kapasitas, 
            'deskripsi'    => $request->deskripsi,
            'foto'         => $nama_file,
            'status'       => $request->status ?? 'tersedia', // Default 'tersedia' jika form tidak mengirim status
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    // Memperbarui data ruangan
    public function update(Request $request, $id)
    {
        // 1. Tambahkan validasi kapasitas juga di update
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas'    => 'required|integer',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'       => 'required'
        ]);

        $ruangan = Ruangan::findOrFail($id);
        
        // Tetapkan nama file foto yang lama terlebih dahulu
        $nama_file = $ruangan->foto;

        // Jika admin mengupload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage jika ada
            if ($ruangan->foto) {
                Storage::disk('public')->delete('ruangan/' . $ruangan->foto);
            }
            // Simpan foto baru
            $path = $request->file('foto')->store('ruangan', 'public');
            $nama_file = basename($path);
        }

        // 2. Masukkan kapasitas ke dalam update
        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'kapasitas'    => $request->kapasitas,
            'deskripsi'    => $request->deskripsi,
            'foto'         => $nama_file,
            'status'       => $request->status,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    // Menghapus ruangan
    public function hapus($id) {
        Ruangan::findOrFail($id)->delete();
        return back()->with('success', 'Ruangan berhasil dihapus!');
    }
}