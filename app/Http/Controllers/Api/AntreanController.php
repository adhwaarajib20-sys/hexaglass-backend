<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrean;
use App\Models\LaporanPengisian;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AntreanController extends Controller
{
    /**
     * List semua antrean hari ini (atau sesuai tanggal) - HANYA YANG DISETUJUI SATPAM
     */
    public function index(Request $request): JsonResponse
    {
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        $antrean = Antrean::with(['kendaraan', 'operator', 'laporanPengisian'])
            ->whereDate('tanggal', $tanggal)
            ->where('status_validasi_satpam', 'disetujui')
            ->orderBy('is_prioritas', 'desc')
            ->orderBy('prioritas', 'desc')
            ->orderBy('waktu_daftar', 'asc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data antrean berhasil diambil',
            'data'    => $antrean,
        ]);
    }

    /**
     * Detail antrean
     */
    public function show(int $id): JsonResponse
    {
        $antrean = Antrean::with([
            'kendaraan',
            'operator',
            'laporanPengisian',
            'informasiPerusahaan',
        ])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Detail antrean',
            'data'    => $antrean,
        ]);
    }

    /**
     * Update status antrean (oleh Operator)
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:menunggu,dipanggil,dilayani,selesai,batal',
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->status = $request->status;

        if ($request->status === 'dipanggil') {
            $antrean->waktu_dipanggil = Carbon::now();
            $antrean->operator_id     = $request->user()->id;
        }

        if ($request->status === 'selesai') {
            $antrean->waktu_selesai = Carbon::now();
        }

        if ($request->filled('keterangan')) {
            $antrean->keterangan = $request->keterangan;
        }

        $antrean->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status antrean berhasil diupdate',
            'data'    => $antrean->load(['kendaraan', 'operator']),
        ]);
    }

    /**
     * Panggil antrean berikutnya (tampilkan saja, tidak ubah status)
     */
    public function panggilBerikutnya(): JsonResponse
    {
        $antrean = Antrean::with(['kendaraan', 'informasiPerusahaan'])
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'menunggu')
            ->where('status_validasi_satpam', 'disetujui')
            ->orderBy('is_prioritas', 'desc')
            ->orderBy('prioritas', 'desc')
            ->orderBy('waktu_daftar', 'asc')
            ->first();

        if (!$antrean) {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada antrean yang menunggu',
                'data'    => null,
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Antrean berikutnya',
            'data'    => $antrean,
        ]);
    }

    /**
     * Antrean pending validasi satpam
     */
    public function antreanPending(): JsonResponse
    {
        $antrean = Antrean::with(['kendaraan'])
            ->whereDate('tanggal', Carbon::today())
            ->where('status_validasi_satpam', 'menunggu_validasi')
            ->orderBy('waktu_daftar', 'asc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data antrean pending',
            'data'    => $antrean,
        ]);
    }

    /**
     * Validasi satpam (setujui/tolak) + Estimasi validasi
     */
    public function validasiSatpam(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status_validasi'           => 'required|in:disetujui,ditolak',
            'alasan_penolakan'          => 'nullable|string',
            'estimasi_validasi_satpam'  => 'nullable|integer|min:1|max:120', // Estimasi dalam menit
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->status_validasi_satpam = $request->status_validasi;
        $antrean->satpam_id              = $request->user()->id;

        // Simpan estimasi validasi jika disetujui
        if ($request->status_validasi === 'disetujui') {
            if ($request->estimasi_validasi_satpam) {
                $antrean->estimasi_validasi_satpam = $request->estimasi_validasi_satpam;
                $antrean->waktu_estimasi_validasi_dikirim = Carbon::now();
            }
        }

        if ($request->status_validasi === 'ditolak') {
            $antrean->alasan_penolakan = $request->alasan_penolakan;
            $antrean->status           = 'batal';
        }

        $antrean->save();

        return response()->json([
            'status'  => true,
            'message' => 'Validasi berhasil',
            'data'    => $antrean->load('kendaraan'),
        ]);
    }

    /**
     * Update estimasi waktu pengisian (Operator)
     */
    public function updateEstimasi(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'estimasi_menit' => 'required|integer|min:1|max:480',
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->estimasi_menit   = $request->estimasi_menit;
        $antrean->estimasi_selesai = Carbon::now()->addMinutes($request->estimasi_menit);
        $antrean->save();

        return response()->json([
            'status'  => true,
            'message' => 'Estimasi waktu berhasil diperbarui',
            'data'    => $antrean,
        ]);
    }

    /**
     * Set estimasi waktu pengisian operator (kirim ke supir)
     */
    public function setEstimasiPengisianOperator(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'estimasi_pengisian_operator' => 'required|integer|min:1|max:480', // Estimasi dalam menit
        ]);

        $antrean = Antrean::findOrFail($id);
        
        // Hanya bisa set jika sudah disetujui satpam dan status menunggu/dipanggil
        if ($antrean->status_validasi_satpam !== 'disetujui') {
            return response()->json([
                'status'  => false,
                'message' => 'Antrean belum disetujui satpam',
            ], 422);
        }

        $antrean->estimasi_pengisian_operator = $request->estimasi_pengisian_operator;
        $antrean->waktu_estimasi_pengisian_dikirim = Carbon::now();
        $antrean->operator_id = $request->user()->id;
        $antrean->save();

        return response()->json([
            'status'  => true,
            'message' => 'Estimasi pengisian berhasil dikirim ke supir',
            'data'    => $antrean->load(['kendaraan', 'operator']),
        ]);
    }

    /**
     * Update prioritas antrean (Operator)
     */
    public function updatePrioritas(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'is_prioritas'     => 'required|boolean',
            'alasan_prioritas' => 'required_if:is_prioritas,true|nullable|string',
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->is_prioritas     = $request->is_prioritas;
        $antrean->alasan_prioritas = $request->is_prioritas ? $request->alasan_prioritas : null;
        $antrean->save();

        return response()->json([
            'status'  => true,
            'message' => 'Prioritas antrean berhasil diperbarui',
            'data'    => $antrean,
        ]);
    }

    /**
     * Selesaikan pengisian + input jumlah gas (Operator)
     */
    public function selesaikanPengisian(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'jumlah_gas_liter'  => 'required|numeric|min:0',
            'catatan_pengisian' => 'nullable|string',
        ]);

        $antrean = Antrean::with('kendaraan')->findOrFail($id);

        // Hitung durasi pengisian dalam menit
        $durasi = $antrean->waktu_dipanggil
            ? Carbon::parse($antrean->waktu_dipanggil)->diffInMinutes(Carbon::now())
            : null;

        // Update data antrean
        $antrean->status            = 'selesai';
        $antrean->waktu_selesai     = Carbon::now();
        $antrean->jumlah_gas_liter  = $request->jumlah_gas_liter;
        $antrean->catatan_pengisian = $request->catatan_pengisian;
        $antrean->save();

        // Buat laporan pengisian otomatis
        $laporan = LaporanPengisian::create([
            'antrean_id'       => $antrean->id,
            'kendaraan_id'     => $antrean->kendaraan_id,
            'operator_id'      => $request->user()->id,
            'tanggal'          => Carbon::today(),
            'jumlah_gas_liter' => $request->jumlah_gas_liter,
            'durasi_menit'     => $durasi,
            'estimasi_menit'   => $antrean->estimasi_menit,
            'is_prioritas'     => $antrean->is_prioritas,
            'alasan_prioritas' => $antrean->alasan_prioritas,
            'catatan'          => $request->catatan_pengisian,
            'status'           => 'selesai',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pengisian berhasil diselesaikan dan laporan dibuat',
            'data'    => [
                'antrean'           => $antrean->load(['kendaraan', 'operator']),
                'laporan_pengisian' => $laporan,
            ],
        ]);
    }
}