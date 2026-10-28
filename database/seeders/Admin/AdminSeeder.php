<?php

namespace Database\Seeders\Admin;

use App\Support\Enum\StatusEnum;
use Domain\Admin\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admins')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => StatusEnum::ACTIVE->value,
            'type' => 'admin',
            'password' => \Hash::make(123456789),

        ]);
    }
}
