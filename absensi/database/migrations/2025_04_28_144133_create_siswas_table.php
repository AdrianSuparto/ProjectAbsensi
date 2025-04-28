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
            $table->uuid('id')->primary();
            $table->string('no_kartu')->unique();
            $table->string('nis')->unique();
            $table->string('nama');
            $table->foreignUuid('kelas_id')->constrained('kelas')->onDelete('cascade');
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
