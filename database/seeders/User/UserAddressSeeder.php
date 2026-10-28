<?php

namespace Database\Seeders\User;

use App\Domain\User\Models\User;
use App\Domain\User\Models\UserAddress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserAddressSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        UserAddress::truncate();
        Schema::enableForeignKeyConstraints();

        UserAddress::factory()
            ->count(30)
            ->create();
    }
}
