<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan');
            $table->foreignId('operator_id')->nullable()->constrained('users');
            $table->string('nomor_antrean');
            $table->date('tanggal');
            $table->enum('status', [
                'menunggu', 'dipanggil', 'dilayani', 'selesai', 'batal'
            ])->default('menunggu');
            $table->enum('prioritas', ['normal', 'tinggi'])->default('normal');
            $table->timestamp('waktu_daftar')->nullable();
            $table->timestamp('waktu_dipanggil')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('estimasi_menit')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrean');
    }
};