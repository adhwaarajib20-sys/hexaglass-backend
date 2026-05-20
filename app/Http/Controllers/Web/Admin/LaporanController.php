<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with(['pelapor', 'verifikator', 'foto']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->klasifikasi) {
            $query->where('klasifikasi', $request->klasifikasi);
        }
        if ($request->dari_tanggal) {
            $query->whereDate('tanggal_kejadian', '>=', $request->dari_tanggal);
        }
        if ($request->sampai_tanggal) {
            $query->whereDate('tanggal_kejadian', '<=', $request->sampai_tanggal);
        }

        $laporan = $query->latest()->paginate(15);

        return view('admin.laporan.index', compact('laporan'));
    }

    public function show($id)
    {
        $laporan = Laporan::with(['pelapor', 'verifikator', 'foto'])->findOrFail($id);

        $laporan->foto->each(function ($foto) {
            $foto->url = Storage::url($foto->path_foto);
        });

        return view('admin.laporan.show', compact('laporan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:diverifikasi,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status'         => $request->status,
            'verifikator_id' => auth()->id(),
            'catatan_admin'  => $request->catatan_admin,
        ]);

        return back()->with('success', 'Laporan berhasil ' . ($request->status === 'diverifikasi' ? 'diverifikasi' : 'ditolak'));
    }

    public function export(Request $request)
    {
        $filename = 'laporan-ketidaksesuaian-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new LaporanExport($request->dari_tanggal, $request->sampai_tanggal),
            $filename
        );
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}