<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pengisian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrean_id')->constrained('antrean')->onDelete('cascade');
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('jumlah_gas_liter', 10, 2);
            $table->integer('durasi_menit')->nullable();
            $table->decimal('estimasi_menit', 10, 2)->nullable();
            $table->boolean('is_prioritas')->default(false);
            $table->text('alasan_prioritas')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pengisian');
    }
};