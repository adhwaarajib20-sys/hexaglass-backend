<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Email atau password salah', 401);
        }

        if ($user->status !== 'aktif') {
            return $this->error('Akun Anda tidak aktif', 403);
        }

        $token = $user->createToken('hexaglass-token')->plainTextToken;
        $role  = $user->getRoleNames()->first();

        return $this->success([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $role,
            ],
        ], 'Login berhasil');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logout berhasil');
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->role = $user->getRoleNames()->first();
        return $this->success($user);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'no_hp'    => 'nullable|string',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'status'   => 'aktif',
        ]);

        $user->assignRole('supir');

        $token = $user->createToken('hexaglass-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => 'supir',
            ],
        ], 'Registrasi berhasil', 201);
    }
}