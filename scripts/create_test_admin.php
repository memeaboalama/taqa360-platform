<?php
// D:\Newtouch\Projects\taqa360\taqaNew19-10\edu-solutions-platform\scripts\create_test_admin.php

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';

$adminEmail = 'testadmin@example.com';
$adminName = 'TestAdmin';
$adminPassword = Hash::make('TempPass123!');

// Create user
$user = User::create([
    'name' => $adminName,
    'email' => $adminEmail,
    'password' => $adminPassword,
    'is_admin' => 1, // assuming field exists; adjust if different
]);

// Create role and permission
$role = Role::firstOrCreate(['name' => 'driver_manager']);
$perm = Permission::firstOrCreate(['name' => 'manage drivers']);
$role->givePermissionTo($perm);

// Assign role to user
$user->assignRole($role);

echo "Admin user created: {$adminEmail} / TempPass123!\n";
echo "Role: driver_manager\n";
echo "Permission: manage drivers\n";