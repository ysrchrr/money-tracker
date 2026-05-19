<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@moneytracker.test'],
            [
                'name' => 'Money Tracker Admin',
                'password' => 'password',
                'role' => 'superadmin',
                'member_type' => 'gold',
                'is_cutoff_enabled' => false,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'member@moneytracker.test'],
            [
                'name' => 'Demo Member',
                'password' => 'password',
                'role' => 'member',
                'member_type' => 'gold',
                'is_cutoff_enabled' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
