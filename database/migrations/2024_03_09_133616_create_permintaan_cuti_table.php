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
        Schema::create('permintaan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan');
            // $table->foreignId('id_jenis_cuti')->constrained('jenis_cuti');
            $table->integer('jumlah_cuti_panjang');
            $table->integer('jumlah_cuti_tahunan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('alamat');
            $table->string('alasan');
            $table->string('alasan_ditolak')->nullable();
            $table->foreignId('id_posisi_pembuat')->constrained('posisi');
            $table->boolean('is_approved');
            $table->boolean('is_rejected');
            $table->boolean('is_checked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_cuti');
    }
};
