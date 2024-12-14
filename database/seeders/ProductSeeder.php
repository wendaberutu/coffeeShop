<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Data produk minuman
        $minuman = [
            [
                'kategori' => 'minuman',
                'nama_produk' => 'Americano',
                'deskripsi' => 'Americano dengan rasa kopi yang kuat dan segar.',
                'gambar' => 'uploads/gambar1.jpg',
                'harga' => 25000,
                'diskon' => 0, // Tidak ada diskon
            ],
            [
                'kategori' => 'minuman',
                'nama_produk' => 'Espresso',
                'deskripsi' => 'Espresso murni dengan rasa pahit yang tajam.',
                'gambar' => 'uploads/gambar2.jpg',
                'harga' => 20000,
                'diskon' => 0, // Tidak ada diskon
            ],
            [
                'kategori' => 'minuman',
                'nama_produk' => 'Macchiato',
                'deskripsi' => 'Macchiato dengan campuran susu dan espresso.',
                'gambar' => 'uploads/gambar3.jpg',
                'harga' => 22000,
                'diskon' => 10, // Produk dengan diskon
            ],
        ];

        // Data produk makanan
        $makanan = [
            [
                'kategori' => 'makanan',
                'nama_produk' => 'Chips',
                'deskripsi' => 'Cemilan chips renyah dan gurih.',
                'gambar' => 'uploads/gambar4.jpg',
                'harga' => 10000,
                'diskon' => 0, // Tidak ada diskon
            ],
            [
                'kategori' => 'makanan',
                'nama_produk' => 'Burger',
                'deskripsi' => 'Burger daging sapi dengan keju lezat.',
                'gambar' => 'uploads/gambar5.jpg',
                'harga' => 35000,
                'diskon' => 0, // Tidak ada diskon
            ],
            [
                'kategori' => 'makanan',
                'nama_produk' => 'Pasta Carbonara',
                'deskripsi' => 'Pasta dengan saus carbonara yang creamy.',
                'gambar' => 'uploads/gambar6.jpg',
                'harga' => 45000,
                'diskon' => 15, // Produk dengan diskon
            ],
        ];

        // Menambahkan produk minuman ke database
        foreach ($minuman as $product) {
            DB::table('products')->insert($product);
        }

        // Menambahkan produk makanan ke database
        foreach ($makanan as $product) {
            DB::table('products')->insert($product);
        }
    }
}
