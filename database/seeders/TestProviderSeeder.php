<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::updateOrCreate([
            'email' => 'provider@test.com',
        ], [
            'name' => 'Test Provider',
            'password' => bcrypt('password123'),
            'role' => 'provider',
            'is_active' => true,
            'is_verified' => true,
            'phone' => '+241123456789',
        ]);
    }
}
