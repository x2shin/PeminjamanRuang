@extends('layouts.main')
@section('title', 'Profil Pengguna')

@section('content')
<style>
    .hover-lift { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .profile-avatar {
        width: 120px;
        height: 120px;
        font-size: 3.5rem;
        background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 100%);
        color: white;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <div>
        <h4 class="fw-bold mb-1">Profil Pengguna</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Kelola informasi akun dan pengaturan keamanan Anda.</p>
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

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal memperbarui profil!</strong>
        <ul class="mb-0 mt-1" style="font-size: 13px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 hover-lift">
            <div class="d-flex justify-content-center mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm profile-avatar fw-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
            <p class="text-muted mb-3" style="font-size: 14px;">{{ Auth::user()->email }}</p>
            
            <div class="d-flex justify-content-center gap-2 mb-3">
                @if(Auth::user()->peran == 'admin')
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><i class="fas fa-shield-alt me-1"></i> Administrator</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill"><i class="fas fa-user-graduate me-1"></i> Siswa / User</span>
                @endif
            </div>
            
            <hr class="text-muted opacity-25">
            <div class="text-start mt-3">
                <p class="text-muted mb-1" style="font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">Bergabung Sejak</p>
                <h6 class="fw-semibold text-dark"><i class="far fa-calendar-check text-primary me-2"></i>{{ \Carbon\Carbon::parse(Auth::user()->created_at)->translatedFormat('d F Y') }}</h6>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 hover-lift">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-user-edit text-primary me-2"></i>Edit Informasi Akun</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">NAMA LENGKAP <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->name }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">ALAMAT EMAIL <span class="text-muted fw-normal">(Dikunci)</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" class="form-control bg-light border-0 py-2 text-muted" value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">
                    
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 14px;"><i class="fas fa-lock text-primary me-2"></i>Ubah Password (Opsional)</h6>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-4 border border-primary-subtle">
                        <p class="mb-0 text-primary fw-medium" style="font-size: 12px;"><i class="fas fa-info-circle me-1"></i> Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengganti password.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">PASSWORD LAMA</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span>
                            <input type="password" name="password_lama" class="form-control bg-light border-0 py-2" placeholder="Masukkan password saat ini">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size: 12px;">PASSWORD BARU</label>
                            <input type="password" name="password_baru" class="form-control shadow-sm bg-light border-0 py-2" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted fw-semibold" style="font-size: 12px;">KONFIRMASI PASSWORD BARU</label>
                            <input type="password" name="password_baru_confirmation" class="form-control shadow-sm bg-light border-0 py-2" placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold py-2">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection