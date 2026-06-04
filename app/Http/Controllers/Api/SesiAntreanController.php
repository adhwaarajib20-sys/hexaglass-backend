<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SesiAntrean;
use App\Models\Kendaraan;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SesiAntreanController extends Controller
{
    // SATPAM: Generate QR Code baru
    public function generateQR(Request $request)
    {
        // Expired QR lama yang masih aktif milik satpam ini
        SesiAntrean::where('satpam_id', $request->user()->id)
            ->where('status', 'aktif')
            ->update(['status' => 'kadaluarsa']);

        $token  = Str::random(64);
        $qrCode = 'QR-' . strtoupper(Str::random(12));

        $sesi = SesiAntrean::create([
            'satpam_id'  => $request->user()->id,
            'qr_code'    => $qrCode,
            'qr_token'   => $token,
            'status'     => 'aktif',
            'expired_at' => Carbon::now()->addMinutes(10),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'QR Code berhasil di-generate',
            'data'    => [
                'qr_code'    => $sesi->qr_code,
                'qr_token'   => $sesi->qr_token,
                'expired_at' => $sesi->expired_at,
                'berlaku'    => '10 menit',
            ],
        ]);
    }

    // SUPIR: Scan QR → Validasi token → Isi data kendaraan
    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_token'         => 'required|string',
            'nama_supir'       => 'required|string',
            'no_hp_supir'      => 'required|string',
            'nomor_polisi'     => 'required|string',
            'jenis_kendaraan'  => 'required|string',
            'kapasitas_tangki' => 'required|string',
            'perusahaan'       => 'nullable|string',
        ]);

        // Cari sesi berdasarkan token
        $sesi = SesiAntrean::where('qr_token', $request->qr_token)->first();

        // Validasi QR
        if (!$sesi) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code tidak valid',
            ], 404);
        }

        if (!$sesi->isValid()) {
            return response()->json([
                'status'  => false,
                'message' => 'QR Code sudah kadaluarsa atau sudah digunakan',
            ], 422);
        }

        // Simpan data kendaraan
        $kendaraan = Kendaraan::updateOrCreate(
            ['nomor_polisi' => $request->nomor_polisi],
            [
                'user_id'          => $request->user()->id,
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
            'kendaraan_id'  => $kendaraan->id,
            'nomor_antrean' => Antrean::generateNomor($tanggal),
            'tanggal'       => $tanggal,
            'status'        => 'menunggu',
            'prioritas'     => 'normal',
            'waktu_daftar'  => Carbon::now(),
        ]);

        // Hapus QR code karena sudah digunakan (agar tidak ada penumpukan)
        $sesi->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil disimpan, nomor antrean telah dibuat',
            'data'    => [
                'kendaraan'      => $kendaraan,
                'nomor_antrean'  => $antrean->nomor_antrean,
                'status_antrean' => $antrean->status,
                'waktu_daftar'   => $antrean->waktu_daftar,
            ],
        ]);
    }

    // SATPAM: Cek status QR aktif miliknya
    public function statusQR(Request $request)
    {
        $sesi = SesiAntrean::where('satpam_id', $request->user()->id)
            ->where('status', 'aktif')
            ->latest()
            ->first();

        if (!$sesi) {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada QR aktif',
                'data'    => null,
            ]);
        }

        // Auto expire jika sudah lewat waktu
        if ($sesi->expired_at->isPast()) {
            $sesi->update(['status' => 'kadaluarsa']);
            return response()->json([
                'status'  => false,
                'message' => 'QR Code sudah kadaluarsa',
                'data'    => null,
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'QR masih aktif',
            'data'    => [
                'qr_code'    => $sesi->qr_code,
                'qr_token'   => $sesi->qr_token,
                'expired_at' => $sesi->expired_at,
                'sisa_waktu' => Carbon::now()->diffInMinutes($sesi->expired_at) . ' menit',
            ],
        ]);
    }
}