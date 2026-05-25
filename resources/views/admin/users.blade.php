@extends('layouts.main')
@section('title', 'Kelola Pengguna')

@section('content')
<style>
    .hover-lift { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pengguna</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Kelola hak akses dan akun siswa yang terdaftar di sistem.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" style="background-color: #ecfdf5; color: #065f46;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 hover-lift mb-5">
    <div class="card-header bg-white border-bottom pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-users text-primary me-2"></i>Daftar Akun Terdaftar</h6>
        <span class="badge bg-primary rounded-pill px-3 py-2">{{ count($users ?? []) }} Total User</span>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-borderless align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr class="text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                    <th class="py-3 ps-4 border-0">Nama Pengguna</th>
                    <th class="py-3 border-0">Email</th>
                    <th class="py-3 border-0">Bergabung Sejak</th>
                    <th class="py-3 text-center border-0">Peran / Hak Akses</th>
                    <th class="py-3 text-center pe-4 border-0">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr class="border-bottom" style="font-size: 14px;">
                    <td class="py-3 ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px; font-size: 16px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="fw-bold text-dark" style="font-size: 15px;">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td class="py-3 text-secondary">{{ $user->email }}</td>
                    <td class="py-3 text-secondary">
                        {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-3 text-center">
                        @if($user->peran == 'admin')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-shield-alt me-1"></i> Administrator</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill fw-medium"><i class="fas fa-user-graduate me-1"></i> Siswa / User</span>
                        @endif
                    </td>
                    <td class="py-3 text-center pe-4">
                        @if(Auth::id() == $user->id)
                            <span class="badge bg-light text-muted border px-3 py-2 rounded-pill"><i class="fas fa-lock me-1"></i> Akun Anda</span>
                        @else
                            <div class="d-flex justify-content-center gap-2">
                                <form action="{{ route('admin.users.peran', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" onclick="return confirm('Ubah hak akses pengguna ini?')" title="Ganti Peran">
                                        <i class="fas fa-exchange-alt me-1"></i> Jadikan {{ $user->peran == 'admin' ? 'Siswa' : 'Admin' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.users.hapus', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" style="width: 32px; height: 32px;" onclick="return confirm('Hapus akun ini secara permanen?')" title="Hapus Akun">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted py-4">
                            <h6 class="fw-bold text-dark mb-1">Data Pengguna Kosong</h6>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection