<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\LaporanExport;
use App\Exports\AntreanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // Export laporan ketidaksesuaian
    public function exportLaporan(Request $request)
    {
        $dari   = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        $filename = 'laporan-ketidaksesuaian';
        if ($dari && $sampai) {
            $filename .= "-{$dari}-sd-{$sampai}";
        }
        $filename .= '.xlsx';

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

        $filename = 'riwayat-antrean';
        if ($dari && $sampai) {
            $filename .= "-{$dari}-sd-{$sampai}";
        }
        $filename .= '.xlsx';

        return Excel::download(
            new AntreanExport($dari, $sampai),
            $filename
        );
    }
}