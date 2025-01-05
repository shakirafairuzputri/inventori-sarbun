<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);
        User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password123'),
            'role' => 'supervisor',
            'status' => 'Aktif',
        ]);

        User::create([
            'name' => 'Pegawai',
            'email' => 'pegawai@example.com',
            'password' => bcrypt('password456'),
            'role' => 'pegawai',
            'status' => 'Aktif',
        ]);
        // User::create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('123'),
        //     'role' => 'admin',
        //     'status' => 'Aktif',
        // ]);
    }
}
