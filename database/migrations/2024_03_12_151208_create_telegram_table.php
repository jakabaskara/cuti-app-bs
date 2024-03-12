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
        Schema::create('telegram', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('id_karyawan')->constrained('karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram');
    }
};
