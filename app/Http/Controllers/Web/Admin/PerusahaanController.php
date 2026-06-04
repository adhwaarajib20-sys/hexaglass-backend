<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\InformasiPerusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index()
    {
        $perusahaan = InformasiPerusahaan::with('createdBy')->latest()->paginate(15);
        return view('admin.perusahaan.index', compact('perusahaan'));
    }

    public function create()
    {
        return view('admin.perusahaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan'          => 'required|string',
            'is_prioritas'             => 'boolean',
            'volume'                   => 'nullable|numeric',
            'rencana_pengisian_harian' => 'nullable|numeric',
            'keterangan'               => 'nullable|string',
            'status'                   => 'in:aktif,nonaktif',
        ]);

        InformasiPerusahaan::create([
            ...$request->all(),
            'is_prioritas' => $request->boolean('is_prioritas'),
            'created_by'   => auth()->id(),
        ]);

        return redirect()->route('admin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $perusahaan = InformasiPerusahaan::findOrFail($id);
        return view('admin.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perusahaan'          => 'required|string',
            'volume'                   => 'nullable|numeric',
            'rencana_pengisian_harian' => 'nullable|numeric',
            'keterangan'               => 'nullable|string',
            'status'                   => 'in:aktif,nonaktif',
        ]);

        $perusahaan = InformasiPerusahaan::findOrFail($id);
        $perusahaan->update([
            ...$request->all(),
            'is_prioritas' => $request->boolean('is_prioritas'),
        ]);

        return redirect()->route('admin.perusahaan.index')
            ->with('success', 'Perusahaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        InformasiPerusahaan::findOrFail($id)->delete();
        return back()->with('success', 'Perusahaan berhasil dihapus');
    }

    public function show($id) {}
}