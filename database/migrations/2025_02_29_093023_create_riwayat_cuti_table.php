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
            $table->foreignId('id_permintaan_cuti')->constrained('permintaan_cuti');
            $table->string('nama_pembuat')->nullable();
            $table->string('nama_approver')->nullable();
            $table->string('nama_checker')->nullable();
            $table->string('jabatan_approver')->nullable();
            $table->string('jabatan_pembuat')->nullable();
            $table->string('jabatan_checker')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('chekced_date')->nullable();
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
