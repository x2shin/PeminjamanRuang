@extends('layouts.main')
@section('title', 'Dashboard Peminjaman')

@section('content')
<style>
    .hover-lift { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .bg-gradient-user { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
    .room-img-top { height: 180px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; }
</style>

@php
    // Logika Sapaan Berdasarkan Waktu
    $jam = date('H');
    if ($jam >= 5 && $jam < 12) { $sapaan = 'Selamat Pagi'; $icon = 'sun text-warning'; }
    elseif ($jam >= 12 && $jam < 15) { $sapaan = 'Selamat Siang'; $icon = 'sun text-warning'; }
    elseif ($jam >= 15 && $jam < 18) { $sapaan = 'Selamat Sore'; $icon = 'cloud-sun text-warning'; }
    else { $sapaan = 'Selamat Malam'; $icon = 'moon text-warning'; }

    // Hitung ringkasan milik siswa
    $menunggu = $data_peminjaman->where('status', 'menunggu')->count();
    $disetujui = $data_peminjaman->where('status', 'disetujui')->count();
@endphp

<div class="card border-0 shadow-sm rounded-4 bg-gradient-user text-white mb-5 mt-2 hover-lift">
    <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="mb-4 mb-md-0">
            <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-2 mb-3 fw-medium" style="font-size: 12px; letter-spacing: 0.5px;">
                <i class="fas fa-user-graduate me-1"></i> AKSES SISWA
            </span>
            <h3 class="fw-bold mb-2">{{ $sapaan }}, {{ explode(' ', Auth::user()->name)[0] }}! <i class="fas fa-{{ $icon }} ms-1"></i></h3>
            <p class="text-white-50 mb-0" style="font-size: 15px;">Mau mengadakan kegiatan apa hari ini? Yuk, pesan ruangannya sekarang!</p>
        </div>
        <div class="d-flex gap-3 text-center">
            <div class="bg-white bg-opacity-10 rounded-4 p-3" style="min-width: 110px; backdrop-filter: blur(5px);">
                <h3 class="fw-bold mb-0">{{ $menunggu }}</h3>
                <span style="font-size: 12px;" class="text-white-50">Menunggu</span>
            </div>
            <div class="bg-white bg-opacity-10 rounded-4 p-3" style="min-width: 110px; backdrop-filter: blur(5px);">
                <h3 class="fw-bold mb-0">{{ $disetujui }}</h3>
                <span style="font-size: 12px;" class="text-white-50">Disetujui</span>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" style="background-color: #ecfdf5; color: #065f46;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-door-open text-primary me-2"></i>Katalog Fasilitas Ruangan</h5>
    <a href="{{ route('peminjaman.tambah') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
        <i class="fas fa-plus me-1"></i> Pinjam Baru
    </a>
</div>

<div class="row g-4 mb-5">
    @forelse($data_ruangan as $ruang)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
                
                <div class="position-relative">
                    @if($ruang->foto)
                        <img src="{{ asset('storage/ruangan/' . $ruang->foto) }}" class="card-img-top room-img-top" alt="{{ $ruang->nama_ruangan }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center room-img-top text-muted">
                            <i class="fas fa-image fs-1 opacity-25"></i>
                        </div>
                    @endif

                    @php
                        $sedangDipakai = false;
                        $overtime = false;
                        $sekarang = \Carbon\Carbon::now();
                        if($ruang->peminjaman) {
                            foreach($ruang->peminjaman as $jadwal) {
                                $mulai = \Carbon\Carbon::parse($jadwal->waktu_mulai);
                                $selesai = \Carbon\Carbon::parse($jadwal->waktu_selesai);
                                if($jadwal->status == 'disetujui') {
                                    if($sekarang->between($mulai, $selesai)) {
                                        $sedangDipakai = true; break;
                                    } elseif($sekarang->greaterThan($selesai)) {
                                        $overtime = true;
                                    }
                                }
                            }
                        }
                    @endphp

                    <div class="position-absolute top-0 end-0 p-3">
                        @if(strtolower($ruang->status) != 'tersedia')
                            <span class="badge bg-danger shadow-sm rounded-pill px-3 py-2"><i class="fas fa-tools me-1"></i> Ditutup</span>
                        @elseif($sedangDipakai)
                            <span class="badge bg-danger shadow-sm rounded-pill px-3 py-2"><i class="fas fa-video me-1"></i> Sedang Dipakai</span>
                        @elseif($overtime)
                            <span class="badge bg-warning text-dark shadow-sm rounded-pill px-3 py-2"><i class="fas fa-exclamation-triangle me-1"></i> Overtime</span>
                        @else
                            <span class="badge bg-success shadow-sm rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Tersedia</span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-1">{{ $ruang->nama_ruangan }}</h5>
                    <p class="text-primary fw-medium mb-3" style="font-size: 13px;">
                        <i class="fas fa-users me-1"></i> Kapasitas: {{ $ruang->kapasitas }} Orang
                    </p>
                    <p class="text-muted" style="font-size: 13px; min-height: 40px;">
                        {{ $ruang->deskripsi ?? 'Belum ada deskripsi untuk ruangan ini.' }}
                    </p>
                    
                    <div class="bg-light rounded-3 p-3">
                        <span class="d-block fw-semibold text-dark mb-2" style="font-size: 11px; text-transform: uppercase;"><i class="fas fa-calendar-alt me-1 text-primary"></i> Jadwal Terisi (Booking):</span>
                        @if($ruang->peminjaman && $ruang->peminjaman->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                            @foreach($ruang->peminjaman->take(2) as $jadwal)
                                <span class="badge bg-white text-dark border shadow-sm fw-normal" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('d M, H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                </span>
                            @endforeach
                            @if($ruang->peminjaman->count() > 2)
                                <span class="badge bg-secondary text-white shadow-sm fw-normal" style="font-size: 11px;">
                                    +{{ $ruang->peminjaman->count() - 2 }} lainnya
                                </span>
                            @endif
                            </div>
                        @else
                            <span class="text-success fw-medium" style="font-size: 12px;">
                                <i class="fas fa-check-circle me-1"></i> Kosong / Belum ada booking
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-white border-top-0 px-4 pb-4 pt-0">
                    @if(strtolower($ruang->status) == 'tersedia')
                        <a href="{{ route('peminjaman.tambah') }}" class="btn btn-outline-primary w-100 rounded-pill fw-semibold shadow-sm">
                            Pinjam Ruangan Ini
                        </a>
                    @else
                        <button class="btn btn-secondary w-100 rounded-pill fw-semibold shadow-sm" disabled>
                            Tidak Dapat Dipinjam
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted">
                <i class="fas fa-door-closed fs-1 mb-3 opacity-25"></i>
                <h6 class="fw-bold text-dark">Belum Ada Fasilitas</h6>
                <p>Katalog ruangan saat ini kosong.</p>
            </div>
        </div>
    @endforelse
</div>

<h5 class="fw-bold text-dark mb-3"><i class="fas fa-history text-primary me-2"></i>Riwayat Pengajuan Saya</h5>
<div class="card shadow-sm border-0 mb-5 hover-lift" style="border-radius: 16px;">
    <div class="card-body table-responsive p-0">
        <table class="table table-borderless align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr class="text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                    <th class="py-3 ps-4 border-0">Fasilitas & Tujuan</th>
                    <th class="py-3 border-0">Waktu Pelaksanaan</th>
                    <th class="py-3 text-center border-0">Status</th>
                    <th class="py-3 text-center pe-4 border-0">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data_peminjaman as $pinjam)
                <tr class="border-bottom" style="font-size: 14px;">
                    <td class="py-3 ps-4">
                        <div class="fw-bold text-dark" style="font-size: 15px;">{{ $pinjam->ruangan->nama_ruangan ?? 'Ruangan Dihapus' }}</div>
                        <div class="text-muted mt-1" style="font-size: 13px;">
                            <i class="fas fa-bullseye me-1 text-primary opacity-75"></i> {{ $pinjam->tujuan_kegiatan }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="text-dark fw-medium">
                            <i class="far fa-calendar-alt me-2 text-primary opacity-75"></i>{{ \Carbon\Carbon::parse($pinjam->waktu_mulai)->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-muted mt-1" style="font-size: 13px;">
                            <i class="far fa-clock me-2 text-secondary opacity-75"></i>{{ \Carbon\Carbon::parse($pinjam->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pinjam->waktu_selesai)->format('H:i') }} WIB
                        </div>
                    </td>
                    <td class="py-3 text-center">
                        @if($pinjam->status == 'disetujui')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                        @elseif($pinjam->status == 'ditolak')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                        @elseif($pinjam->status == 'selesai')
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-flag-checkered me-1"></i> Selesai</span>
                        @elseif($pinjam->status == 'dibatalkan')
                            <span class="badge bg-dark-subtle text-dark border border-dark-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-ban me-1"></i> Dibatalkan</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-hourglass-half me-1"></i> Menunggu</span>
                        @endif
                    </td>
                    <td class="py-3 text-center pe-4">
                        @if($pinjam->status == 'menunggu')
                            <form action="{{ route('peminjaman.batal', $pinjam->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm px-3" onclick="return confirm('Apakah kamu yakin ingin membatalkan pengajuan ini?')">
                                    <i class="fas fa-times me-1"></i> Batalkan
                                </button>
                            </form>
                        @else
                            <span class="text-muted" style="font-size: 12px;"><i class="fas fa-lock me-1 opacity-50"></i> Dikunci</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="text-muted d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 border shadow-sm" style="width: 70px; height: 70px;">
                                <i class="fas fa-folder-open fs-3 text-secondary opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Pengajuan</h6>
                            <p class="mb-3" style="font-size: 14px;">Kamu belum pernah mengajukan peminjaman ruangan.</p>
                            <a href="{{ route('peminjaman.tambah') }}" class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm fw-semibold">Mulai Pinjam Sekarang</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection