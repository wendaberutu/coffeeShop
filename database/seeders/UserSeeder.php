<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' =>'WendaAdmin',
            'no_whatshap' =>'082273500223',
            'email' =>'wendaadmin@example.com',
            'password' => bcrypt('wenda123'),
            'umur' => 30, // Ensure umur is set
            'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
