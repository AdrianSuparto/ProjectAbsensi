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
        Schema::create('izin_sakits', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('siswa_id');
            $table->foreign('siswa_id')->references('id')->on('siswas')->restrictOnDelete()->restrictOnUpdate();
            $table->date('tanggal');
            $table->enum('jenis', ['Izin', 'Sakit']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_sakits');
    }
};
