<?php

namespace Database\Seeders\Admin;

use App\Support\Enum\StatusEnum;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * LocalAdminSeeder
 * ----------------
 * Makes sure the local preview login below always works:
 *
 *   email    : admin@gmail.com
 *   password : 123456789
 *
 * The project's AdminSeeder truncates the `admins` table, which would destroy
 * existing admins, so this seeder deliberately does not: it matches on the
 * email and only updates the password when the row already exists.
 *
 * Idempotent: safe to run as many times as needed.
 *
 * Run with:
 *   php artisan db:seed --class=Database\Seeders\Admin\LocalAdminSeeder
 */
class LocalAdminSeeder extends Seeder
{
    private const EMAIL    = 'admin@gmail.com';
    private const PASSWORD = '123456789';

    public function run(): void
    {
        $admin = Admin::where('email', self::EMAIL)->first();

        if ($admin) {
            // Existing account: refresh the password only, keep id and name.
            $admin->update(['password' => Hash::make(self::PASSWORD)]);

            $this->command->info("Admin #{$admin->id} ({$admin->email}) already existed — password refreshed.");
            return;
        }

        $admin = Admin::create([
            'name'     => 'admin',
            'email'    => self::EMAIL,
            'password' => Hash::make(self::PASSWORD),
            'type'     => 'admin',
            'status'   => StatusEnum::ACTIVE->value,
        ]);

        $this->command->info("Admin #{$admin->id} created: {$admin->email}");
    }
}