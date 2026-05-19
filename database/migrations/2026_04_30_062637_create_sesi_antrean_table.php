<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satpam_id')->constrained('users')->onDelete('cascade');
            $table->string('qr_code')->unique();        // QR Code unik per sesi
            $table->string('qr_token')->unique();       // Token untuk validasi
            $table->enum('status', [
                'aktif',      // QR sudah di-generate, menunggu supir scan
                'digunakan',  // Supir sudah scan & isi data
                'kadaluarsa', // QR expired
            ])->default('aktif');
            $table->timestamp('expired_at');            // QR berlaku berapa menit
            $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraan')->nullOnDelete();
            $table->foreignId('antrean_id')->nullable()->constrained('antrean')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_antrean');
    }
};