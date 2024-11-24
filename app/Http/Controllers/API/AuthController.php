<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Member;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        // Validasi input login
        $validator = Validator::make($request->all(), [
            'no_whatshap' => 'required|string|digits_between:10,15',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('no_whatshap', 'password');

        // Cek autentikasi
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor WhatsApp atau password salah'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Return a token response with user details
     */
    protected function respondWithToken($token)
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]
        ]);
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'umur' => 'required|integer|min:1|max:150',
            'tanggal_lahir' => 'required|date',
            'no_whatshap' => 'required|string|digits_between:10,15|unique:users',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string|max:500',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Log data input untuk debugging
        Log::info('Validated Data:', $request->all());

        // Buat user baru
        $user = User::create([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_whatshap' => $request->no_whatshap,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Gunakan Hash::make untuk keamanan
            'alamat' => $request->alamat,
            'role' => 'user' // Default role untuk pengguna baru
        ]);

        // Buat data member terkait
        Member::create([
            'user_id' => $user->id,
            'nama_member' => $request->nama,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_whatshap' => $request->no_whatshap,
            'umur' => $request->umur,
            'tanggal_lahir' => $request->tanggal_lahir
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil didaftarkan dan data member dibuat',
            'data' => $user->only(['id', 'nama', 'email', 'role', 'umur', 'tanggal_lahir', 'no_whatshap']),
        ], 201);
    }

    /**
     * Handle user logout
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get authenticated user details
     */
    public function me()
    {
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }
}
