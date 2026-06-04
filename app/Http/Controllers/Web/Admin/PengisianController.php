<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengisian;
use App\Exports\AntreanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PengisianController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanPengisian::with(['antrean.kendaraan', 'operator']);

        if ($request->dari_tanggal) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->sampai_tanggal) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }
        if ($request->operator_id) {
            $query->where('operator_id', $request->operator_id);
        }

        $pengisian  = $query->latest()->paginate(15);
        $totalLiter = $query->sum('jumlah_gas_liter');
        $operators  = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'operator'))->get();

        return view('admin.pengisian.index', compact('pengisian', 'totalLiter', 'operators'));
    }

    public function show($id)
    {
        $pengisian = LaporanPengisian::with(['antrean.kendaraan', 'operator'])->findOrFail($id);
        return view('admin.pengisian.show', compact('pengisian'));
    }

    public function export(Request $request)
    {
        $dari = $request->dari_tanggal;
        $sampai = $request->sampai_tanggal;

        // Validasi harus ada kedua tanggal
        if (!$dari || !$sampai) {
            return back()->with('error', 'Pilih tanggal mulai dan tanggal akhir terlebih dahulu');
        }

        if ($dari > $sampai) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        $filename = 'rekap-pengisian-' . $dari . '-sd-' . $sampai . '.xlsx';
        return Excel::download(
            new AntreanExport($dari, $sampai),
            $filename
        );
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}