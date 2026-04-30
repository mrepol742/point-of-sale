<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'cashier@example.com',
                'role' => 'cashier',
            ],
            [
                'email' => 'production@example.com',
                'role' => 'production',
            ],
            [
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create([
                'email' => $user['email'],
                'status' => 'active',
                'username' => $user['role'],
                'role' => $user['role'],
                'password' => bcrypt($user['role']),
            ]);
        }
    }
}
