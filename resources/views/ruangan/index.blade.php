@extends('layouts.main')
@section('title', 'Kelola Ruangan')

@section('content')
<style>
    .hover-lift { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Fasilitas Ruangan</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Tambah, ubah, atau hapus data ruangan sekolah.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" style="background-color: #ecfdf5; color: #065f46;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal menyimpan data!</strong>
        <ul class="mb-0 mt-1" style="font-size: 13px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 hover-lift">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Ruangan Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ruangan.simpan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">NAMA RUANGAN <span class="text-danger">*</span></label>
                        <input type="text" name="nama_ruangan" class="form-control bg-light border-0" placeholder="Misal: Lab Komputer 1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">KAPASITAS (ORANG) <span class="text-danger">*</span></label>
                        <input type="number" name="kapasitas" class="form-control bg-light border-0" placeholder="Misal: 40" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">DESKRIPSI <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control bg-light border-0" rows="3" placeholder="Fasilitas yang ada di dalam ruangan..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px;">FOTO RUANGAN <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="file" name="foto" class="form-control bg-light border-0" accept="image/*">
                        <div class="form-text" style="font-size: 11px;">Maksimal 2MB. Format: JPG, PNG.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold py-2">
                        <i class="fas fa-save me-1"></i> Simpan Ruangan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 hover-lift">
            <div class="card-body table-responsive p-0">
                <table class="table table-borderless align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.8px;">
                            <th class="py-3 ps-4 border-0">Foto</th>
                            <th class="py-3 border-0">Nama & Spesifikasi</th>
                            <th class="py-3 text-center border-0">Status</th>
                            <th class="py-3 text-center pe-4 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data_ruangan as $ruang)
                        <tr class="border-bottom" style="font-size: 14px;">
                            <td class="py-3 ps-4" style="width: 120px;">
                                @if($ruang->foto)
                                    <img src="{{ asset('storage/ruangan/' . $ruang->foto) }}" class="img-fluid rounded-3 shadow-sm" style="width: 90px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted border" style="width: 90px; height: 60px;">
                                        <i class="fas fa-image fs-5 opacity-50"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3">
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">{{ $ruang->nama_ruangan }}</h6>
                                <div class="text-primary fw-medium mb-1" style="font-size: 12px;">
                                    <i class="fas fa-users me-1"></i> {{ $ruang->kapasitas }} Orang
                                </div>
                                <div class="text-muted" style="font-size: 12px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $ruang->deskripsi ?? 'Tidak ada deskripsi.' }}
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                @if(strtolower($ruang->status) == 'tersedia')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-medium">Tersedia</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill fw-medium">Ditutup</span>
                                @endif
                            </td>
                            <td class="py-3 text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px;" data-bs-toggle="modal" data-bs-target="#editModal{{ $ruang->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('ruangan.hapus', $ruang->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px;" onclick="return confirm('Yakin ingin menghapus ruangan ini secara permanen?')" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $ruang->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Edit Data Ruangan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('ruangan.update', $ruang->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-semibold" style="font-size: 12px;">NAMA RUANGAN</label>
                                                <input type="text" name="nama_ruangan" class="form-control bg-light border-0" value="{{ $ruang->nama_ruangan }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-semibold" style="font-size: 12px;">KAPASITAS</label>
                                                <input type="number" name="kapasitas" class="form-control bg-light border-0" value="{{ $ruang->kapasitas }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-semibold" style="font-size: 12px;">DESKRIPSI</label>
                                                <textarea name="deskripsi" class="form-control bg-light border-0" rows="3">{{ $ruang->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-semibold" style="font-size: 12px;">STATUS</label>
                                                <select name="status" class="form-select bg-light border-0" required>
                                                    <option value="tersedia" {{ strtolower($ruang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                    <option value="ditutup" {{ strtolower($ruang->status) == 'ditutup' ? 'selected' : '' }}>Ditutup / Maintenance</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted fw-semibold" style="font-size: 12px;">GANTI FOTO <span class="text-muted fw-normal">(Kosongkan jika tidak diubah)</span></label>
                                                <input type="file" name="foto" class="form-control bg-light border-0" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-door-closed fs-4 text-secondary opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Ruangan</h6>
                                    <p class="mb-0" style="font-size: 14px;">Silakan tambahkan ruangan melalui form di sebelah kiri.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection