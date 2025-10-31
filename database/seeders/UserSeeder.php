<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nim_nip' => '2312501170',
            'name' => 'Admin SWYC',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // User
        User::create([
            'nim_nip' => '2312500248',
            'name' => 'Nadine',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
