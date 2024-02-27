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
        Schema::create('sisa_cuti_tahunan', function (Blueprint $table) {
            $table->id();
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->integer('sisa_cuti');
            $table->foreignId('id_karyawan')->constrained('karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisa_cuti_tahunan');
    }
};
