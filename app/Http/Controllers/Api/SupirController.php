<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SesiAntrean;
use App\Models\Kendaraan;
use App\Models\Antrean;
use App\Models\InformasiPerusahaan;
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
            'kapasitas_tangki'=> 'required|numeric|min:1',
            'perusahaan_id'   => 'nullable|exists:informasi_perusahaan,id',
            'perusahaan'      => 'nullable|string', // Support manual input perusahaan
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

        // Cek apakah perusahaan VIP
        $perusahaan = null;
        $isVip = false;
        $namaPerusahaan = null;
        
        if ($request->perusahaan_id) {
            // Dari dropdown VIP
            $perusahaan = InformasiPerusahaan::find($request->perusahaan_id);
            $isVip = $perusahaan && $perusahaan->is_prioritas;
            $namaPerusahaan = $perusahaan ? $perusahaan->nama_perusahaan : null;
        } elseif ($request->perusahaan) {
            // Dari manual input - not VIP
            $namaPerusahaan = $request->perusahaan;
            $isVip = false;
        }

        // Simpan data kendaraan
        $kendaraan = Kendaraan::updateOrCreate(
            ['nomor_polisi' => strtoupper($request->nomor_polisi)],
            [
                'user_id'               => $sesi->satpam_id,
                'nama_supir'            => $request->nama_supir,
                'no_hp_supir'           => $request->no_hp_supir,
                'jenis_kendaraan'       => $request->jenis_kendaraan,
                'kapasitas_tangki'      => $request->kapasitas_tangki,
                'perusahaan'            => $namaPerusahaan,
                'is_perusahaan_vip'     => $isVip,
                'status_validasi'       => 'pending',
            ]
        );

        // Generate nomor antrean
        $tanggal = Carbon::today()->toDateString();
        $prioritas = $isVip ? 'tinggi' : 'normal';
        $antrean = Antrean::create([
            'kendaraan_id'           => $kendaraan->id,
            'nomor_antrean'          => Antrean::generateNomor($tanggal),
            'tanggal'                => $tanggal,
            'status'                 => 'menunggu',
            'status_validasi_satpam' => 'menunggu_validasi',
            'prioritas'              => $prioritas,
            'is_prioritas'           => $isVip,
            'waktu_daftar'           => Carbon::now(),
        ]);

        // Hapus QR code karena sudah digunakan (agar tidak ada penumpukan)
        $sesi->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pendaftaran berhasil! Menunggu validasi satpam.',
            'data'    => [
                'nomor_antrean'         => $antrean->nomor_antrean,
                'status_antrean'        => $antrean->status,
                'status_validasi'       => $antrean->status_validasi_satpam,
                'prioritas'             => $prioritas,
                'is_perusahaan_vip'     => $isVip,
                'kendaraan'             => $kendaraan,
                'waktu_daftar'          => $antrean->waktu_daftar,
            ],
        ]);
    }

    // Cek status antrean (tanpa auth)
    public function statusAntrean($kode)
    {
        $antrean = Antrean::with(['kendaraan', 'laporanPengisian.operator'])
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
                'nomor_antrean'                 => $antrean->nomor_antrean,
                'status'                        => $antrean->status,
                'status_validasi_satpam'        => $antrean->status_validasi_satpam,
                'alasan_penolakan'              => $antrean->alasan_penolakan,
                'estimasi_validasi_satpam'      => $antrean->estimasi_validasi_satpam,
                'estimasi_pengisian_operator'   => $antrean->estimasi_pengisian_operator,
                'waktu_estimasi_validasi_dikirim' => $antrean->waktu_estimasi_validasi_dikirim,
                'waktu_estimasi_pengisian_dikirim' => $antrean->waktu_estimasi_pengisian_dikirim,
                'kendaraan'                     => $antrean->kendaraan,
                'waktu_daftar'                  => $antrean->waktu_daftar,
                'waktu_dipanggil'               => $antrean->waktu_dipanggil,
                'waktu_selesai'                 => $antrean->waktu_selesai,
                'laporan_pengisian'             => $antrean->laporanPengisian,
            ],
        ]);
    }

    // Daftar perusahaan VIP (tanpa auth)
    public function daftarPerusahaan()
    {
        $perusahaan = InformasiPerusahaan::where('status', 'aktif')
            ->select('id', 'nama_perusahaan', 'is_prioritas')
            ->orderBy('is_prioritas', 'desc')
            ->orderBy('nama_perusahaan', 'asc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar perusahaan',
            'data'    => $perusahaan,
        ]);
    }

    // Jenis kendaraan yang tersedia (tanpa auth)
    public function jenisKendaraan()
    {
        $jenis = [
            ['id' => '5fit_kecil', 'label' => '5fit (Kecil)', 'kapasitas' => 5],
            ['id' => '10fit_sedang', 'label' => '10fit (Sedang)', 'kapasitas' => 10],
            ['id' => '20fit_besar', 'label' => '20fit (Besar)', 'kapasitas' => 20],
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Jenis kendaraan tersedia',
            'data'    => $jenis,
        ]);
    }
}