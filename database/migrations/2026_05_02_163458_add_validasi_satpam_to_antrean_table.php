<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->enum('status_validasi_satpam', [
                'menunggu_validasi',
                'disetujui',
                'ditolak'
            ])->default('menunggu_validasi')->after('status');
            $table->foreignId('satpam_id')->nullable()->constrained('users')->nullOnDelete()->after('operator_id');
            $table->text('alasan_penolakan')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $table->dropColumn(['status_validasi_satpam', 'satpam_id', 'alasan_penolakan']);
        });
    }
};