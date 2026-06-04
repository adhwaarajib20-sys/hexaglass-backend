<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exports\AntreanExport;
use App\Http\Controllers\Controller;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AntreanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        $query = Antrean::with(['kendaraan', 'operator'])
            ->whereDate('tanggal', $tanggal);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $antrean = $query->latest('waktu_daftar')->paginate(15);

        return view('admin.antrean.index', compact('antrean'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $antrean = Antrean::with(['kendaraan', 'operator', 'laporanPengisian'])->findOrFail($id);

        return view('admin.antrean.show', compact('antrean'));
    }

    public function export(Request $request)
    {
        $dari = $request->input('dari_tanggal');
        $sampai = $request->input('sampai_tanggal');

        // Validasi harus ada kedua tanggal
        if (!$dari || !$sampai) {
            return back()->with('error', 'Pilih tanggal mulai dan tanggal akhir terlebih dahulu');
        }

        if ($dari > $sampai) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        $filename = 'rekap-antrean-' . $dari . '-sd-' . $sampai . '.xlsx';

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
