<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BroadcastController extends Controller
{
    public function sendBroadcast(Request $request)
    {
        // Validasi input
        $request->validate([
            'message' => 'required|string',  // Memastikan message ada
        ]);

        // Ambil data nomor WhatsApp dari database
        $users = DB::table('users')->select('no_whatshap', 'nama')->get();

        // Format data untuk API Fonnte
        $messages = [];
        foreach ($users as $user) {
            $messages[] = [
                'target' => (string) $user->no_whatshap, // Pastikan target dalam format string
                'message' => 'Halo ' . $user->nama . '! \n\n' . $request->message, // Pesan sebagai string
                'delay' => '3', // Jeda 1 detik untuk setiap pengiriman (gunakan string)
            ];
        }

        // return $users;
        // die();
        // dd($messages);

        // API Fonnte
        $apiUrl = 'https://api.fonnte.com/send';
        $token = env('FONNTE_API_KEY');
        // dd($token);

        // Siapkan request menggunakan curl
        $curl = curl_init();
        $postData = json_encode($messages);
        // dd($postData);


        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'data' => $postData,
                'countryCode' => '62'

            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: $token",
                "Content-Type: application/json" // Pastikan format JSON
            ],
        ]);


        // Eksekusi request dan tangkap respons
        $response = curl_exec($curl);
        // dd($postData, $response);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            $errorMsg = curl_error($curl);
            curl_close($curl);
            return response()->json(['status' => 'error', 'message' => $errorMsg], 500);
        }

        curl_close($curl);

        // Debugging respons API
        return response()->json([
            'status' => 'success',
            'http_code' => $httpCode,
            'response' => json_decode($response, true)
        ], 200);
    }

 
    public function broadcastByAge(Request $request)
    {
        // Validasi input
        $request->validate([
            'awal' => 'required|integer|min:0',  // Rentang awal umur (integer, minimal 0)
            'akhir' => 'required|integer|gte:awal', // Rentang akhir umur (integer, harus >= awal)
            'message' => 'required|string',  // Pesan wajib ada
        ]);

        // Ambil input dari request
        $awal = $request->input('awal');
        $akhir = $request->input('akhir');
        $message = $request->input('message');

        // Ambil semua nomor WhatsApp pengguna berdasarkan rentang umur
        $users = User::whereBetween('umur', [$awal, $akhir])->get();

        // Format pesan untuk API
        $messages = [];
        foreach ($users as $user) {
            $messages[] = [
                'target' => (string) $user->no_whatshap, // Pastikan target dalam format string
                'message' => 'Halo ' . $user->nama . '!\n\n' . 'Kami menghadirkan promo spesial untuk Anda yang berusia ' . $awal . ' hingga ' . $akhir . ' tahun.' . $message, // Pesan yang dikirimkan
                'delay' => '3', // Jeda 1 detik untuk setiap pengiriman
            ];
        }

        // Jika tidak ada pengguna yang sesuai kriteria
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada pengguna dalam rentang umur yang ditentukan.',
            ], 404);
        }

        // Pengecekan
        // return [$awal, $akhir,
        //     $message,
        //     $users,
        //     $messages,
        //     $request->error
        // ];


        // Kirim request POST menggunakan HTTP Client
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_API_KEY'),  // Ganti dengan token asli Anda
        ])->post('https://api.fonnte.com/send', [
            'data' => json_encode($messages),  // Kirim data sebagai JSON
        ]);

        // Periksa apakah ada error dalam response
        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim pesan broadcast.',
                'response' => $response->body(),
            ], $response->status());
        }

        // Tampilkan respons dari API
        return response()->json([
            'status' => 'success',
            'message' => 'Pesan berhasil dikirim ke pengguna dalam rentang umur yang ditentukan.',
            'response' => json_decode($response->body(), true), // Untuk melihat detail respons
        ], 200);
    }
}
