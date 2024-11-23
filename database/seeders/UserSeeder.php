<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert multiple users at once
        User::insert([
            [
                'nama' => 'WendaAdmin',
                'no_whatshap' => '082273500223',
                'email' => 'wendaadmin@example.com',
                'password' => bcrypt('wenda123'),
                'alamat' => 'paingan',
                'umur' => 30,
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Damar',
                'no_whatshap' => '089623573070',
                'email' => 'damar@example.com',
                'password' => Hash::make('password123'),
                'alamat' => 'Some Address',
                'umur' => 17,
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '2007-03-15'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Cinta',
                'no_whatshap' => '083831739340',
                'email' => 'cinta@example.com',
                'password' => Hash::make('password123'),
                'alamat' => 'Another Address',
                'umur' => 25,
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1999-02-10'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Wendy',
                'no_whatshap' => '082155495919',
                'email' => 'wendy@example.com',
                'password' => Hash::make('password123'),
                'alamat' => 'Wendy\'s Address',
                'umur' => 40,
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1984-07-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
