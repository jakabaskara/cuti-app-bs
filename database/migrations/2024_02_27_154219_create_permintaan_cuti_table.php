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
            $table->enum('jenis_cuti',['panjang','tahunan']);
            $table->string('alasan');
            $table->string('alamat');
            $table->enum('status',['disetujui','dibatalkan','pending']);
            $table->foreignId('id_unit_kerja')->constrained('unit_kerja');
            $table->integer('sisa_cuti');
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
