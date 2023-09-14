<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User 1',
            'email' => 'test1@gmail.com',
            'password' => 'testtest1',
            'phone_number' => '0954654444',
            'address' => 'damascus',
        ]);

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User 2',
            'email' => 'test2@gmail.com',
            'password' => 'testtest2',
            'phone_number' => '0954654444',
            'address' => 'damascus',
        ]);

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User 3',
            'email' => 'test3@gmail.com',
            'password' => 'testtest3',
            'phone_number' => '0954654444',
            'address' => 'damascus',
        ]);

    }
}
