<?php

namespace App\Http\Controllers\Web\Operator;

use App\Http\Controllers\Controller;
use App\Models\Antrean;
use App\Models\LaporanPengisian;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = Carbon::today();
        $operatorId = auth()->id();

        $stats = [
            'antrean_menunggu' => Antrean::whereDate('tanggal', $today)
                ->where('status', 'menunggu')
                ->where('status_validasi_satpam', 'disetujui')
                ->count(),
            'sedang_diproses'  => Antrean::whereDate('tanggal', $today)
                ->whereIn('status', ['dipanggil', 'dilayani'])
                ->where('operator_id', $operatorId)
                ->count(),
            'selesai_hari_ini' => LaporanPengisian::whereDate('tanggal', $today)
                ->where('operator_id', $operatorId)
                ->count(),
            'total_liter'      => LaporanPengisian::whereDate('tanggal', $today)
                ->where('operator_id', $operatorId)
                ->sum('jumlah_gas_liter'),
        ];

        $antreanAktif = Antrean::with('kendaraan')
            ->whereDate('tanggal', $today)
            ->where('status_validasi_satpam', 'disetujui')
            ->whereIn('status', ['menunggu', 'dipanggil', 'dilayani'])
            ->orderBy('is_prioritas', 'desc')
            ->orderBy('waktu_daftar', 'asc')
            ->limit(10)
            ->get();

        $riwayatPengisian = LaporanPengisian::with(['antrean.kendaraan'])
            ->where('operator_id', $operatorId)
            ->whereDate('tanggal', $today)
            ->latest()
            ->limit(5)
            ->get();

        return view('operator.dashboard', compact(
            'stats', 'antreanAktif', 'riwayatPengisian'
        ));
    }
}