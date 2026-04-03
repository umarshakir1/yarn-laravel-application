<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $adminRole      = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole      = Role::firstOrCreate(['name' => 'Staff']);
        $managerRole    = Role::firstOrCreate(['name' => 'Manager']);
        $accountantRole = Role::firstOrCreate(['name' => 'Accountant']);

        // Create a default Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole($adminRole);
    }
}
