<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InformasiPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InformasiPerusahaanController extends Controller
{
    /**
     * List semua perusahaan
     */
    public function index(Request $request): JsonResponse
    {
        $query = InformasiPerusahaan::with('createdBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_prioritas') && $request->is_prioritas !== null) {
            $query->where('is_prioritas', $request->is_prioritas);
        }

        $data = $query->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data informasi perusahaan',
            'data'    => $data,
        ]);
    }

    /**
     * Detail perusahaan
     */
    public function show(int $id): JsonResponse
    {
        $data = InformasiPerusahaan::with('createdBy')->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Detail informasi perusahaan',
            'data'    => $data,
        ]);
    }

    /**
     * Tambah perusahaan (Admin)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_perusahaan'          => 'required|string',
            'is_prioritas'             => 'boolean',
            'volume'                   => 'nullable|numeric',
            'rencana_pengisian_harian' => 'nullable|numeric',
            'keterangan'               => 'nullable|string',
            'status'                   => 'in:aktif,nonaktif',
        ]);

        $data = InformasiPerusahaan::create([
            'nama_perusahaan'          => $request->nama_perusahaan,
            'is_prioritas'             => $request->is_prioritas ?? false,
            'volume'                   => $request->volume,
            'rencana_pengisian_harian' => $request->rencana_pengisian_harian,
            'keterangan'               => $request->keterangan,
            'status'                   => $request->status ?? 'aktif',
            'created_by'               => $request->user()->id,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Informasi perusahaan berhasil ditambahkan',
            'data'    => $data,
        ], 201);
    }

    /**
     * Update perusahaan (Admin)
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'nama_perusahaan'          => 'sometimes|string',
            'is_prioritas'             => 'sometimes|boolean',
            'volume'                   => 'nullable|numeric',
            'rencana_pengisian_harian' => 'nullable|numeric',
            'keterangan'               => 'nullable|string',
            'status'                   => 'sometimes|in:aktif,nonaktif',
        ]);

        $data = InformasiPerusahaan::findOrFail($id);
        $data->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Informasi perusahaan berhasil diperbarui',
            'data'    => $data,
        ]);
    }

    /**
     * Hapus perusahaan (Admin)
     */
    public function destroy(int $id): JsonResponse
    {
        $data = InformasiPerusahaan::findOrFail($id);
        $data->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Informasi perusahaan berhasil dihapus',
            'data'    => null,
        ]);
    }
}