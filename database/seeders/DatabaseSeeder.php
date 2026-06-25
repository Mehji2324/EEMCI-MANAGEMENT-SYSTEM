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

        // 2. Create Demo Accounts
        $password = Hash::make('demo1234');

        User::firstOrCreate(
            ['email' => 'admin@eemci.ma'],
            [
                'role_id'              => $adminRole->id,
                'first_name'           => 'Admin',
                'last_name'            => 'EEMCI',
                'password'             => $password,
                'must_change_password' => false,
            ]
        );

        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@eemci.ma'],
            [
                'role_id'              => Role::where('name', 'teacher')->first()->id,
                'first_name'           => 'Hicham',
                'last_name'            => 'Alaoui',
                'password'             => $password,
                'must_change_password' => false,
            ]
        );

        $studentUser = User::firstOrCreate(
            ['email' => 'student@eemci.ma'],
            [
                'role_id'              => Role::where('name', 'student')->first()->id,
                'first_name'           => 'Salma',
                'last_name'            => 'Kabbaj',
                'password'             => $password,
                'must_change_password' => false,
            ]
        );

        // Optionally, ensure the student profile exists and is validated
        \App\Models\Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'date_of_birth' => '2000-01-01',
                'place_of_birth' => 'Rabat',
                'gender' => 'female',
                'nationality' => 'Moroccan',
                'address' => 'Rabat',
                'status' => 'validated', // Validated so they can login
            ]
        );
    }
}
