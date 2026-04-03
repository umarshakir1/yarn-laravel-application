<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $sections = [
            'view products',
            'view clients',
            'view purchases',
            'view sales',
            'view services',
            'view expenses',
            'view accounts',
            'view transfers',
            'view reports',
        ];

        foreach ($sections as $section) {
            Permission::firstOrCreate(['name' => $section, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($sections);

        Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
    }
}
