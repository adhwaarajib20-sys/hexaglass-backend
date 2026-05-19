<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasi_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->boolean('is_prioritas')->default(false);
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('rencana_pengisian_harian', 10, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_perusahaan');
    }
};