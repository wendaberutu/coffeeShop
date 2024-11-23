<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = 
        [            [
                'nama' => 'WendaAdmin',
                'email' => 'wendaadmin@gmail.com',
                'password' => Hash::make('wenda123'),
                'umur' => 30, // Ensure umur is set
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
                'alamat' => 'Jogja',
                'no_whatshap' => '082273500223',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Damar',
                'email' => 'damar@gmail.com',
                'password' => Hash::make('damar123'),
                'umur' => 20, // Ensure umur is set
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
                'alamat' => 'Jogja',
                'no_whatshap' => '089623573070',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Wendy',
                'email' => 'wendy@gmail.com',
                'password' => Hash::make('wendy123'),
                'umur' => 40, // Ensure umur is set
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
                'alamat' => 'Jogja',
                'no_whatshap' => '082155495919',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Cinta',
                'email' => 'cinta@gmail.com',
                'password' => Hash::make('cinta123'),
                'umur' => 17, // Ensure umur is set
                'tanggal_lahir' => Carbon::createFromFormat('Y-m-d', '1994-05-20'),
                'alamat' => 'Jogja',
                'no_whatshap' => '083831739340',
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        User::insert($users);





      
    }
}
