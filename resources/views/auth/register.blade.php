@extends('layouts.main')
@section('title', 'Registrasi Akun')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #4f46e5;">Buat Akun Baru</h2>
            <p class="text-muted">Daftar untuk mulai meminjam ruangan.</p>
        </div>

        <div class="card card-body p-4 p-md-5">
            {{-- Menampilkan pesan error validasi --}}
            @if($errors->any())
                <div class="alert alert-danger rounded-3" style="font-size: 13px;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 12px;">NAMA LENGKAP</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 bg-light" placeholder="Nama kamu" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 12px;">EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 bg-light" placeholder="siswa@sekolah.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold" style="font-size: 12px;">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 bg-light" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted fw-semibold" style="font-size: 12px;">KONFIRMASI PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password_confirmation" class="form-control border-start-0 bg-light" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fs-6">Daftar Sekarang <i class="fas fa-user-plus ms-2"></i></button>

                <div class="text-center mt-4" style="font-size: 13px;">
                    <span class="text-muted">Sudah punya akun?</span> <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection