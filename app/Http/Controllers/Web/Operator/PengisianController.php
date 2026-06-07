<?php

namespace App\Http\Controllers\Web\Operator;

use App\Http\Controllers\Controller;
use App\Models\LaporanPengisian;
use App\Models\Antrean;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengisianController extends Controller
{
    public function index()
    {
        $pengisian = LaporanPengisian::with(['antrean.kendaraan', 'operator'])
            ->where('operator_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('operator.pengisian.index', compact('pengisian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'antrean_id'       => 'required|exists:antrean,id',
            'jumlah_gas_liter' => 'required|numeric|min:0',
            'estimasi_menit'   => 'nullable|integer',
            'catatan'          => 'nullable|string',
        ]);

        $antrean = Antrean::findOrFail($request->antrean_id);

        $durasi = $antrean->waktu_dipanggil
            ? Carbon::parse($antrean->waktu_dipanggil)->diffInMinutes(Carbon::now())
            : null;

        // Update antrean
        $antrean->update([
            'status'            => 'selesai',
            'waktu_selesai'     => Carbon::now(),
            'jumlah_gas_liter'  => $request->jumlah_gas_liter,
            'catatan_pengisian' => $request->catatan,
            'estimasi_menit'    => $request->estimasi_menit ?? $antrean->estimasi_menit,
        ]);

        // Buat laporan pengisian
        LaporanPengisian::create([
            'antrean_id'       => $antrean->id,
            'kendaraan_id'     => $antrean->kendaraan_id,
            'operator_id'      => auth()->id(),
            'tanggal'          => Carbon::today(),
            'jumlah_gas_liter' => $request->jumlah_gas_liter,
            'durasi_menit'     => $durasi,
            'estimasi_menit'   => $request->estimasi_menit ?? $antrean->estimasi_menit,
            'is_prioritas'     => $antrean->is_prioritas,
            'alasan_prioritas' => $antrean->alasan_prioritas,
            'catatan'          => $request->catatan,
            'status'           => 'selesai',
        ]);

        return redirect()->route('operator.antrean.index')
            ->with('success', 'Pengisian berhasil disimpan! ' . number_format($request->jumlah_gas_liter, 0, ',', '.') . ' m³');
    }

    public function show($id)
    {
        $pengisian = LaporanPengisian::with(['antrean.kendaraan', 'operator'])->findOrFail($id);
        return view('operator.pengisian.show', compact('pengisian'));
    }

    public function create() {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}