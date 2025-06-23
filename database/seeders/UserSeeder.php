<?php

namespace Database\Seeders;

use App\Models\AuthBaseModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::create([
            'name' => 'User',
            'email_verified_at' => now(),
            'status' => AuthBaseModel::STATUS_ACTIVE,
            'email' => 'user@dev.com',
            'password' => 'user@dev.com',
        ]);
    }
}
