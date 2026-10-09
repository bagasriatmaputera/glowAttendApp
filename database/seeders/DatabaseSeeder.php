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
        $roleManagement = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Management']);
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
        $owner->assignRole($roleManagement);

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

        // Create Employee profile record
        \App\Models\Employee::firstOrCreate(
            ['user_id' => $employee->id],
            [
                'employee_code' => 'EMP-001',
                'full_name' => 'Demo Employee',
                'phone' => '081234567890',
                'address' => 'Jakarta, Indonesia',
                'position' => 'Stylist',
                'join_date' => now()->subMonths(6)->toDateString(),
                'is_active' => true,
            ]
        );
    }
}
