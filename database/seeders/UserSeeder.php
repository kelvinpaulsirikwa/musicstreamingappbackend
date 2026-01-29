<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@musicstream.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create artist user
        User::create([
            'name' => 'John Artist',
            'username' => 'johnartist',
            'email' => 'artist@musicstream.com',
            'password' => Hash::make('password'),
            'role' => 'artist',
        ]);
    }
}
