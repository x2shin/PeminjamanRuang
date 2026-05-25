@extends('layouts.main')
@section('title', 'Dashboard Administrator')

@section('content')

<style>
    .hover-lift {
        transition: transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
    .bg-gradient-dark { background: linear-gradient(135deg, #374151 0%, #6b7280 100%); }
</style>

@php
    // Logika Sapaan Berdasarkan Waktu
    $jam = date('H');
    if ($jam >= 5 && $jam < 12) {
        $sapaan = 'Selamat Pagi';
        $icon = 'sun text-warning';
    } elseif ($jam >= 12 && $jam < 15) {
        $sapaan = 'Selamat Siang';
        $icon = 'sun text-warning';
    } elseif ($jam >= 15 && $jam < 18) {
        $sapaan = 'Selamat Sore';
        $icon = 'cloud-sun text-warning';
    } else {
        $sapaan = 'Selamat Malam';
        $icon = 'moon text-primary';
    }

    // Statistik Data
    $total = $data_peminjaman->count();
    $menunggu = $data_peminjaman->where('status', 'menunggu')->count();
    $disetujui = $data_peminjaman->where('status', 'disetujui')->count();
    $selesai = $data_peminjaman->where('status', 'selesai')->count();
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 mt-2">
    <div>
        <h4 class="fw-bold mb-1">{{ $sapaan }}, {{ explode(' ', Auth::user()->name)[0] }}! <i class="fas fa-{{ $icon }} ms-1"></i></h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Berikut adalah ringkasan aktivitas peminjaman ruangan hari ini.</p>
    </div>
    <a href="{{ route('peminjaman.cetakPdf') }}" class="btn btn-primary shadow-sm rounded-pill px-4 fw-semibold mt-3 mt-md-0 hover-lift" target="_blank">
        <i class="fas fa-file-pdf me-2"></i>Cetak Laporan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" style="background-color: #ecfdf5; color: #065f46;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white bg-gradient-primary hover-lift">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-white-50 fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">TOTAL PENGAJUAN</p>
                    <h3 class="fw-bold mb-0 display-6">{{ $total }}</h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-folder-open fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white bg-gradient-warning hover-lift">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-white-50 fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">MENUNGGU PROSES</p>
                    <h3 class="fw-bold mb-0 display-6">{{ $menunggu }}</h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-hourglass-half fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white bg-gradient-success hover-lift">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-white-50 fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">DISETUJUI</p>
                    <h3 class="fw-bold mb-0 display-6">{{ $disetujui }}</h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-check-double fs-5"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-white bg-gradient-dark hover-lift">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-white-50 fw-semibold" style="font-size: 11px; letter-spacing: 0.8px;">SESI SELESAI</p>
                    <h3 class="fw-bold mb-0 display-6">{{ $selesai }}</h3>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fas fa-flag-checkered fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4 hover-lift" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-chart-line text-primary me-2"></i>Frekuensi Penggunaan Ruangan</h6>
    </div>
    <div class="card-body p-4">
        <div style="height: 250px;">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

<form action="{{ route('peminjaman.index') }}" method="GET" class="mb-4">
    <div class="card shadow-sm border-0 hover-lift" style="border-radius: 16px;">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0 px-3 rounded-start-pill"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="cari" class="form-control bg-light border-0 py-2 rounded-end-pill" placeholder="Cari nama, ruangan, atau kegiatan..." value="{{ request('cari') }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0 px-3 rounded-start-pill"><i class="far fa-calendar-alt text-muted"></i></span>
                        <input type="date" name="tanggal" class="form-control bg-light border-0 py-2 rounded-end-pill text-muted" value="{{ request('tanggal') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                        <i class="fas fa-filter me-1"></i> Filter Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0 mb-5" style="border-radius: 16px;">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-list text-primary me-2"></i>Daftar Pengajuan</h6>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-borderless align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr class="text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                    <th class="py-3 ps-4 border-0">Detail Peminjaman</th>
                    <th class="py-3 border-0">Pemohon</th>
                    <th class="py-3 border-0">Jadwal</th>
                    <th class="py-3 text-center border-0">Status</th>
                    <th class="py-3 text-center pe-4 border-0">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data_peminjaman as $pinjam)
                <tr class="border-bottom" style="font-size: 14px;">
                    <td class="py-3 ps-4">
                        <div class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $pinjam->ruangan->nama_ruangan ?? 'Ruangan Dihapus' }}</div>
                        <div class="text-muted" style="font-size: 13px;">
                            <i class="fas fa-bullseye me-1 text-primary opacity-75"></i> {{ $pinjam->tujuan_kegiatan }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px;">
                                <i class="fas fa-user text-primary" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $pinjam->user->name ?? 'User Dihapus' }}</span>
                                <span class="text-muted" style="font-size: 12px;">Siswa</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark mb-1">
                            <i class="far fa-calendar-alt me-2 text-primary opacity-75"></i>{{ \Carbon\Carbon::parse($pinjam->waktu_mulai)->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-muted" style="font-size: 13px;">
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
                            <span class="badge bg-dark-subtle text-dark border border-dark-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-ban me-1"></i> Batal oleh User</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-hourglass-half me-1"></i> Menunggu</span>
                        @endif
                    </td>
                    <td class="py-3 text-center pe-4">
                        @if($pinjam->status == 'menunggu')
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('peminjaman.setujui', $pinjam->id) }}" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm hover-lift" onclick="return confirm('Setujui pengajuan ini?')">
                                    <i class="fas fa-check me-1"></i> Setujui
                                </a>
                                <a href="{{ route('peminjaman.tolak', $pinjam->id) }}" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm hover-lift" onclick="return confirm('Tolak pengajuan ini?')">
                                    <i class="fas fa-times me-1"></i> Tolak
                                </a>
                            </div>
                        @elseif($pinjam->status == 'disetujui')
                            <a href="{{ route('peminjaman.selesai', $pinjam->id) }}" class="btn btn-sm btn-info text-white rounded-pill px-4 shadow-sm hover-lift" onclick="return confirm('Tandai kegiatan ini telah selesai dan ruangan sudah kosong?')">
                                <i class="fas fa-flag-checkered me-1"></i> Akhiri Sesi
                            </a>
                        @else
                            <span class="text-muted" style="font-size: 13px;"><i class="fas fa-lock me-1 opacity-50"></i> Dikunci</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted d-flex flex-column align-items-center justify-content-center py-5">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 border shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fas fa-search fs-3 text-secondary opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Data Tidak Ditemukan</h6>
                            <p class="mb-0" style="font-size: 14px;">Belum ada data pengajuan yang sesuai dengan kriteria filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('barChart').getContext('2d');
        
        const labels = {!! json_encode($labels_ruangan) !!};
        const dataTotal = {!! json_encode($data_total) !!};

        // Buat gradien untuk chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.8)');   
        gradient.addColorStop(1, 'rgba(14, 165, 233, 0.4)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Peminjaman',
                    data: dataTotal,
                    backgroundColor: gradient, 
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { family: "'Poppins', sans-serif", size: 13 },
                        bodyFont: { family: "'Poppins', sans-serif", size: 14 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { color: '#f3f4f6', borderDash: [5, 5], drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#6b7280', font: { family: "'Poppins', sans-serif" } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush