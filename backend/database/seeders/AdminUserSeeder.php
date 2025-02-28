<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'id' => Str::uuid(),
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'user_type' => 'admin',
            ]
        );
    }
}

