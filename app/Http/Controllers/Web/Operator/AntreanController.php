<?php

namespace App\Http\Controllers\Web\Operator;

use App\Http\Controllers\Controller;
use App\Models\Antrean;
use App\Models\LaporanPengisian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AntreanController extends Controller
{
    public function index()
    {
        $antrean = Antrean::with(['kendaraan', 'operator'])
            ->whereDate('tanggal', Carbon::today())
            ->where('status_validasi_satpam', 'disetujui')
            ->whereNotIn('status', ['selesai', 'batal'])
            ->orderBy('is_prioritas', 'desc')
            ->orderBy('waktu_daftar', 'asc')
            ->get();

        return view('operator.antrean.index', compact('antrean'));
    }

    public function show($id)
    {
        $antrean = Antrean::with([
            'kendaraan', 'operator', 'laporanPengisian'
        ])->findOrFail($id);

        return view('operator.antrean.show', compact('antrean'));
    }

    public function panggil(Request $request, $id)
    {
        $antrean = Antrean::findOrFail($id);
        $antrean->update([
            'status'          => 'dipanggil',
            'waktu_dipanggil' => Carbon::now(),
            'operator_id'     => auth()->id(),
        ]);

        return back()->with('success', 'Antrean berhasil dipanggil');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dipanggil,dilayani,selesai,batal',
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->status = $request->status;

        if ($request->status === 'dipanggil') {
            $antrean->waktu_dipanggil = Carbon::now();
            $antrean->operator_id     = auth()->id();
        }
        if ($request->status === 'selesai') {
            $antrean->waktu_selesai = Carbon::now();
        }

        $antrean->save();

        return back()->with('success', 'Status berhasil diperbarui');
    }

    public function updatePrioritas(Request $request, $id)
    {
        $request->validate([
            'is_prioritas'     => 'required|boolean',
            'alasan_prioritas' => 'required_if:is_prioritas,1|nullable|string',
            'estimasi_menit'   => 'nullable|integer|min:1',
        ]);

        $antrean = Antrean::findOrFail($id);
        $antrean->update([
            'is_prioritas'     => $request->is_prioritas,
            'alasan_prioritas' => $request->is_prioritas ? $request->alasan_prioritas : null,
            'estimasi_menit'   => $request->estimasi_menit,
            'estimasi_selesai' => $request->estimasi_menit
                ? Carbon::now()->addMinutes($request->estimasi_menit)
                : null,
        ]);

        return back()->with('success', 'Prioritas berhasil diperbarui');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit($id) {}
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}