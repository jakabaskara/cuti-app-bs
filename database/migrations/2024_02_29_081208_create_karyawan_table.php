<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('NIK');
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->date('TMT_bekerja');
            $table->date('tgl_diangkat_staf')->nullable();
            $table->foreignId('id_posisi')->constrained('posisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
