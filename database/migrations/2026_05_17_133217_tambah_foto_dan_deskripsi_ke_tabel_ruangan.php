<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('nama_ruangan');
            $table->string('foto')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'foto']);
        });
    }
};