<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AuthBaseModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dev.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@dev.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@1.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@1.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@2.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@2.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@3.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@3.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@4.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@4.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@5.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@5.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@6.com',
            'email_verified_at' => now(),
            'password' => 'superadmin@6.com',
            'status' => AuthBaseModel::STATUS_ACTIVE,
        ]);
    }
}
