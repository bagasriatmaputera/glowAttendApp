<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $roleSuperAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'SuperAdmin']);
        $roleAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
        $roleEmployee = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee']);

        // Create Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@glow.com'],
            [
                'username' => 'Owner',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                // 'email_verified_at' => now(),
            ]
        );
        $owner->assignRole($roleSuperAdmin);

        // Create Management
        $management = User::firstOrCreate(
            ['email' => 'management@glow.com'],
            [
                'username' => 'Management',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                // 'email_verified_at' => now(),
            ]
        );
        $management->assignRole($roleAdmin);

        // Create Employee
        $employee = User::firstOrCreate(
            ['email' => 'employee@glow.com'],
            [
                'username' => 'employee',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                // 'email_verified_at' => now(),
            ]
        );
        $employee->assignRole($roleEmployee);
    }
}
