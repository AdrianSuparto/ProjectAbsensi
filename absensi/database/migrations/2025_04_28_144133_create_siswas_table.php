<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('no_kartu')->unique();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->uuid('kelas_siswa_id');
            $table->foreign('kelas_siswa_id')->references('id')->on('kelas_siswas')->restrictOnDelete()->restrictOnUpdate();
            $table->string('nama_ortu');
            $table->string('nomor_ortu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
