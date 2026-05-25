<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLogin() {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ==========================================
            // LOGIKA PENGALIHAN BERDASARKAN PERAN
            // ==========================================
            if (Auth::user()->peran == 'admin') {
                // Jika Admin, ke Dashboard Kelola
                return redirect()->intended('/peminjaman')->with('success', 'Selamat datang kembali, Admin!');
            } else {
                // Jika Siswa/User, ke Halaman Beranda (Cek Ketersediaan)
                return redirect()->route('beranda')->with('success', 'Login berhasil! Selamat datang di Peminjaman Ruangan.');
            }
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Proses logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }

    // Menampilkan form registrasi
    public function showRegister() {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request) {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users', // Email tidak boleh sama dengan yang sudah ada
            'password' => 'required|min:6|confirmed'  // Wajib cocok dengan input password_confirmation
        ], [
            'email.unique' => 'Email ini sudah terdaftar!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!'
        ]);

        // Simpan user baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'peran' => 'user' // Default peran adalah user/siswa
        ]);

        // Otomatis langsung login setelah berhasil mendaftar
        Auth::login($user);

        // Arahkan ke halaman utama/beranda dengan pesan sukses
        return redirect()->route('beranda')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name);
    }
}