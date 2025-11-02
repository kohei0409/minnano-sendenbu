<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'name' => '管理者',
            'email' => 'admin@sendenbu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'user_level' => 'premium2',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);
    }
}
