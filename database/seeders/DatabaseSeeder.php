<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator', 'description' => 'System Administrator']
        );

        Role::firstOrCreate(
            ['name' => 'teacher'],
            ['display_name' => 'Teacher', 'description' => 'Faculty Member']
        );

        Role::firstOrCreate(
            ['name' => 'student'],
            ['display_name' => 'Student', 'description' => 'Enrolled Student']
        );

        // 2. Create Default Admin User
        $adminEmail = env('ADMIN_EMAIL', 'admin@eemci.edu');
        $adminPassword = env('ADMIN_PASSWORD') ?: \Illuminate\Support\Str::random(16);

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'role_id'              => $adminRole->id,
                'first_name'           => 'System',
                'last_name'            => 'Admin',
                'password'             => Hash::make($adminPassword),
                'must_change_password' => true,
            ]
        );

        if ($admin->wasRecentlyCreated && isset($this->command)) {
            $this->command->info("Admin created with Email: {$adminEmail} and Password: {$adminPassword}");
        }
    }
}
