<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@example.com',
            'phone' => '+71111111111',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);


        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'phone' => '+79179115262',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);
    }
}
