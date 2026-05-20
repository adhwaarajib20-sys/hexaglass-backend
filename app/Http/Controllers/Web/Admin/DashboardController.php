<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Antrean;
use App\Models\Laporan;
use App\Models\LaporanPengisian;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistik hari ini
        $stats = [
            'total_antrean'    => Antrean::whereDate('tanggal', $today)->count(),
            'menunggu'         => Antrean::whereDate('tanggal', $today)->where('status', 'menunggu')->count(),
            'dilayani'         => Antrean::whereDate('tanggal', $today)->where('status', 'dilayani')->count(),
            'selesai'          => Antrean::whereDate('tanggal', $today)->where('status', 'selesai')->count(),
            'total_liter'      => LaporanPengisian::whereDate('tanggal', $today)->sum('jumlah_gas_liter'),
            'total_laporan'    => Laporan::where('status', 'terkirim')->count(),
            'total_operator'   => User::where('status', 'aktif')->whereHas('roles', fn($q) => $q->where('name', 'operator'))->count(),
        ];

        // Grafik 7 hari terakhir
        $grafikHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $grafikHarian[] = [
                'label'   => $tgl->format('d M'),
                'antrean' => Antrean::whereDate('tanggal', $tgl)->count(),
                'liter'   => LaporanPengisian::whereDate('tanggal', $tgl)->sum('jumlah_gas_liter'),
            ];
        }

        // Antrean terbaru
        $antreanTerbaru = Antrean::with(['kendaraan', 'operator'])
            ->whereDate('tanggal', $today)
            ->latest('waktu_daftar')
            ->limit(10)
            ->get();

        // Laporan terbaru
        $laporanTerbaru = Laporan::with('pelapor')
            ->where('status', 'terkirim')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'grafikHarian', 'antreanTerbaru', 'laporanTerbaru'
        ));
    }
}