<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class
        ]);

        User::create([
            'name' => 'super',
            'last_name' => 'admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('secret'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'jhon',
            'last_name' => 'doe',
            'email' => 'jhon@gmail.com',
            'password' => Hash::make('secret'),
            'role_id' => 2,
        ]);

        \App\Models\User::factory(10)->create();
    }
}
