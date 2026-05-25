<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\UserController;
use App\Models\Peminjaman;
use Carbon\Carbon;

Route::get('/', function () {
    // Ambil data peminjaman KHUSUS HARI INI dan yang statusnya DISETUJUI
    $jadwal_hari_ini = Peminjaman::with(['user', 'ruangan'])
        ->where('status', 'disetujui')
        ->whereDate('waktu_mulai', Carbon::today())
        ->orderBy('waktu_mulai', 'asc')
        ->get();

    return view('welcome', compact('jadwal_hari_ini'));
})->name('beranda');

// Routing Auth (Bebas diakses)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Routing Peminjaman & Logout (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Cetak Laporan PDF (Khusus Admin)
    Route::get('/laporan/pdf', [PeminjamanController::class, 'cetakPdf'])->name('peminjaman.cetakPdf');

    // Routing Peminjaman Dasar
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/tambah', [PeminjamanController::class, 'tambah'])->name('peminjaman.tambah');
    Route::post('/peminjaman/simpan', [PeminjamanController::class, 'simpan'])->name('peminjaman.simpan');
    
    // Rute Batal tetap POST karena di view menggunakan <form>
    Route::post('/peminjaman/{id}/batal', [PeminjamanController::class, 'batalSiswa'])->name('peminjaman.batal');
    
    // ==============================================================
    // PERBAIKAN: Routing Aksi Khusus Admin (Diubah jadi GET)
    // ==============================================================
    Route::get('/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::get('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::get('/peminjaman/{id}/selesai', [PeminjamanController::class, 'selesai'])->name('peminjaman.selesai');

    // Routing Kelola Ruangan (Khusus Admin)
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan/simpan', [RuanganController::class, 'simpan'])->name('ruangan.simpan');
    Route::post('/ruangan/{id}/hapus', [RuanganController::class, 'hapus'])->name('ruangan.hapus');
    Route::post('/ruangan/{id}/update', [RuanganController::class, 'update'])->name('ruangan.update');

    // Manajemen Pengguna (Khusus Admin)
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{id}/peran', [UserController::class, 'ubahPeran'])->name('admin.users.peran');
    Route::post('/admin/users/{id}/hapus', [UserController::class, 'hapus'])->name('admin.users.hapus');

    // Route Profil Pengguna
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::post('/profil/update', [UserController::class, 'updateProfil'])->name('profil.update');
});