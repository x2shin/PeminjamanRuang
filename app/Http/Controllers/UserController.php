<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman daftar pengguna
    public function index()
    {
        if (Auth::user()->peran != 'admin') return back(); // Keamanan lapis ganda
        
        // UBAH: Gunakan nama variabel $users agar sesuai dengan view
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    // Mengubah peran (Admin <-> User)
    public function ubahPeran($id)
    {
        if (Auth::user()->peran != 'admin') return back();
        
        $user = User::findOrFail($id);
        
        // Jangan biarkan admin mengubah perannya sendiri (mencegah admin bunuh diri)
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa mengubah peran akunmu sendiri!');
        }

        // Jika admin, ubah jadi user. Jika user, ubah jadi admin.
        $user->peran = ($user->peran == 'admin') ? 'user' : 'admin';
        $user->save();

        return back()->with('success', 'Peran pengguna berhasil diubah!');
    }

    // Menghapus akun pengguna
    public function hapus($id)
    {
        if (Auth::user()->peran != 'admin') return back();

        $user = User::findOrFail($id);
        
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri!');
        }

        $user->delete();
        return back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    // Menampilkan halaman profil
    public function profil()
    {
        return view('profil');
    }

    // Memperbarui nama dan password
    public function updateProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:6|confirmed', // Harus ada input 'password_baru_confirmation'
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;

        // Jika user mengisi kolom password lama (berarti ingin ganti password)
        if ($request->filled('password_lama')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai!');
            }
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}