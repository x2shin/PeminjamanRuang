<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Ruangan</title>
    <style>
        /* Pengaturan CSS khusus untuk Cetak/PDF yang sangat stabil */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 12px; 
            color: #333; 
            margin: 0;
            padding: 20px;
        }
        .header { 
            text-align: center; 
            border-bottom: 3px double #333; 
            padding-bottom: 15px; 
            margin-bottom: 25px; 
        }
        .header h2 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .header h4 { margin: 5px 0 0; font-size: 14px; font-weight: normal; color: #555; }
        .header p { margin: 8px 0 0; font-size: 11px; font-style: italic; color: #777; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
        }
        th, td { 
            border: 1px solid #444; 
            padding: 10px 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #f4f4f4; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 11px; 
            text-align: center;
        }
        .text-center { text-align: center; }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #ccc;
        }
        
        /* Area Tanda Tangan */
        .footer-ttd { 
            float: right; 
            width: 280px; 
            text-align: center; 
            margin-top: 30px;
        }
        .footer-ttd p { margin: 0 0 70px; }
        .nama-ttd { 
            font-weight: bold; 
            text-decoration: underline; 
            margin: 0;
        }
        
        /* Hilangkan elemen yang tidak perlu saat dicetak */
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            🖨️ Cetak / Simpan ke PDF Sekarang
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-left: 10px;">
            Tutup Halaman
        </button>
    </div>

    <div class="header">
        <h2>LAPORAN PEMINJAMAN RUANGAN SEKOLAH</h2>
        <h4>Sistem Informasi Manajemen Fasilitas & Aset</h4>
        <p>Dicetak oleh: {{ Auth::user()->name }} | Waktu Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Peminjam</th>
                <th style="width: 20%;">Ruangan</th>
                <th style="width: 25%;">Tujuan Kegiatan</th>
                <th style="width: 20%;">Waktu Pelaksanaan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_peminjaman ?? [] as $index => $pinjam)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $pinjam->user->name ?? 'User Dihapus' }}</strong><br>
                    <span style="font-size: 11px; color: #666;">User ID: #{{ $pinjam->user_id }}</span>
                </td>
                <td>{{ $pinjam->ruangan->nama_ruangan ?? 'Ruangan Dihapus' }}</td>
                <td>{{ $pinjam->tujuan_kegiatan }}</td>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($pinjam->waktu_mulai)->format('d/m/Y') }}</strong><br>
                    {{ \Carbon\Carbon::parse($pinjam->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($pinjam->waktu_selesai)->format('H:i') }} WIB
                </td>
                <td class="text-center">
                    <span class="badge">{{ $pinjam->status }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px;">Tidak ada data peminjaman yang dapat dicetak.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Malang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Administrator Fasilitas,</p>
        <p class="nama-ttd">{{ Auth::user()->name }}</p>
        <span style="font-size: 11px; color: #555;">NIP. ........................................</span>
    </div>

</body>
</html>