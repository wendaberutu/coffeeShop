<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
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
}
