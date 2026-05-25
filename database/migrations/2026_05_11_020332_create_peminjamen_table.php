<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id();
        // user_id tetap bahasa Inggris karena bawaan fitur Login (auth) Laravel menggunakan tabel 'users'
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); 
        $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete(); 
        $table->dateTime('waktu_mulai'); 
        $table->dateTime('waktu_selesai');   
        $table->string('tujuan_kegiatan');      
$table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
