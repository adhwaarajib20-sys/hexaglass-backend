<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\LaporanExport;
use App\Exports\AntreanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Hitung range tanggal berdasarkan period
     */
    private function getDateRange($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'hari':
                return [
                    $now->copy()->startOfDay(),
                    $now->copy()->endOfDay(),
                ];
            case 'minggu':
                return [
                    $now->copy()->startOfWeek(Carbon::MONDAY),
                    $now->copy()->endOfWeek(Carbon::SUNDAY),
                ];
            case 'bulan':
                return [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth(),
                ];
            default:
                return [null, null];
        }
    }

    /**
     * Tentukan nama file berdasarkan period
     */
    private function getFileName($baseFileName, $period)
    {
        $dateRange = $this->getDateRange($period);
        
        if ($period && $dateRange[0]) {
            $label = match($period) {
                'hari' => 'harian-' . $dateRange[0]->format('Y-m-d'),
                'minggu' => 'mingguan-' . $dateRange[0]->format('Y-m-d') . '-sd-' . $dateRange[1]->format('Y-m-d'),
                'bulan' => 'bulanan-' . $dateRange[0]->format('Y-m'),
                default => ''
            };
            return $baseFileName . '-' . $label . '.xlsx';
        }
        
        return $baseFileName . '.xlsx';
    }

    // Export laporan ketidaksesuaian
    public function exportLaporan(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        // Validasi harus ada kedua tanggal
        if (!$dari || !$sampai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pilih tanggal mulai (dari_tanggal) dan tanggal akhir (sampai_tanggal)',
            ], 400);
        }

        if ($dari > $sampai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
            ], 400);
        }

        $filename = "laporan-ketidaksesuaian-{$dari}-sd-{$sampai}.xlsx";

        return Excel::download(
            new LaporanExport($dari, $sampai),
            $filename
        );
    }

    // Export riwayat antrean
    public function exportAntrean(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        // Validasi harus ada kedua tanggal
        if (!$dari || !$sampai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pilih tanggal mulai (dari_tanggal) dan tanggal akhir (sampai_tanggal)',
            ], 400);
        }

        if ($dari > $sampai) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir',
            ], 400);
        }

        $filename = "riwayat-antrean-{$dari}-sd-{$sampai}.xlsx";

        return Excel::download(
            new AntreanExport($dari, $sampai),
            $filename
        );
    }
}