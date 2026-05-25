@extends('layouts.main')
@section('title', 'Form Peminjaman Ruangan')

@section('content')
<style>
    .hover-lift { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <div>
        <h4 class="fw-bold mb-1">Ajukan Peminjaman</h4>
        <p class="text-muted mb-0" style="font-size: 14px;">Lengkapi formulir di bawah ini untuk memesan ruangan.</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-light shadow-sm rounded-pill px-4 fw-semibold">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row justify-content-center mb-5">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 hover-lift overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 px-md-5">
                <h6 class="fw-bold text-primary mb-0"><i class="fas fa-edit me-2"></i>Formulir Data Peminjaman</h6>
            </div>

            <div class="card-body p-4 p-md-5 pt-3">
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><strong>Cek kembali isian Anda:</strong>
                    <ul class="mb-0 mt-2 text-start" style="font-size: 14px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('peminjaman.simpan') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">PILIH RUANGAN <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0 px-3"><i class="fas fa-door-open text-muted"></i></span>
                            <select name="ruangan_id" id="ruanganSelect" class="form-select bg-light border-0 py-2" required>
                                <option value="" disabled {{ old('ruangan_id') ? '' : 'selected' }}>-- Silakan Pilih Ruangan --</option>
                                @foreach($data_ruangan as $ruang)
                                    <option value="{{ $ruang->id }}" 
                                            {{ old('ruangan_id') == $ruang->id ? 'selected' : '' }}
                                            data-foto="{{ $ruang->foto ? asset('storage/ruangan/' . $ruang->foto) : '' }}"
                                            data-deskripsi="{{ $ruang->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}">
                                        {{ $ruang->nama_ruangan }} (Kapasitas: {{ $ruang->kapasitas }} orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="previewRuangan" class="mt-3 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary-subtle d-none">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img id="previewFoto" src="" alt="Foto Ruangan" class="rounded-3 shadow-sm" style="width: 80px; height: 60px; object-fit: cover; display: none;">
                                    <div id="noFotoBadge" class="bg-white text-muted border rounded-3 d-flex flex-column align-items-center justify-content-center shadow-sm" style="width: 80px; height: 60px; font-size: 10px; display: none;">
                                        <i class="fas fa-image fs-5 mb-1 opacity-50"></i> No Photo
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold text-primary mb-1" style="font-size: 13px;"><i class="fas fa-info-circle me-1"></i> Info Ruangan</h6>
                                    <p id="previewDeskripsi" class="text-dark mb-0" style="font-size: 13px; line-height: 1.4;"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label text-muted fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">WAKTU MULAI (WIB) <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm rounded-3">
                                <span class="input-group-text bg-light border-0 px-3"><i class="fas fa-calendar-check text-muted"></i></span>
                                <input type="datetime-local" name="waktu_mulai" id="waktuMulai" class="form-control bg-light border-0 py-2" value="{{ old('waktu_mulai') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">WAKTU SELESAI (WIB) <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm rounded-3">
                                <span class="input-group-text bg-light border-0 px-3"><i class="fas fa-calendar-times text-muted"></i></span>
                                <input type="datetime-local" name="waktu_selesai" id="waktuSelesai" class="form-control bg-light border-0 py-2" value="{{ old('waktu_selesai') }}" required>
                            </div>
                            <div class="form-text mt-2 text-primary fw-medium" style="font-size: 11px;" id="infoWaktu"><i class="fas fa-clock me-1"></i>Waktu selesai harus lebih lambat.</div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label text-muted fw-semibold" style="font-size: 12px; letter-spacing: 0.5px;">TUJUAN KEGIATAN / ACARA <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0 px-3"><i class="fas fa-bullseye text-muted"></i></span>
                            <input type="text" name="tujuan_kegiatan" class="form-control bg-light border-0 py-2" placeholder="Misal: Ekskul Rohis, Rapat OSIS..." value="{{ old('tujuan_kegiatan') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm hover-lift" style="font-size: 15px;">
                        Kirim Pengajuan Sekarang <i class="fas fa-paper-plane ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // LOGIKA PREVIEW GAMBAR RUANGAN
        // ==========================================
        const ruanganSelect = document.getElementById('ruanganSelect');
        const previewRuangan = document.getElementById('previewRuangan');
        const previewFoto = document.getElementById('previewFoto');
        const noFotoBadge = document.getElementById('noFotoBadge');
        const previewDeskripsi = document.getElementById('previewDeskripsi');

        function updatePreview() {
            if(ruanganSelect.selectedIndex <= 0) return; 
            
            const selectedOption = ruanganSelect.options[ruanganSelect.selectedIndex];
            const fotoUrl = selectedOption.getAttribute('data-foto');
            const deskripsi = selectedOption.getAttribute('data-deskripsi');

            previewRuangan.classList.remove('d-none');
            previewDeskripsi.textContent = deskripsi;

           if (fotoUrl) {
                previewFoto.src = fotoUrl;
                previewFoto.style.setProperty('display', 'block', 'important');
                noFotoBadge.style.setProperty('display', 'none', 'important');
            } else {
                previewFoto.style.setProperty('display', 'none', 'important');
                noFotoBadge.style.setProperty('display', 'flex', 'important');
            }
        }

        updatePreview();
        ruanganSelect.addEventListener('change', updatePreview);

        // ==========================================
        // LOGIKA WAKTU PINTAR (ANTI SALAH INPUT)
        // ==========================================
        const waktuMulai = document.getElementById('waktuMulai');
        const waktuSelesai = document.getElementById('waktuSelesai');
        const infoWaktu = document.getElementById('infoWaktu');

        waktuMulai.addEventListener('change', function() {
            if(this.value) {
                waktuSelesai.min = this.value;
                
                if(!waktuSelesai.value || waktuSelesai.value <= this.value) {
                    let date = new Date(this.value);
                    date.setHours(date.getHours() + 1); 
                    
                    let tzoffset = (new Date()).getTimezoneOffset() * 60000;
                    let localISOTime = (new Date(date - tzoffset)).toISOString().slice(0, 16);
                    
                    waktuSelesai.value = localISOTime;
                    
                    infoWaktu.innerHTML = '<i class="fas fa-magic me-1"></i> Diisi otomatis +1 Jam. Silakan sesuaikan.';
                    infoWaktu.className = "form-text mt-2 text-success fw-medium";
                }
            }
        });

        waktuSelesai.addEventListener('change', function() {
            if(waktuMulai.value && this.value <= waktuMulai.value) {
                alert('Waktu tidak valid! Waktu selesai harus lebih lambat dari waktu mulai.');
                this.value = ''; 
                infoWaktu.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Waktu tidak valid!';
                infoWaktu.className = "form-text mt-2 text-danger fw-bold";
            } else {
                infoWaktu.innerHTML = '<i class="fas fa-check-circle me-1"></i> Waktu valid.';
                infoWaktu.className = "form-text mt-2 text-success fw-medium";
            }
        });
    });
</script>
@endpush