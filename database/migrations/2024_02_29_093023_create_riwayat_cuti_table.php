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
        Schema::create('riwayat_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_permintaan_cuti');
            $table->string('nama_approver');
            $table->string('nama_checker');
            $table->string('jabatan_approver');
            $table->string('jabatan_checker');
            $table->foreignId('id_unit_kerja')->constrained('unit_kerja');
            $table->date('approval_date');
            $table->date('checked_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_cuti');
    }
};
