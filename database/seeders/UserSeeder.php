<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => 'active',
            'role' => 'admin',
            'password' => Hash::make('admin')
        ]);

        User::create([
            'name' => 'p4m',
            'email' => 'p4m@gmail.com',
            'status' => 'active',
            'role' => 'p4m',
            'password' => Hash::make('p4m')
        ]);

        User::create([
            'name' => 'kajur',
            'email' => 'kajur@gmail.com',
            'status' => 'active',
            'role' => 'kajur',
            'password' => Hash::make('kajur')
        ]);

        User::create([
            'name' => 'kaprodi',
            'email' => 'kaprodi@gmail.com',
            'status' => 'active',
            'role' => 'kaprodi',
            'password' => Hash::make('kaprodi')
        ]);
    }
}
