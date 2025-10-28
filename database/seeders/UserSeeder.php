<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin/test user
        User::factory()->create([
            'name' => 'Kanban Admin',
            'email' => 'advanced@kanban.com',
            'password' => Hash::make('demo.advancedkanban!2025'),
        ]);

        // Additional random users
        User::factory(10)->create();
    }
}
