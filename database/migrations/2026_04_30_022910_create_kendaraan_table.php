<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_polisi')->unique();
            $table->string('jenis_kendaraan');
            $table->string('kapasitas_tangki');
            $table->string('perusahaan')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->enum('status_validasi', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};