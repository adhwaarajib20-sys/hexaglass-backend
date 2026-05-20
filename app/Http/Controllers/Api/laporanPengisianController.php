<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengisian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class LaporanPengisianController extends Controller
{
    /**
     * List semua laporan pengisian
     */
    public function index(Request $request): JsonResponse
    {
        $query = LaporanPengisian::with(['antrean.kendaraan', 'operator']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $laporan = $query->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data laporan pengisian',
            'data'    => $laporan,
        ]);
    }

    /**
     * Detail laporan pengisian
     */
    public function show(int $id): JsonResponse
    {
        $laporan = LaporanPengisian::with(['antrean.kendaraan', 'operator'])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Detail laporan pengisian',
            'data'    => $laporan,
        ]);
    }

    /**
     * Statistik laporan pengisian (mengambil bulan & tahun dari URL)
     */
    public function statistik(int $bulan = null, int $tahun = null): JsonResponse
    {
        $bulan = $bulan ?? Carbon::now()->month;
        $tahun = $tahun ?? Carbon::now()->year;

        $query = LaporanPengisian::whereMonth('tanggal', $bulan)
                                  ->whereYear('tanggal', $tahun);

        return response()->json([
            'status'  => true,
            'message' => 'Statistik laporan pengisian',
            'data'    => [
                'total_pengisian'  => $query->count(),
                'total_gas_liter'  => $query->sum('jumlah_gas_liter'),
                'rata_rata_durasi' => round($query->avg('durasi_menit'), 2),
                'total_prioritas'  => $query->where('is_prioritas', true)->count(),
                'per_hari'         => $this->getPerHari($bulan, $tahun),
            ],
        ]);
    }

    /**
     * Data per hari dalam sebulan
     */
    private function getPerHari(int $bulan, int $tahun): array
    {
        $data = [];
        $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $tanggal = Carbon::create($tahun, $bulan, $i)->toDateString();
            $data[] = [
                'tanggal'     => $tanggal,
                'label'       => $i,
                'total'       => LaporanPengisian::whereDate('tanggal', $tanggal)->count(),
                'total_liter' => LaporanPengisian::whereDate('tanggal', $tanggal)->sum('jumlah_gas_liter'),
            ];
        }

        return $data;
    }
}
