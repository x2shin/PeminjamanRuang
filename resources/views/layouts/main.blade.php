<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Ruang - @yield('title', 'Aplikasi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fb; }
        .navbar-brand { color: #4f46e5 !important; font-weight: 700; }
        .btn-primary { background-color: #4f46e5; border-color: #4f46e5; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg py-3 bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('beranda') }}"><i class="fas fa-building me-2"></i>Peminjaman Ruang</a>
            <div>
                @auth
                    {{-- Menu Khusus Admin --}}
                    @if(Auth::user()->peran == 'admin')
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary fw-semibold px-3 me-2 rounded-pill"><i class="fas fa-users me-1"></i>Kelola Pengguna</a>
                        <a href="{{ route('ruangan.index') }}" class="btn btn-outline-secondary fw-semibold px-3 me-2 rounded-pill"><i class="fas fa-cog me-1"></i>Kelola Ruangan</a>
                    @endif
                    
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary fw-semibold px-3 me-2 rounded-pill">Dashboard</a>
                    
                    <a href="{{ route('profil') }}" class="btn btn-light fw-semibold px-3 me-2 rounded-pill shadow-sm">
                        <i class="fas fa-user-circle me-1 text-primary"></i>Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill px-3">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark fw-semibold px-4 me-2 rounded-pill">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary fw-semibold px-4 rounded-pill">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========================================================
            // 1. MENGUBAH SEMUA POP-UP CONFIRM BAWAAN MENJADI SWEETALERT
            // ========================================================
            document.querySelectorAll('[onclick*="confirm"]').forEach(function(element) {
                let match = element.getAttribute('onclick').match(/confirm\(['"](.*?)['"]\)/);
                if (match) {
                    let message = match[1];
                    element.removeAttribute('onclick'); // Hapus pop-up jelek bawaan browser
                    
                    element.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Deteksi kata kunci untuk menentukan warna tombol
                        let isDanger = message.toLowerCase().includes('hapus') || 
                                       message.toLowerCase().includes('tolak') || 
                                       message.toLowerCase().includes('batal');
                        let confirmColor = isDanger ? '#ef4444' : '#4f46e5'; 

                        Swal.fire({
                            title: 'Konfirmasi Aksi',
                            text: message,
                            icon: isDanger ? 'warning' : 'question',
                            showCancelButton: true,
                            confirmButtonColor: confirmColor,
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Lanjutkan!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                confirmButton: 'rounded-pill px-4 shadow-sm',
                                cancelButton: 'rounded-pill px-4 shadow-sm'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (element.tagName.toLowerCase() === 'a') {
                                    window.location.href = element.href;
                                } else if (element.tagName.toLowerCase() === 'button' && element.closest('form')) {
                                    element.closest('form').submit();
                                }
                            }
                        });
                    });
                }
            });

            // ========================================================
            // 2. NOTIFIKASI TOAST UNTUK SUCCESS DAN ERROR
            // ========================================================
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true,
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                });
            @endif
        });
    </script>
    
    @stack('scripts')
</body>
</html>