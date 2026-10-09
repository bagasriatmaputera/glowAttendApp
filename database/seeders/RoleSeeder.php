<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $managementRole = Role::create(['name' => 'Management']);
        $adminRole = Role::create(['name' => 'Admin']);
        $employeeRole = Role::create(['name' => 'Employee']);

        // Create users
        $management = User::create([
            'username' => 'management',
            'email' => 'management@example.com',
            'password' => Hash::make('password'),
        ]);
        $management->assignRole('Management');

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        $employee = User::create([
            'username' => 'employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
        ]);
        $employee->assignRole('Employee');

        // Create Employee profile record
        \App\Models\Employee::create([
            'user_id' => $employee->id,
            'employee_code' => 'EMP-001',
            'full_name' => 'Demo Employee',
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'position' => 'Stylist',
            'join_date' => now()->subMonths(6)->toDateString(),
            'is_active' => true,
        ]);

        $this->command->info('Roles and users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Management: management@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Employee: employee@example.com / password');
    }
}
