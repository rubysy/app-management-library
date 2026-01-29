<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin
        \App\Models\User::factory()->create([
            'name' => 'Admin Library',
            'email' => 'admin@youlibrary.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'address' => 'Library HQ',
        ]);

        // Staff
        \App\Models\User::factory()->create([
            'name' => 'Staff Petugas',
            'email' => 'staff@youlibrary.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'address' => 'Library Counter 1',
        ]);

        // Reader
        \App\Models\User::factory()->create([
            'name' => 'Pembaca Setia',
            'email' => 'reader@youlibrary.com',
            'password' => bcrypt('password'),
            'role' => 'reader',
            'address' => 'Jl. Mawar No. 123',
        ]);
    }
}
