<?php

namespace App\Http\Controllers\Web\Operator;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::where('pelapor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('operator.laporan.index', compact('laporan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operator.laporan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        $existing = Laporan::where('pelapor_id', Auth::id())
            ->where('tanggal_kejadian', $request->tanggal_kejadian)
            ->where('lokasi', $request->lokasi)
            ->where('klasifikasi', $request->klasifikasi)
            ->where('deskripsi', $request->deskripsi)
            ->first();

        if ($existing) {
            return back()
                ->withInput()
                ->with('warning', 'Laporan serupa sudah ada. Periksa riwayat laporan Anda sebelum mengirim kembali.');
        }

        $laporan = Laporan::create([
            'pelapor_id'       => Auth::id(),
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

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('laporan/foto', 'public');
                LaporanFoto::create([
                    'laporan_id'      => $laporan->id,
                    'path_foto'       => $path,
                    'keterangan_foto' => null,
                ]);
            }
        }

        return redirect()->route('operator.laporan.index')
            ->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::where('pelapor_id', Auth::id())
            ->with('foto')
            ->findOrFail($id);

        return view('operator.laporan.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(404);
    }
}
