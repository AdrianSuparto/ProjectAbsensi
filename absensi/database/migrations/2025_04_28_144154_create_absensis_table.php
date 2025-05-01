<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswas')->restrictOnDelete()->restrictOnUpdate();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status_masuk', ['Hadir', 'Terlambat', 'Tidak Masuk', 'Izin', 'Sakit', 'Libur'])->nullable();
            $table->enum('status_pulang', ['Pulang', 'Belum Pulang', 'Tidak Masuk', 'Izin', 'Sakit', 'Libur'])->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
