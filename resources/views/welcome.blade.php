<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Ruangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        /* Gradien keren untuk Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
            padding: 100px 0 150px;
            position: relative;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: linear-gradient(to top, #f8f9fa, transparent);
        }
        /* Efek kartu mengambang */
        .floating-card {
            margin-top: -80px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 10;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="z-index: 20; top: 0;">
        <div class="container py-3">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-building me-2 fs-4"></i> Peminjaman Ruangan
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center mt-3 mt-lg-0 gap-2">
                    @auth
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-light rounded-pill px-4 fw-semibold text-primary shadow-sm">
                            Ke Dashboard <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-light rounded-pill px-4 fw-semibold text-primary shadow-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="hero-section text-center text-white text-md-start">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill fw-semibold" style="letter-spacing: 1px; font-size: 12px;">
                        <i class="fas fa-clock me-1"></i> UPDATE REAL-TIME
                    </span>
                    <h1 class="display-5 fw-bold mb-3" style="line-height: 1.2;">Cek Ketersediaan Ruangan</h1>
                    <p class="lead mb-0 opacity-75" style="font-size: 16px;">Pantau jadwal pemakaian fasilitas sekolah secara transparan hari ini. Jangan sampai jadwalmu bentrok!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="card border-0 bg-white floating-card overflow-hidden">
            
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 px-md-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h5 class="fw-bold text-dark mb-1">
                        <i class="far fa-calendar-alt text-primary me-2"></i> Jadwal Hari Ini
                    </h5>
                    <p class="text-muted mb-0" style="font-size: 14px;">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                        <i class="fas fa-sync-alt me-1"></i> Live Update
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                            <tr>
                                <th class="py-3 px-4 px-md-5 border-0">Ruangan</th>
                                <th class="py-3 px-4 border-0">Peminjam</th>
                                <th class="py-3 px-4 border-0">Kegiatan</th>
                                <th class="py-3 px-4 border-0">Waktu</th>
                                <th class="py-3 px-4 px-md-5 border-0 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal_hari_ini as $jadwal)
                                <tr class="border-bottom" style="font-size: 15px;">
                                    <td class="py-3 px-4 px-md-5">
                                        <div class="fw-bold text-dark">{{ $jadwal->ruangan->nama_ruangan ?? 'Ruangan Dihapus' }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-secondary" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user" style="font-size: 12px;"></i>
                                            </div>
                                            <span class="fw-medium text-dark">{{ $jadwal->user->name ?? 'Pengguna Dihapus' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-secondary">{{ $jadwal->tujuan_kegiatan }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="fw-medium text-dark">
                                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 px-md-5 text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-medium">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i> Sedang Dipakai
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                                <i class="fas fa-door-open fs-2"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-2">Semua Ruangan Kosong!</h5>
                                            <p class="text-muted mb-4" style="font-size: 15px;">Belum ada ruangan yang dipinjam untuk hari ini. Fasilitas siap digunakan.</p>
                                            
                                            @auth
                                                <a href="{{ route('peminjaman.tambah') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                                    Pinjam Ruangan Sekarang
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                                                    Login untuk Meminjam
                                                </a>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(count($jadwal_hari_ini) > 0)
            <div class="card-footer bg-white border-top py-3 px-4 px-md-5 text-center text-muted" style="font-size: 13px;">
                Menampilkan total <strong>{{ count($jadwal_hari_ini) }}</strong> kegiatan hari ini.
            </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>