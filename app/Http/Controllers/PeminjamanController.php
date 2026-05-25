<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        // Pisahkan tampilan berdasarkan peran
        if (Auth::user()->peran == 'admin') {
            
            // 1. Ambil data peminjaman dengan fitur Pencarian & Filter Tanggal
            $query = Peminjaman::with(['user', 'ruangan'])->orderBy('created_at', 'desc');

            if ($request->filled('cari')) {
                $cari = $request->cari;
                $query->whereHas('user', function($q) use ($cari) {
                    $q->where('name', 'like', "%{$cari}%");
                })->orWhereHas('ruangan', function($q) use ($cari) {
                    $q->where('nama_ruangan', 'like', "%{$cari}%");
                })->orWhere('tujuan_kegiatan', 'like', "%{$cari}%");
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('waktu_mulai', $request->tanggal);
            }

            $data_peminjaman = $query->get();

            // 2. Siapkan Data Statistik untuk Chart.js
            $statistik_ruangan = Peminjaman::selectRaw('ruangan_id, count(*) as total')
                ->groupBy('ruangan_id')
                ->with('ruangan')
                ->get();

            $labels_ruangan = [];
            $data_total = [];
            foreach($statistik_ruangan as $stat) {
                $labels_ruangan[] = $stat->ruangan->nama_ruangan ?? 'Dihapus';
                $data_total[] = $stat->total;
            }

            return view('admin.dashboard', compact('data_peminjaman', 'labels_ruangan', 'data_total')); 
            
       } else {
            // Tampilan untuk Siswa
            $data_peminjaman = Peminjaman::with('ruangan')->where('user_id', Auth::id())->latest()->get();
            
            // AMBIL DATA RUANGAN BESERTA JADWAL BOOKING-NYA
            $data_ruangan = Ruangan::with(['peminjaman' => function($query) {
                // Hanya ambil jadwal yang belum selesai (ke depan) dan berstatus aktif
                $query->where('waktu_selesai', '>=', \Carbon\Carbon::now())
                      ->whereIn('status', ['menunggu', 'disetujui'])
                      ->orderBy('waktu_mulai', 'asc');
            }])->get(); 
            
            return view('peminjaman.index', compact('data_peminjaman', 'data_ruangan')); 
        }
    }

    public function tambah()
    {
        $data_ruangan = Ruangan::where('status', 'tersedia')->get();
        return view('peminjaman.tambah', compact('data_ruangan'));
    }

    public function simpan(Request $request)
    {
        // 1. Validasi input dasar
        $request->validate([
            'ruangan_id' => 'required',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai', // Jam selesai harus setelah jam mulai
            'tujuan_kegiatan' => 'required',
        ]);

        // 2. Cek Bentrok Jadwal (Logika Anti-Double Booking)
        $bentrok = Peminjaman::where('ruangan_id', $request->ruangan_id)
            ->whereIn('status', ['disetujui', 'menunggu']) 
            ->where('waktu_mulai', '<', $request->waktu_selesai)
            ->where('waktu_selesai', '>', $request->waktu_mulai)
            ->first();

        // 3. Jika ada jadwal yang bentrok, kembalikan dengan pesan error
        if ($bentrok) {
            return back()->with('error', 'Waduh! Ruangan ini sudah dibooking pada tanggal dan jam tersebut. Silakan pilih waktu lain.')->withInput();
        }

        // 4. Jika aman, simpan ke database
        Peminjaman::create([
            'user_id' => Auth::id(),
            'ruangan_id' => $request->ruangan_id,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'tujuan_kegiatan' => $request->tujuan_kegiatan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan berhasil dikirim!');
    }

    public function setujui($id)
    {
        if (Auth::user()->peran != 'admin') return back();
        
        Peminjaman::where('id', $id)->update(['status' => 'disetujui']);
        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function tolak($id)
    {
        if (Auth::user()->peran != 'admin') return back();

        Peminjaman::where('id', $id)->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak!');
    }

    // Fungsi Cetak PDF
    public function cetakPdf()
    {
        if (Auth::user()->peran != 'admin') return back();

        $data_peminjaman = Peminjaman::with(['user', 'ruangan'])->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.laporan_pdf', compact('data_peminjaman'));
        
        return $pdf->stream('Laporan_Peminjaman.pdf'); 
    }

    // Fungsi Khusus Admin untuk Menandai Selesai
    public function selesai($id)
    {
        if (Auth::user()->peran != 'admin') return back();

        Peminjaman::where('id', $id)->update(['status' => 'selesai']);
        return back()->with('success', 'Kegiatan selesai, ruangan telah dikembalikan!');
    }

    // Fitur Siswa: Membatalkan pengajuan yang masih "menunggu"
    public function batalSiswa($id)
    {
        // Cari data peminjaman berdasarkan ID dan pastikan itu milik siswa yang sedang login
        $peminjaman = Peminjaman::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        // Hanya bisa dibatalkan jika statusnya masih 'menunggu'
        if ($peminjaman->status == 'menunggu') {
            // Kita ubah statusnya menjadi 'dibatalkan' (atau bisa juga langsung dihapus dengan ->delete())
            $peminjaman->update(['status' => 'dibatalkan']);
            return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
        }

        return back()->with('error', 'Pengajuan tidak dapat dibatalkan karena sudah diproses admin.');
    }
}