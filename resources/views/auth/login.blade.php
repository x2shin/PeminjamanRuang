@extends('layouts.main')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color: #4f46e5;">Selamat Datang!</h2>
            <p class="text-muted">Masuk untuk mengelola ruangan sekolah.</p>
        </div>

        <div class="card card-body p-5">
            @if(session('error'))
                <div class="alert alert-danger rounded-3" style="font-size: 14px;">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label text-muted fw-semibold" style="font-size: 13px;">EMAIL ADDRESS</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0 bg-light" placeholder="admin@sekolah.com" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label text-muted fw-semibold" style="font-size: 13px;">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 bg-light" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fs-5">Masuk <i class="fas fa-arrow-right ms-2"></i></button>

                {{-- Link menuju halaman register --}}
                <div class="text-center mt-4" style="font-size: 13px;">
                    <span class="text-muted">Belum punya akun?</span> 
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar sekarang</a>
                </div>
            </form>
        </div>
        
    </div>
</div>
@endsection