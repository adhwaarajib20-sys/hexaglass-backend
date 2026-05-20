<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanFoto;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // List semua laporan
    public function index(Request $request)
    {
        $query = Laporan::with(['pelapor', 'verifikator', 'foto']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->klasifikasi) {
            $query->where('klasifikasi', $request->klasifikasi);
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal_kejadian', $request->tanggal);
        }

        if ($request->milik_saya) {
            $query->where('pelapor_id', $request->user()->id);
        }

        $laporan = $query->latest()->get();

        // Tambahkan URL foto
        $laporan->each(function ($item) {
            $item->foto->each(function ($foto) {
                $foto->url = Storage::url($foto->path_foto);
            });
        });

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
            'foto.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $laporan = Laporan::create([
            'pelapor_id'       => optional($request->user())->id,
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
                $path = $foto->store('laporan/foto', 'public');
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

        $laporan->foto->each(function ($foto) {
            $foto->url = Storage::url($foto->path_foto);
        });

        return response()->json([
            'status'  => true,
            'message' => 'Detail laporan',
            'data'    => $laporan,
        ]);
    }

    // Upload foto tambahan ke laporan yang sudah ada
    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto.*'           => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan_foto'  => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);

        $uploadedFotos = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $foto) {
                $path = $foto->store('laporan/foto', 'public');
                $laporanFoto = LaporanFoto::create([
                    'laporan_id'      => $laporan->id,
                    'path_foto'       => $path,
                    'keterangan_foto' => $request->keterangan_foto,
                ]);
                $laporanFoto->url = Storage::url($path);
                $uploadedFotos[] = $laporanFoto;
            }
        }

        return response()->json([
            'status'  => true,
            'message' => count($uploadedFotos) . ' foto berhasil diupload',
            'data'    => $uploadedFotos,
        ]);
    }

    // Hapus foto
    public function hapusFoto($laporanId, $fotoId)
    {
        $foto = LaporanFoto::where('laporan_id', $laporanId)
            ->where('id', $fotoId)
            ->firstOrFail();

        Storage::disk('public')->delete($foto->path_foto);
        $foto->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Foto berhasil dihapus',
            'data'    => null,
        ]);
    }

    // Verifikasi laporan (Admin)
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status'         => $request->status,
            'verifikator_id' => $request->user()->id,
            'catatan_admin'  => $request->catatan_admin,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil diverifikasi',
            'data'    => $laporan->load(['pelapor', 'verifikator', 'foto']),
        ]);
    }

    // Hapus laporan
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

    // Export Excel (Admin only)
    public function exportExcel(Request $request)
    {
        $dari    = $request->dari_tanggal;
        $sampai  = $request->sampai_tanggal;
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
}