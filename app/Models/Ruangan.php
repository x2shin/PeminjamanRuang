<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan'; // Sesuaikan jika nama tabel Anda berbeda
    protected $fillable = ['nama_ruangan', 'kapasitas', 'deskripsi', 'foto', 'status'];

    // TAMBAHKAN FUNGSI INI:
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'ruangan_id');
    }
}