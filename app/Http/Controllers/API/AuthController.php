<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Member;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['no_whatshap', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Nomor WhatsApp atau password salah'], 401);
        }

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
        Log::info('Request Data:', $request->all());

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:1|max:150',
            'tanggal_lahir' => 'required|date',
            'no_whatshap' => 'required|string|digits_between:10,15|unique:users',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        Log::info('Validated Data:', $validatedData);

        // Create user
        $user = User::create([
            'nama' => $validatedData['nama'],
            'umur' => $validatedData['umur'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'no_whatshap' => $validatedData['no_whatshap'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'alamat' => $validatedData['alamat'],
            'role' => 'user'
        ]);

        // Create member
        Member::create([
            'user_id' => $user->id,
            'nama_member' => $validatedData['nama'],
            'alamat' => $validatedData['alamat'],
            'email' => $validatedData['email'],
            'no_whatshap' => $validatedData['no_whatshap'],
            'umur' => $validatedData['umur'],
            'tanggal_lahir' => $validatedData['tanggal_lahir']
        ]);

        return response()->json([
            'message' => 'User berhasil didaftarkan dan data member dibuat',
            'user' => $user->only(['nama', 'umur', 'tanggal_lahir', 'no_whatshap', 'role', 'email']),
        ], 201);
    }
}
