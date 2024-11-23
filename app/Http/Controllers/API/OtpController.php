<?php

namespace App\Http\Controllers\API;

use App\Models\otp;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function requestOtp(Request $request)
    {
        $tokenFonnte = env('FONNTE_API_KEY');
        $request->validate([
            'nomor' => 'required|digits_between:10,15'
        ]);

        // Hapus OTP sebelumnya untuk nomor ini
        Otp::where('nomor', $request->nomor)->delete();

        // Buat OTP baru
        $otp = rand(100000, 999999);
        $waktuKadaluarsa = Carbon::now()->addMinutes(5); // OTP berlaku 5 menit

        Otp::create([
            'nomor' => $request->nomor,
            'otp' => $otp,
            'waktu_kadaluarsa' => $waktuKadaluarsa
        ]);

        // Kirim OTP via Fonnte API
        $response = Http::withHeaders([
            'Authorization' => $tokenFonnte,
            
        ])->post('https://api.fonnte.com/send', [
            'target' => $request->nomor,
            'message' => 'Your OTP is: ' . $otp,
        ]);

        return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'nomor' => 'required|digits_between:10,15',
            'otp' => 'required|digits:6'
        ]);

        // Cek apakah OTP dan nomor cocok
        $otpRecord = Otp::where('nomor', $request->nomor)->where('otp', $request->otp)->first();
        // return ([$request, $otpRecord]);

        if (!$otpRecord) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Cek apakah OTP sudah kadaluarsa
        if (Carbon::now()->greaterThan($otpRecord->waktu_kadaluarsa)) {
            return response()->json(['message' => 'OTP has expired'], 400);
        }

       
        return response()->json(['message' => 'OTP verified successfully'], 200);
    }
}