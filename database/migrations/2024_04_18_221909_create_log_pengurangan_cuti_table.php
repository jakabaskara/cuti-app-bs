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
        Schema::create('log_pengurangan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan');
            $table->integer('sisa_cuti_awal');
            $table->integer('sisa_cuti_setelah');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_pengurangan_cuti');
    }
};
