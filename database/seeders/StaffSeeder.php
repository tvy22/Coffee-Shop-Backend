<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@coffeeshop.com'],
            [
                'name' => 'Default Staff',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ]
        );
    }
}
