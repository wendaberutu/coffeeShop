<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * Laporan Pendapatan
     */
    public function index()
    {
        // Ambil data dari order_details dan gabungkan dengan products
        $report = DB::table('order_details')
            ->join('products', 'products.id', '=', 'order_details.id_produk') // Gabungkan dengan tabel products
            ->select(
                'products.nama_produk',
                DB::raw('SUM(order_details.jumlah) as total_terjual'), // Total jumlah yang terjual
                DB::raw('products.harga as harga_produk'), // Harga per produk
                DB::raw('SUM(order_details.jumlah * products.harga) as pendapatan') // Total pendapatan
            )
            ->groupBy('products.id', 'products.nama_produk', 'products.harga') // Kelompokkan berdasarkan produk
            ->orderBy('pendapatan', 'DESC') // Urutkan berdasarkan pendapatan tertinggi
            ->get();

        // Kembalikan laporan dalam bentuk JSON
        return response()->json([
            'data' => $report,
            'total_pendapatan' => $report->sum('pendapatan') // Hitung total pendapatan keseluruhan
        ]);
    }
}
