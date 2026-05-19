<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SesiAntrean;
use App\Models\Kendaraan;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupirController extends Controller
{
    // Validasi barcode dari satpam (tanpa auth)
    public function validasiBarcode(Request $request)
    {
        $request->validate([
            'kode_barcode' => 'required|string',
        ]);

        $sesi = SesiAntrean::where('qr_token', $request->kode_barcode)
            ->orWhere('qr_code', $request->kode_barcode)
            ->first();

        if (!$sesi) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode barcode tidak valid',
            ], 404);
        }

        if (!$sesi->isValid()) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode barcode sudah kadaluarsa atau sudah digunakan',
            ], 422);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Kode barcode valid',
            'data'    => [
                'qr_token'   => $sesi->qr_token,
                'expired_at' => $sesi->expired_at,
            ],
        ]);
    }

    // Daftar antrean (tanpa auth)
    public function daftarAntrean(Request $request)
    {
        $request->validate([
            'kode_barcode'    => 'required|string',
            'nama_supir'      => 'required|string',
            'no_hp_supir'     => 'required|string',
            'nomor_polisi'    => 'required|string',
            'jenis_kendaraan' => 'required|string',
            'kapasitas_tangki'=> 'required|string',
            'perusahaan'      => 'nullable|string',
        ]);

        // Cari sesi
        $sesi = SesiAntrean::where('qr_token', $request->kode_barcode)
            ->orWhere('qr_code', $request->kode_barcode)
            ->first();

        if (!$sesi || !$sesi->isValid()) {
            return response()->json([
                'status'  => false,
                'message' => 'Kode barcode tidak valid atau sudah kadaluarsa',
            ], 422);
        }

        // Simpan data kendaraan
        $kendaraan = Kendaraan::updateOrCreate(
            ['nomor_polisi' => strtoupper($request->nomor_polisi)],
            [
                'user_id'          => $sesi->satpam_id,
                'nama_supir'       => $request->nama_supir,
                'no_hp_supir'      => $request->no_hp_supir,
                'jenis_kendaraan'  => $request->jenis_kendaraan,
                'kapasitas_tangki' => $request->kapasitas_tangki,
                'perusahaan'       => $request->perusahaan,
                'status_validasi'  => 'pending',
            ]
        );

        // Generate nomor antrean
        $tanggal = Carbon::today()->toDateString();
        $antrean = Antrean::create([
            'kendaraan_id'           => $kendaraan->id,
            'nomor_antrean'          => Antrean::generateNomor($tanggal),
            'tanggal'                => $tanggal,
            'status'                 => 'menunggu',
            'status_validasi_satpam' => 'menunggu_validasi',
            'prioritas'              => 'normal',
            'waktu_daftar'           => Carbon::now(),
        ]);

        // Update sesi
        $sesi->update([
            'status'       => 'digunakan',
            'kendaraan_id' => $kendaraan->id,
            'antrean_id'   => $antrean->id,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pendaftaran berhasil! Menunggu validasi satpam.',
            'data'    => [
                'nomor_antrean'  => $antrean->nomor_antrean,
                'status_antrean' => $antrean->status,
                'status_validasi'=> $antrean->status_validasi_satpam,
                'kendaraan'      => $kendaraan,
                'waktu_daftar'   => $antrean->waktu_daftar,
            ],
        ]);
    }

    // Cek status antrean (tanpa auth)
    public function statusAntrean($kode)
    {
        $antrean = Antrean::with(['kendaraan', 'operator'])
            ->whereRaw('UPPER(nomor_antrean) = UPPER(?)', [trim($kode)])
            ->latest()
            ->first();

        if (!$antrean) {
            return response()->json([
                'status'  => false,
                'message' => 'Nomor antrean tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Status antrean',
            'data'    => [
                'nomor_antrean'   => $antrean->nomor_antrean,
                'status'          => $antrean->status,
                'status_validasi' => $antrean->status_validasi_satpam,
                'alasan_penolakan'=> $antrean->alasan_penolakan,
                'kendaraan'       => $antrean->kendaraan,
                'waktu_daftar'    => $antrean->waktu_daftar,
                'waktu_dipanggil' => $antrean->waktu_dipanggil,
            ],
        ]);
    }
}