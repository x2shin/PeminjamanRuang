<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman'; // Memberi tahu Laravel nama tabel pastinya
    protected $fillable = ['user_id', 'ruangan_id', 'waktu_mulai', 'waktu_selesai', 'tujuan_kegiatan', 'status'];

    public function ruangan() {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}