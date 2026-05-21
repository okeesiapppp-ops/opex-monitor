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
            'name' => 'Administrator',
            'email' => 'admin@uta.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '081234567890',
            'company' => 'United Tractors ARIA',
        ]);

        // Vendor 1 (UT Part)
        User::create([
            'name' => 'UT Part Vendor',
            'email' => 'vendor@uta.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'status' => 'active',
            'phone' => '081234567891',
            'company' => 'UT Part',
        ]);

        // Vendor 2
        User::create([
            'name' => 'Vendor Dua',
            'email' => 'vendor2@uta.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'status' => 'active',
            'phone' => '081234567892',
            'company' => 'UT Part',
        ]);

        // Planner 1 (PAMA)
        User::create([
            'name' => 'Planner PAMA',
            'email' => 'planner@pama.com',
            'password' => Hash::make('password'),
            'role' => 'planner',
            'status' => 'active',
            'phone' => '081234567893',
            'company' => 'PAMA ARIA',
        ]);

        // Planner 2
        User::create([
            'name' => 'Planner Dua',
            'email' => 'planner2@pama.com',
            'password' => Hash::make('password'),
            'role' => 'planner',
            'status' => 'active',
            'phone' => '081234567894',
            'company' => 'PAMA ARIA',
        ]);
    }
}