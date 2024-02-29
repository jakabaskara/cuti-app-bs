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
        Schema::create('sisa_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan');
            $table->foreignId('id_jenis_cuti')->constrained('jenis_cuti');
            $table->date('periode_mulai');
            $table->date('periode_akhir');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisa_cuti');
    }
};
