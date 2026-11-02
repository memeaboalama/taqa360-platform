<?php
// database/seeders/TestAdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        $adminEmail = 'testadmin@example.com';
        $adminName = 'TestAdmin';
        $adminPassword = Hash::make('TempPass123!');

        $user = User::firstOrCreate(
            ['email' => $adminEmail],
            ['name' => $adminName, 'password' => $adminPassword]
        );

        // Create role and permission if not exist
        $role = Role::firstOrCreate(['name' => 'driver_manager']);
        $perm = Permission::firstOrCreate(['name' => 'manage drivers']);
        $role->givePermissionTo($perm);

        // Assign role to user
        $user->assignRole($role);

        $this->command->info("Test admin user created: {$adminEmail} / TempPass123!");
    }
}