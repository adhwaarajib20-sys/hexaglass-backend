<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->unsignedInteger('estimasi_validasi_satpam')->nullable()->after('estimasi_menit')->comment('Estimasi waktu validasi satpam dalam menit');
            $table->unsignedInteger('estimasi_pengisian_operator')->nullable()->after('estimasi_validasi_satpam')->comment('Estimasi waktu pengisian operator dalam menit');
            $table->dateTime('waktu_estimasi_validasi_dikirim')->nullable()->after('waktu_daftar')->comment('Waktu estimasi validasi dikirim ke supir');
            $table->dateTime('waktu_estimasi_pengisian_dikirim')->nullable()->after('waktu_estimasi_validasi_dikirim')->comment('Waktu estimasi pengisian dikirim ke supir');
        });
    }

    public function down(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->dropColumn([
                'estimasi_validasi_satpam',
                'estimasi_pengisian_operator',
                'waktu_estimasi_validasi_dikirim',
                'waktu_estimasi_pengisian_dikirim',
            ]);
        });
    }
};
