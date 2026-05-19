<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            // Hapus qr_code dari kendaraan
            $table->dropUnique(['qr_code']);
            $table->dropColumn('qr_code');

            // Tambah data supir
            $table->string('nama_supir')->after('user_id');
            $table->string('no_hp_supir')->nullable()->after('nama_supir');
            $table->string('no_ktp_supir')->nullable()->after('no_hp_supir');
        });
    }

    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->string('qr_code')->unique()->nullable();
            $table->dropColumn(['nama_supir', 'no_hp_supir', 'no_ktp_supir']);
        });
    }
};