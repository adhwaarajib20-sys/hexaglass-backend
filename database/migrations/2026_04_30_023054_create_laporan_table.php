<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')->constrained('users');
            $table->foreignId('verifikator_id')->nullable()->constrained('users');
            $table->string('nama_pelapor');
            $table->string('perusahaan')->nullable();
            $table->date('tanggal_kejadian');
            $table->time('waktu_kejadian');
            $table->string('lokasi');
            $table->enum('klasifikasi', [
                'keselamatan', 'lingkungan', 'kualitas', 'prosedur', 'lainnya'
            ]);
            $table->text('deskripsi');
            $table->text('rekomendasi')->nullable();
            $table->enum('status', [
                'draft', 'terkirim', 'diverifikasi', 'ditolak'
            ])->default('draft');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('laporan_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->onDelete('cascade');
            $table->string('path_foto');
            $table->string('keterangan_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_foto');
        Schema::dropIfExists('laporan');
    }
};