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
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $employeeRole = Role::create(['name' => 'Employee']);

        // Create users
        $superAdmin = User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

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

        $this->command->info('Roles and users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Employee: employee@example.com / password');
    }
}
