<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            // Estimasi selesai (estimasi_menit sudah ada)
            if (!Schema::hasColumn('antrean', 'estimasi_selesai')) {
                $table->timestamp('estimasi_selesai')->nullable()->after('estimasi_menit');
            }

            // Prioritas
            if (!Schema::hasColumn('antrean', 'is_prioritas')) {
                $table->boolean('is_prioritas')->default(false)->after('prioritas');
            }
            if (!Schema::hasColumn('antrean', 'alasan_prioritas')) {
                $table->text('alasan_prioritas')->nullable()->after('is_prioritas');
            }

            // Hasil pengisian
            if (!Schema::hasColumn('antrean', 'jumlah_gas_liter')) {
                $table->decimal('jumlah_gas_liter', 10, 2)->nullable()->after('alasan_prioritas');
            }
            if (!Schema::hasColumn('antrean', 'catatan_pengisian')) {
                $table->text('catatan_pengisian')->nullable()->after('jumlah_gas_liter');
            }
        });

        // Tambah foreign key ke informasi_perusahaan setelah tabel dibuat
        // (akan ditambahkan setelah migration informasi_perusahaan selesai)
    }

    public function down(): void
    {
        Schema::table('antrean', function (Blueprint $table) {
            $columns = [
                'estimasi_selesai',
                'is_prioritas',
                'alasan_prioritas',
                'jumlah_gas_liter',
                'catatan_pengisian',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('antrean', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};