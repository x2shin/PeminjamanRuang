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
    Schema::create('ruangan', function (Blueprint $table) {
        $table->id();
        $table->string('nama_ruangan'); // Contoh: Lab Komputer 1
        $table->integer('kapasitas'); // Kapasitas orang
        $table->enum('status', ['tersedia', 'perbaikan'])->default('tersedia');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangans');
    }
};
