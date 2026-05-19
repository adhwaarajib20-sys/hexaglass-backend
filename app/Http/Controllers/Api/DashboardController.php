<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrean;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();
        $bulan   = $request->bulan   ?? Carbon::now()->month;
        $tahun   = $request->tahun   ?? Carbon::now()->year;

        return response()->json([
            'status'  => true,
            'message' => 'Data dashboard berhasil diambil',
            'data'    => [
                'antrean' => $this->statistikAntrean($tanggal, $bulan, $tahun),
                'laporan' => $this->statistikLaporan($bulan, $tahun),
                'user'    => $this->statistikUser(),
                'grafik'  => $this->grafikAntrean($bulan, $tahun),
            ],
        ]);
    }

    private function statistikAntrean($tanggal, $bulan, $tahun)
    {
        return [
            'hari_ini' => [
                'total'     => Antrean::whereDate('tanggal', $tanggal)->count(),
                'menunggu'  => Antrean::whereDate('tanggal', $tanggal)->where('status', 'menunggu')->count(),
                'dipanggil' => Antrean::whereDate('tanggal', $tanggal)->where('status', 'dipanggil')->count(),
                'dilayani'  => Antrean::whereDate('tanggal', $tanggal)->where('status', 'dilayani')->count(),
                'selesai'   => Antrean::whereDate('tanggal', $tanggal)->where('status', 'selesai')->count(),
                'batal'     => Antrean::whereDate('tanggal', $tanggal)->where('status', 'batal')->count(),
            ],
            'bulan_ini' => [
                'total'   => Antrean::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count(),
                'selesai' => Antrean::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'selesai')->count(),
                'batal'   => Antrean::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'batal')->count(),
            ],
        ];
    }

    private function statistikLaporan($bulan, $tahun)
    {
        return [
            'total'        => Laporan::count(),
            'terkirim'     => Laporan::where('status', 'terkirim')->count(),
            'diverifikasi' => Laporan::where('status', 'diverifikasi')->count(),
            'ditolak'      => Laporan::where('status', 'ditolak')->count(),
            'bulan_ini'    => Laporan::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'per_klasifikasi' => [
                'keselamatan' => Laporan::where('klasifikasi', 'keselamatan')->count(),
                'lingkungan'  => Laporan::where('klasifikasi', 'lingkungan')->count(),
                'kualitas'    => Laporan::where('klasifikasi', 'kualitas')->count(),
                'prosedur'    => Laporan::where('klasifikasi', 'prosedur')->count(),
                'lainnya'     => Laporan::where('klasifikasi', 'lainnya')->count(),
            ],
        ];
    }

    // ✅ FIXED: Gunakan whereHas roles dari Spatie Permission
    private function statistikUser()
    {
        return [
            'total'    => User::count(),
            'aktif'    => User::where('status', 'aktif')->count(),
            'nonaktif' => User::where('status', 'nonaktif')->count(),
            'per_role' => [
                'admin'    => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count(),
                'operator' => User::whereHas('roles', fn($q) => $q->where('name', 'operator'))->count(),
                'satpam'   => User::whereHas('roles', fn($q) => $q->where('name', 'satpam'))->count(),
                'supir'    => User::whereHas('roles', fn($q) => $q->where('name', 'supir'))->count(),
            ],
        ];
    }

    private function grafikAntrean($bulan, $tahun)
    {
        $tujuhHari = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal     = Carbon::now()->subDays($i);
            $tujuhHari[] = [
                'tanggal' => $tanggal->toDateString(),
                'label'   => $tanggal->format('d M'),
                'total'   => Antrean::whereDate('tanggal', $tanggal)->count(),
                'selesai' => Antrean::whereDate('tanggal', $tanggal)->where('status', 'selesai')->count(),
            ];
        }

        $perBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $perBulan[] = [
                'bulan' => Carbon::create($tahun, $i, 1)->format('M'),
                'total' => Antrean::whereMonth('tanggal', $i)->whereYear('tanggal', $tahun)->count(),
            ];
        }

        return [
            '7_hari_terakhir' => $tujuhHari,
            'per_bulan'       => $perBulan,
        ];
    }

    public function rekap(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date|after_or_equal:dari_tanggal',
        ]);

        $antrean = Antrean::with(['kendaraan'])
            ->whereBetween('tanggal', [$request->dari_tanggal, $request->sampai_tanggal])
            ->get();

        $laporan = Laporan::with(['pelapor', 'verifikator'])
            ->whereBetween('tanggal_kejadian', [$request->dari_tanggal, $request->sampai_tanggal])
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Rekap operasional berhasil diambil',
            'data'    => [
                'periode' => [
                    'dari'   => $request->dari_tanggal,
                    'sampai' => $request->sampai_tanggal,
                ],
                'ringkasan' => [
                    'total_antrean'        => $antrean->count(),
                    'antrean_selesai'      => $antrean->where('status', 'selesai')->count(),
                    'antrean_batal'        => $antrean->where('status', 'batal')->count(),
                    'total_laporan'        => $laporan->count(),
                    'laporan_diverifikasi' => $laporan->where('status', 'diverifikasi')->count(),
                ],
                'antrean' => $antrean,
                'laporan' => $laporan,
            ],
        ]);
    }
}