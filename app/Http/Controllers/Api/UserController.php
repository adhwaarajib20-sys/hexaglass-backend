<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List semua user
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter by role
        if ($request->role) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data user berhasil diambil',
            'data'    => $users,
        ]);
    }

    // Detail user
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response()->json([
            'status'  => true,
            'message' => 'Detail user',
            'data'    => $user,
        ]);
    }

    // Tambah user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'no_hp'    => 'nullable|string',
            'role'     => 'required|in:admin,operator,satpam,supir',
            'status'   => 'in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'status'   => $request->status ?? 'aktif',
            'role'     => $request->role,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $user->load('roles'),
        ], 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'sometimes|string',
            'email'  => 'sometimes|email|unique:users,email,' . $id,
            'no_hp'  => 'nullable|string',
            'role'   => 'sometimes|in:admin,operator,satpam,supir',
            'status' => 'sometimes|in:aktif,nonaktif',
        ]);

        $user->update([
            'name'   => $request->name   ?? $user->name,
            'email'  => $request->email  ?? $user->email,
            'no_hp'  => $request->no_hp  ?? $user->no_hp,
            'status' => $request->status ?? $user->status,
            'role'   => $request->role   ?? $user->role,
        ]);

        // Update role jika ada perubahan
        if ($request->role) {
            $user->syncRoles([$request->role]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil diupdate',
            'data'    => $user->load('roles'),
        ]);
    }

    // Reset password user
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil direset',
            'data'    => null,
        ]);
    }

    // Nonaktifkan user
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Status user berhasil diubah menjadi ' . $user->status,
            'data'    => $user,
        ]);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Cegah hapus diri sendiri
        if ($user->id === auth()->id()) {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak dapat menghapus akun sendiri',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil dihapus',
            'data'    => null,
        ]);
    }
}