<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    // List semua laporan
    public function index(Request $request)
    {
        $query = Laporan::with(['pelapor', 'verifikator', 'foto']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by klasifikasi
        if ($request->klasifikasi) {
            $query->where('klasifikasi', $request->klasifikasi);
        }

        // Filter by tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal_kejadian', $request->tanggal);
        }

        // Filter hanya laporan milik user (untuk supir)
        if ($request->milik_saya) {
            $query->where('pelapor_id', $request->user()->id);
        }

        $laporan = $query->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data laporan berhasil diambil',
            'data'    => $laporan,
        ]);
    }

    // Buat laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor'     => 'required|string',
            'perusahaan'       => 'nullable|string',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian'   => 'required',
            'lokasi'           => 'required|string',
            'klasifikasi'      => 'required|in:keselamatan,lingkungan,kualitas,prosedur,lainnya',
            'deskripsi'        => 'required|string',
            'rekomendasi'      => 'nullable|string',
            'foto.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $laporan = Laporan::create([
            'pelapor_id'       => $request->user()?->id, // Null jika public request
            'nama_pelapor'     => $request->nama_pelapor,
            'perusahaan'       => $request->perusahaan,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'waktu_kejadian'   => $request->waktu_kejadian,
            'lokasi'           => $request->lokasi,
            'klasifikasi'      => $request->klasifikasi,
            'deskripsi'        => $request->deskripsi,
            'rekomendasi'      => $request->rekomendasi,
            'status'           => 'terkirim',
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('laporan', 'public');
                LaporanFoto::create([
                    'laporan_id'      => $laporan->id,
                    'path_foto'       => $path,
                    'keterangan_foto' => null,
                ]);
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil dibuat',
            'data'    => $laporan->load('foto'),
        ], 201);
    }

    // Detail laporan
    public function show($id)
    {
        $laporan = Laporan::with(['pelapor', 'verifikator', 'foto'])->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Detail laporan',
            'data'    => $laporan,
        ]);
    }

    // Verifikasi laporan (oleh Admin)
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status'          => $request->status,
            'verifikator_id'  => $request->user()->id,
            'catatan_admin'   => $request->catatan_admin,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil diverifikasi',
            'data'    => $laporan->load(['pelapor', 'verifikator', 'foto']),
        ]);
    }

    // Hapus laporan (soft delete)
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil dihapus',
            'data'    => null,
        ]);
    }
}