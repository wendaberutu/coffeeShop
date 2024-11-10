<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['no_whatshap', 'password']);

        // Mengembalikan error jika autentikasi gagal
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Nomor atau password salah'], 401);
        }

        // Mengembalikan token jika autentikasi berhasil
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'umur' => 'required|integer',
            'tanggal_lahir' => 'required|date',
            'no_whatshap' => 'required|digits_between:10,15|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User([
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
            'umur' => $validatedData['umur'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'no_whatshap' => $validatedData['no_whatshap'],
            'password' => bcrypt($validatedData['password']),
            'role' => 'user'  // Default role
        ]);

        $user->save();

        return response()->json(['message' => 'User berhasil didaftarkan'], 201);
    }
}
